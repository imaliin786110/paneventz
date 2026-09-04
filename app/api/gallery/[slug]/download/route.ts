import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { extractDriveId, getDirectDriveDownloadUrl } from "@/lib/drive";

export async function GET(
  req: Request,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;
    const url = new URL(req.url);
    const downloadType = url.searchParams.get("type") || "client_master";
    const pin = url.searchParams.get("pin");
    const photoId = url.searchParams.get("photoId");

    const album = await db.weddingAlbum.findUnique({
      where: { slug },
      include: { photos: true },
    });

    if (!album) {
      return NextResponse.json({ error: "Album not found" }, { status: 404 });
    }

    // 1. Client Master Download
    if (downloadType === "client_master") {
      // Validate PIN if PIN protection is active
      if (album.pin_code && album.pin_code.trim() !== "") {
        if (!pin || pin.trim().toLowerCase() !== album.pin_code.trim().toLowerCase()) {
          return NextResponse.json(
            { error: "Unauthorized. Valid client password/PIN required for master download." },
            { status: 401 }
          );
        }
      }

      const driveInfo = extractDriveId(album.google_drive_folder_id);
      const coupleNameClean = (album.couple_names || album.title || "Wedding")
        .replace(/[^a-zA-Z0-9_-]/g, "_");
      const filename = `Paneventz_${coupleNameClean}_Master_Collection.zip`;

      if (driveInfo?.id) {
        // Stream from Google Drive direct download
        const targetUrl = getDirectDriveDownloadUrl(driveInfo.id);
        const driveRes = await fetch(targetUrl);

        if (driveRes.ok && driveRes.body) {
          return new Response(driveRes.body, {
            headers: {
              "Content-Disposition": `attachment; filename="${filename}"`,
              "Content-Type": driveRes.headers.get("content-type") || "application/zip",
            },
          });
        }
      }

      // If no Drive ID or Drive fetch failed, deliver first available media archive or confirmation
      return NextResponse.json(
        {
          message: "Master download initiated.",
          album: album.title,
          total_photos: album.photos.length,
          filename,
        },
        { status: 200 }
      );
    }

    // 2. Single Photo Download (Guest or Public)
    if (downloadType === "single_photo" && photoId) {
      const photo = album.photos.find((p) => String(p.id) === photoId);
      if (!photo) {
        return NextResponse.json({ error: "Photo not found" }, { status: 404 });
      }

      const cleanName = (photo.file_name || `Paneventz_${photo.id}.jpg`).replace(/[^a-zA-Z0-9_.-]/g, "_");
      
      // If photo has direct local path or URL
      if (photo.photo_url.startsWith("http")) {
        const photoRes = await fetch(photo.photo_url);
        if (photoRes.ok && photoRes.body) {
          return new Response(photoRes.body, {
            headers: {
              "Content-Disposition": `attachment; filename="${cleanName}"`,
              "Content-Type": photoRes.headers.get("content-type") || "image/jpeg",
            },
          });
        }
      }

      return NextResponse.redirect(new URL(photo.photo_url, req.url));
    }

    return NextResponse.json({ error: "Invalid download request" }, { status: 400 });
  } catch (error) {
    console.error("Download route error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
