import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import { getDriveEmbedUrl, getDriveBrowseUrl } from "@/lib/drive";
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
    if (album.google_drive_folder_id) {
      try {
        const { syncAlbumFromGoogleDrive } = await import("@/lib/drive-sync");
        const syncRes = await syncAlbumFromGoogleDrive(album.id, album.google_drive_folder_id);
        if (syncRes.added > 0 || photos.length === 0) {
          photos = await db.albumPhoto.findMany({
            where: { wedding_album_id: album.id },
            orderBy: { created_at: "desc" },
          });
        }
      } catch (syncErr) {
        console.warn("Backend Drive sync attempt warning:", syncErr);
      }
    }

    const embedUrl = getDriveEmbedUrl(album.google_drive_folder_id);
    const browseUrl = getDriveBrowseUrl(album.google_drive_folder_id);

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
        google_drive_folder_id: album.google_drive_folder_id,
        drive_embed_url: embedUrl,
        drive_browse_url: browseUrl,
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
