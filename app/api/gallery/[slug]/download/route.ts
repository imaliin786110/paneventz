import { NextResponse } from "next/server";
import { cookies } from "next/headers";
import { db } from "@/lib/db";
import { extractDriveId } from "@/lib/drive";
import { galleryAccessCookieName, hasGalleryAccess } from "@/lib/gallery-access";

export async function GET(
  req: Request,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;
    const url = new URL(req.url);
    const downloadType = url.searchParams.get("type") || "client_master";
    const photoId = url.searchParams.get("photoId");

    const album = await db.weddingAlbum.findUnique({
      where: { slug },
      include: { photos: true },
    });

    if (!album) {
      return NextResponse.json({ error: "Album not found" }, { status: 404 });
    }

    const cookieStore = await cookies();
    const isUnlocked = hasGalleryAccess(
      slug,
      album.pin_code,
      cookieStore.get(galleryAccessCookieName(slug))?.value,
    );

    // 1. Client Master Download
    if (downloadType === "client_master") {
      if (!isUnlocked) {
        return NextResponse.json({ error: "Unlock this private gallery before downloading master archive." }, { status: 401 });
      }

      // The customer portal is intentionally a photo-only experience. Never
      // redirect customers to the raw Google Drive folder.
      return NextResponse.redirect(new URL(`/client-portal?album=${album.slug}`, req.url));
    }

    // 2. Single Photo Download (Guest or Public)
    if (downloadType === "single_photo" && photoId) {
      if (!album.allow_downloads) {
        return NextResponse.json({ error: "Downloads are disabled for this gallery." }, { status: 403 });
      }

      const photo = album.photos.find((p) => String(p.id) === photoId);
      if (!photo) {
        return NextResponse.json({ error: "Photo not found" }, { status: 404 });
      }

      // Require unlock only if album is private and not unlocked
      if (!album.is_public && album.pin_code && !isUnlocked) {
        return NextResponse.json({ error: "Unlock this private gallery before downloading media." }, { status: 401 });
      }

      const cleanName = (photo.file_name || `Paneventz_${photo.id}.jpg`).replace(/[^a-zA-Z0-9_.-]/g, "_");

      // If photo has a remote URL (including a Drive thumbnail), fetch it on
      // the server and return it as an attachment so Drive is never opened.
      if (photo.photo_url.startsWith("http")) {
        try {
          const driveInfo = extractDriveId(photo.photo_url);
          const sourceUrl = driveInfo?.id
            ? `https://drive.google.com/uc?export=download&id=${driveInfo.id}`
            : photo.photo_url;
          let photoRes = await fetch(sourceUrl, { redirect: "follow" });
          const firstType = photoRes.headers.get("content-type") || "";
          // Large Drive files can answer the `uc` URL with a confirmation HTML
          // page. Fall back to the thumbnail rather than downloading HTML.
          if ((!photoRes.ok || !photoRes.body || (!firstType.startsWith("image/") && !firstType.startsWith("video/"))) && driveInfo?.id) {
            photoRes = await fetch(`https://drive.google.com/thumbnail?id=${driveInfo.id}&sz=w4096`, { redirect: "follow" });
          }
          if (photoRes.ok && photoRes.body) {
            return new Response(photoRes.body, {
              headers: {
                "Content-Disposition": `attachment; filename="${cleanName}"`,
                "Content-Type": photoRes.headers.get("content-type") || "image/jpeg",
              },
            });
          }
        } catch (fetchErr) {
          console.warn("Failed fetching remote photo for download stream:", fetchErr);
        }
        return NextResponse.redirect(photo.photo_url);
      }

      // If photo is stored locally in /public
      try {
        const fs = await import("fs/promises");
        const path = await import("path");
        const relativePath = photo.photo_url.replace(/^\//, "");
        const localPath = path.join(process.cwd(), "public", relativePath);
        const fileBuffer = await fs.readFile(localPath);

        const ext = path.extname(cleanName).toLowerCase();
        const contentType = ext === ".png" ? "image/png" : ext === ".webp" ? "image/webp" : "image/jpeg";

        return new Response(fileBuffer, {
          headers: {
            "Content-Disposition": `attachment; filename="${cleanName}"`,
            "Content-Type": contentType,
          },
        });
      } catch (readErr) {
        console.warn("Local photo file read failed, falling back to redirect:", readErr);
        return NextResponse.redirect(new URL(photo.photo_url, req.url));
      }
    }

    return NextResponse.json({ error: "Invalid download request" }, { status: 400 });
  } catch (error) {
    console.error("Download route error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
