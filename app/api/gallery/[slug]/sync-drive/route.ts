import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import { syncAlbumFromGoogleDrive } from "@/lib/drive-sync";

export async function POST(
  req: Request,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;
    const url = new URL(req.url);
    const type = url.searchParams.get("type") || "guest";

    const album = await db.weddingAlbum.findUnique({
      where: { slug },
      include: { photos: { orderBy: { created_at: "desc" } } },
    });

    if (!album) {
      return NextResponse.json({ error: "Album not found" }, { status: 404 });
    }

    const driveFolder =
      type === "master"
        ? album.google_drive_folder_id || album.guest_google_drive_folder_id
        : album.guest_google_drive_folder_id || album.google_drive_folder_id;

    if (!driveFolder) {
      return NextResponse.json({ error: "No Google Drive folder configured for this album" }, { status: 400 });
    }

    const result = await syncAlbumFromGoogleDrive(album.id, driveFolder);

    const updatedPhotos = await db.albumPhoto.findMany({
      where: { wedding_album_id: album.id },
      orderBy: { created_at: "desc" },
    });

    return NextResponse.json({
      success: true,
      added: result.added,
      total: updatedPhotos.length,
      photos: serializeData(updatedPhotos),
    });
  } catch (error) {
    console.error("Drive sync route error:", error);
    return NextResponse.json({ error: "Failed to sync Drive photos" }, { status: 500 });
  }
}
