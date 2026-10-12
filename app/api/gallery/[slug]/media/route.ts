import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { extractDriveId, getDriveThumbnailUrl } from "@/lib/drive";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

/**
 * Same-origin media proxy used by camera-only face matching. It prevents the
 * browser from needing CORS access to Google Drive and never lists an album.
 */
export async function GET(
  req: Request,
  { params }: { params: Promise<{ slug: string }> },
) {
  try {
    const { slug } = await params;
    const photoId = new URL(req.url).searchParams.get("photoId");
    if (!photoId) return NextResponse.json({ error: "Photo id is required" }, { status: 400 });

    const album = await db.weddingAlbum.findUnique({ where: { slug } });
    if (!album) return NextResponse.json({ error: "Gallery not found" }, { status: 404 });

    const photo = await db.albumPhoto.findFirst({
      where: { id: BigInt(photoId), wedding_album_id: album.id },
      select: { photo_url: true, thumbnail_url: true, is_video: true },
    });
    if (!photo) return NextResponse.json({ error: "Photo not found" }, { status: 404 });

    const source = photo.thumbnail_url || photo.photo_url;
    if (!source.startsWith("http")) {
      const path = await import("path");
      const fs = await import("fs/promises");
      const relativePath = source.replace(/^\/+/, "");
      const publicRoot = path.resolve(process.cwd(), "public");
      const localPath = path.resolve(publicRoot, relativePath);
      if (!localPath.startsWith(`${publicRoot}${path.sep}`)) {
        return NextResponse.json({ error: "Invalid media path" }, { status: 400 });
      }
      try {
        const buffer = await fs.readFile(localPath);
        const extension = path.extname(localPath).toLowerCase();
        const contentType = extension === ".png" ? "image/png"
          : extension === ".webp" ? "image/webp"
          : extension === ".avif" ? "image/avif"
          : extension === ".mp4" ? "video/mp4"
          : "image/jpeg";
        return new Response(buffer, {
          headers: {
            "Content-Type": contentType,
            "Cache-Control": "private, max-age=300",
            "X-Content-Type-Options": "nosniff",
          },
        });
      } catch {
        return NextResponse.json({ error: "Media unavailable" }, { status: 404 });
      }
    }

    const driveInfo = extractDriveId(source);
    const upstream = driveInfo?.id ? getDriveThumbnailUrl(driveInfo.id) : source;
    const response = await fetch(upstream, { redirect: "follow" });
    if (!response.ok || !response.body) {
      return NextResponse.json({ error: "Media unavailable" }, { status: 502 });
    }

    return new Response(response.body, {
      headers: {
        "Content-Type": response.headers.get("content-type") || (photo.is_video ? "video/mp4" : "image/jpeg"),
        "Cache-Control": "private, max-age=300",
        "X-Content-Type-Options": "nosniff",
      },
    });
  } catch (error) {
    console.error("Gallery media proxy error:", error);
    return NextResponse.json({ error: "Media proxy failed" }, { status: 500 });
  }
}
