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

    const driveFolders = type === "master"
      ? [
          album.google_drive_folder_id ? { url: album.google_drive_folder_id, hint: "image" as const } : null,
          album.client_video_google_drive_folder_id ? { url: album.client_video_google_drive_folder_id, hint: "video" as const } : null,
        ]
      : [{ url: album.guest_google_drive_folder_id || album.google_drive_folder_id, hint: "image" as const }];
    const configuredFolders = driveFolders.filter(
      (folder): folder is { url: string; hint: "video" | "image" } => Boolean(folder?.url),
    );

    if (!configuredFolders.length) {
      return NextResponse.json({ error: "No Google Drive folder configured for this album" }, { status: 400 });
    }

    let added = 0;
    for (const driveFolder of configuredFolders) {
      const result = await syncAlbumFromGoogleDrive(album.id, driveFolder.url, driveFolder.hint);
      added += result.added;
    }

    const updatedPhotos = await db.albumPhoto.findMany({
      where: { wedding_album_id: album.id },
      orderBy: { created_at: "desc" },
    });

    return NextResponse.json({
      success: true,
      added,
      total: updatedPhotos.length,
      photos: serializeData(updatedPhotos),
    });
  } catch (error) {
    console.error("Drive sync route error:", error);
    return NextResponse.json({ error: "Failed to sync Drive photos" }, { status: 500 });
  }
}
