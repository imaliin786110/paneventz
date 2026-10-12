import { NextResponse } from "next/server";
import { cookies } from "next/headers";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import { getDriveEmbedUrl, getDriveBrowseUrl } from "@/lib/drive";
import { galleryAccessCookieName, hasGalleryAccess } from "@/lib/gallery-access";

export async function GET(
  req: Request,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;
    const album = await db.weddingAlbum.findUnique({
      where: { slug },
      include: {
        photos: {
          orderBy: { created_at: "desc" },
        },
      },
    });

    if (!album) {
      return NextResponse.json({ error: "Gallery not found" }, { status: 404 });
    }

    const cookieStore = await cookies();
    const isUnlocked = hasGalleryAccess(
      slug,
      album.pin_code,
      cookieStore.get(galleryAccessCookieName(slug))?.value,
    );

    const safeAlbum = {
      id: album.id,
      title: album.title,
      slug: album.slug,
      couple_names: album.couple_names,
      location: album.location,
      cover_image: album.cover_image,
      event_date: album.event_date,
      has_pin: Boolean(album.pin_code),
      pin_code: Boolean(album.pin_code),
      is_public: album.is_public,
      enable_face_ai: album.enable_face_ai,
      allow_downloads: album.allow_downloads,
      guest_google_drive_folder_id: album.guest_google_drive_folder_id,
      guest_drive_embed_url: getDriveEmbedUrl(album.guest_google_drive_folder_id),
      guest_drive_browse_url: getDriveBrowseUrl(album.guest_google_drive_folder_id),
      master_drive_embed_url: isUnlocked ? getDriveEmbedUrl(album.google_drive_folder_id) : null,
      master_drive_browse_url: isUnlocked ? getDriveBrowseUrl(album.google_drive_folder_id) : null,
    };

    let photos = album.photos;

    // If album has no photos in database, attempt backend sync from Guest Google Drive
    if (photos.length === 0 && album.guest_google_drive_folder_id) {
      try {
        const { syncAlbumFromGoogleDrive } = await import("@/lib/drive-sync");
        const syncRes = await syncAlbumFromGoogleDrive(album.id, album.guest_google_drive_folder_id);
        if (syncRes.added > 0) {
          photos = await db.albumPhoto.findMany({
            where: { wedding_album_id: album.id },
            orderBy: { created_at: "desc" },
          });
        }
      } catch (syncErr) {
        console.warn("Gallery photos Drive auto-sync warning:", syncErr);
      }
    }

    return NextResponse.json({
      album: serializeData(safeAlbum),
      photos: serializeData(photos),
      isUnlocked,
    });
  } catch (error) {
    console.error("Gallery Photos API Error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}

