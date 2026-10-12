import { db } from "@/lib/db";
import { extractDriveId, getDriveThumbnailUrl } from "@/lib/drive";
import { refreshGallery } from "@/lib/content-revalidation";

export interface SyncedDriveFile {
  id: string;
  name: string;
  photoUrl: string;
  thumbnailUrl: string;
  isVideo: boolean;
}

/**
 * Fetch list of files from a Google Drive folder.
 * Uses Google Drive API key if available, or extracts from public folder endpoints.
 */
export async function fetchDriveFolderFiles(folderUrlOrId: string): Promise<SyncedDriveFile[]> {
  const driveInfo = extractDriveId(folderUrlOrId);
  if (!driveInfo?.id) return [];
  const folderId = driveInfo.id;

  const apiKey =
    process.env.GOOGLE_DRIVE_API_KEY ||
    process.env.GOOGLE_API_KEY ||
    process.env.NEXT_PUBLIC_GOOGLE_API_KEY ||
    "";

  const files: SyncedDriveFile[] = [];

  // Method 1: Google Drive API v3
  if (apiKey) {
    try {
      const query = encodeURIComponent(`'${folderId}' in parents and trashed = false`);
      const url = `https://www.googleapis.com/drive/v3/files?q=${query}&fields=files(id,name,mimeType,thumbnailLink)&pageSize=200&key=${apiKey}`;
      const res = await fetch(url, { headers: { "Accept": "application/json" } });
      const data = await res.json();

      if (data.files && Array.isArray(data.files)) {
        for (const item of data.files) {
          const isVideo = String(item.mimeType || "").includes("video");
          const isImage = String(item.mimeType || "").includes("image") || /\.(jpe?g|png|webp|avif)$/i.test(item.name || "");
          if (isImage || isVideo) {
            files.push({
              id: item.id,
              name: item.name || `Drive_${item.id}.jpg`,
              photoUrl: `https://drive.google.com/thumbnail?id=${item.id}&sz=w2048`,
              thumbnailUrl: `https://drive.google.com/thumbnail?id=${item.id}&sz=w600`,
              isVideo,
            });
          }
        }
      }
    } catch (apiErr) {
      console.warn("Google Drive API query error:", apiErr);
    }
  }

  // Method 2: Public Google Drive HTML scraping fallback
  if (files.length === 0) {
    try {
      const res = await fetch(`https://drive.google.com/drive/folders/${folderId}`, {
        headers: {
          "User-Agent":
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36",
          "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        },
      });

      if (res.ok) {
        const text = await res.text();
        // Regex to extract file IDs and image names embedded in Google Drive initialData
        const pattern = /\[\"([a-zA-Z0-9_-]{28,38})\",\[\"([^\"]+\.(?:jpe?g|png|webp|mp4|mov))\"/gi;
        const seenIds = new Set<string>();
        let match;

        while ((match = pattern.exec(text)) !== null) {
          const fId = match[1];
          const fName = match[2];
          if (!seenIds.has(fId)) {
            seenIds.add(fId);
            const isVideo = /\.(mp4|mov)$/i.test(fName);
            files.push({
              id: fId,
              name: fName,
              photoUrl: `https://drive.google.com/thumbnail?id=${fId}&sz=w2048`,
              thumbnailUrl: `https://drive.google.com/thumbnail?id=${fId}&sz=w600`,
              isVideo,
            });
          }
        }
      }
    } catch (scrapeErr) {
      console.warn("Google Drive HTML fetch fallback error:", scrapeErr);
    }
  }

  return files;
}

/**
 * Syncs Google Drive folder files directly into the wedding album database.
 */
export async function syncAlbumFromGoogleDrive(albumId: bigint | string, driveFolderUrlOrId: string) {
  const albumBigIntId = typeof albumId === "string" ? BigInt(albumId) : albumId;
  const album = await db.weddingAlbum.findUnique({
    where: { id: albumBigIntId },
    include: { photos: { select: { photo_url: true, file_name: true } } },
  });

  if (!album) return { success: false, error: "Album not found", added: 0 };

  const driveFiles = await fetchDriveFolderFiles(driveFolderUrlOrId);
  if (!driveFiles.length) {
    return { success: true, added: 0, total: album.photos.length, message: "No new files returned from Drive folder." };
  }

  const existingUrls = new Set(album.photos.map((p) => p.photo_url));
  const newPhotosToInsert = driveFiles
    .filter((file) => !existingUrls.has(file.photoUrl))
    .map((file) => ({
      wedding_album_id: album.id,
      photo_url: file.photoUrl,
      thumbnail_url: file.thumbnailUrl,
      file_name: file.name,
      is_video: file.isVideo,
      faces_count: 0,
      created_at: new Date(),
      updated_at: new Date(),
    }));

  if (newPhotosToInsert.length > 0) {
    await db.albumPhoto.createMany({ data: newPhotosToInsert });
    refreshGallery(album.slug);
  }

  return {
    success: true,
    added: newPhotosToInsert.length,
    total: album.photos.length + newPhotosToInsert.length,
  };
}
