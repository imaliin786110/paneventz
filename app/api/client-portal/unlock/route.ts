import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import { galleryAccessCookieName, galleryAccessToken } from "@/lib/gallery-access";

export async function POST(req: Request) {
  try {
    const { pin } = await req.json();
    if (!pin) {
      return NextResponse.json({ error: "PIN is required" }, { status: 422 });
    }

    const album = await db.weddingAlbum.findFirst({
      where: { pin_code: pin.trim() },
      include: {
        photos: {
          orderBy: { created_at: "desc" },
        },
      },
    });

    if (!album) {
      return NextResponse.json({ error: "Invalid PIN code" }, { status: 401 });
    }

    let photos = album.photos || [];

    // Keep the customer photo grid current when new files are added to Drive.
    const syncFolders = [
      album.google_drive_folder_id ? { url: album.google_drive_folder_id, hint: "image" as const } : null,
      album.client_video_google_drive_folder_id ? { url: album.client_video_google_drive_folder_id, hint: "video" as const } : null,
    ].filter((folder): folder is { url: string; hint: "video" | "image" } => Boolean(folder));
    if (syncFolders.length) {
      try {
        const { syncAlbumFromGoogleDrive } = await import("@/lib/drive-sync");
        let added = 0;
        for (const folder of syncFolders) {
          const syncRes = await syncAlbumFromGoogleDrive(album.id, folder.url, folder.hint);
          added += syncRes.added;
        }
        if (added > 0 || photos.length === 0) {
          photos = await db.albumPhoto.findMany({
            where: { wedding_album_id: album.id },
            orderBy: { created_at: "desc" },
          });
        }
      } catch (syncErr) {
        console.warn("Backend Drive sync attempt warning:", syncErr);
      }
    }

    const response = NextResponse.json({
      success: true,
      album: serializeData({
        id: album.id,
        slug: album.slug,
        title: album.title,
        couple_names: album.couple_names,
        location: album.location,
        event_date: album.event_date,
        cover_image: album.cover_image,
        allow_downloads: album.allow_downloads,
        // Never expose raw Google Drive identifiers or browse URLs to clients.
        photos: photos,
      }),
    });

    response.cookies.set({
      name: galleryAccessCookieName(album.slug),
      value: galleryAccessToken(album.slug, album.pin_code || pin),
      httpOnly: true,
      sameSite: "lax",
      secure: process.env.NODE_ENV === "production",
      maxAge: 60 * 60 * 8,
      path: "/",
    });

    return response;
  } catch (error) {
    console.error("Client Portal Unlock Error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
