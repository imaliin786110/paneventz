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
export async function fetchDriveFolderFiles(folderUrlOrId: string, fileTypeHint?: "video" | "image"): Promise<SyncedDriveFile[]> {
  const driveInfo = extractDriveId(folderUrlOrId);
  if (!driveInfo?.id) return [];
  const folderId = driveInfo.id;

  const configuredSetting = await db.websiteSetting.findFirst({
    select: { google_drive_api_key: true },
  }).catch(() => null);

  const apiKey =
    process.env.GOOGLE_DRIVE_API_KEY ||
    process.env.GOOGLE_API_KEY ||
    process.env.NEXT_PUBLIC_GOOGLE_API_KEY ||
    configuredSetting?.google_drive_api_key ||
    "";

  const files: SyncedDriveFile[] = [];

  // Method 1: Google Drive API v3
  if (apiKey) {
    try {
      // Admin links may point to one Drive video file rather than a folder.
      // Resolve that file directly before attempting the folder listing query.
      if (driveInfo.type === "file") {
        const res = await fetch(
          `https://www.googleapis.com/drive/v3/files/${encodeURIComponent(folderId)}?fields=id,name,mimeType,thumbnailLink&key=${encodeURIComponent(apiKey)}`,
          { headers: { Accept: "application/json" } },
        );
        const item = await res.json();
        const isVideo = String(item.mimeType || "").includes("video") || /\.(mp4|webm|mov|m4v|avi|mkv)$/i.test(item.name || "");
        const isImage = String(item.mimeType || "").includes("image") || /\.(jpe?g|png|webp|avif)$/i.test(item.name || "");
        if (res.ok && item.id && (isImage || isVideo)) {
          files.push({
            id: item.id,
            name: item.name || `Drive_${item.id}${isVideo ? ".mp4" : ".jpg"}`,
            photoUrl: `https://drive.google.com/thumbnail?id=${item.id}&sz=w2048`,
            thumbnailUrl: `https://drive.google.com/thumbnail?id=${item.id}&sz=w600`,
            isVideo,
          });
        }
      }

      if (driveInfo.type === "file" && files.length > 0) {
        return files;
      }

      let pageToken = "";
      let pageCount = 0;

      // Drive returns paginated results. The previous implementation only
      // read the first page, which silently capped larger wedding albums.
      do {
        const params = new URLSearchParams({
          q: `'${folderId}' in parents and trashed = false`,
          fields: "nextPageToken,files(id,name,mimeType,thumbnailLink)",
          pageSize: "1000",
          key: apiKey,
        });
        if (pageToken) params.set("pageToken", pageToken);
        const res = await fetch(`https://www.googleapis.com/drive/v3/files?${params.toString()}`, {
          headers: { "Accept": "application/json" },
        });
        const data = await res.json();

        if (Array.isArray(data.files)) {
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
        pageToken = typeof data.nextPageToken === "string" ? data.nextPageToken : "";
        pageCount += 1;
      } while (pageToken && pageCount < 50);
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
        // Drive's folder markup has changed over time. Support both the
        // original initialData shape and the current grid markup, where each
        // item exposes aria-label="filename.jpg" and data-id="...".
        const patterns = [
          /\[\"([a-zA-Z0-9_-]{28,44})\",\[\"([^\"]+\.(?:jpe?g|png|webp|avif|mp4|mov))\"/gi,
          /aria-label="([^\"]+\.(?:jpe?g|png|webp|avif|mp4|mov))[^\"]*"[\s\S]{0,1200}?data-id="([a-zA-Z0-9_-]{20,})"/gi,
        ];
        const seenIds = new Set<string>();

        for (const pattern of patterns) {
          let match;
          while ((match = pattern.exec(text)) !== null) {
            const fName = pattern === patterns[0] ? match[2] : match[1];
            const fId = pattern === patterns[0] ? match[1] : match[2];
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
      }
    } catch (scrapeErr) {
      console.warn("Google Drive HTML fetch fallback error:", scrapeErr);
    }
  }

  // A public single-file link can still be indexed without a Drive API key.
  // The admin field tells us whether this link belongs to the video or photo stream.
  if (driveInfo.type === "file" && fileTypeHint) {
    files.push({
      id: driveInfo.id,
      name: `Drive_${driveInfo.id}${fileTypeHint === "video" ? ".mp4" : ".jpg"}`,
      photoUrl: `https://drive.google.com/thumbnail?id=${driveInfo.id}&sz=w2048`,
      thumbnailUrl: `https://drive.google.com/thumbnail?id=${driveInfo.id}&sz=w600`,
      isVideo: fileTypeHint === "video",
    });
  }

  return files;
}

/**
 * Syncs Google Drive folder files directly into the wedding album database.
 */
export async function syncAlbumFromGoogleDrive(albumId: bigint | string, driveFolderUrlOrId: string, fileTypeHint?: "video" | "image") {
  const albumBigIntId = typeof albumId === "string" ? BigInt(albumId) : albumId;
  const album = await db.weddingAlbum.findUnique({
    where: { id: albumBigIntId },
    include: { photos: { select: { photo_url: true, file_name: true } } },
  });

  if (!album) return { success: false, error: "Album not found", added: 0 };

  const driveFiles = await fetchDriveFolderFiles(driveFolderUrlOrId, fileTypeHint);
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
