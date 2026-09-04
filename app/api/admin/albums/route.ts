import { NextRequest, NextResponse } from "next/server";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";

// GET: List all albums
export async function GET() {
  try {
    const albums = await db.weddingAlbum.findMany({
      include: {
        photos: true,
        _count: { select: { photos: true } },
      },
      orderBy: { created_at: "desc" },
    }).then(serializeData);

    return NextResponse.json({ albums });
  } catch (error) {
    console.error("GET /api/admin/albums error:", error);
    return NextResponse.json({ error: "Failed to fetch albums" }, { status: 500 });
  }
}

// POST: Create or Update an album
export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const {
      id,
      title,
      slug,
      couple_names,
      location,
      pin_code,
      google_drive_folder_id,
      guest_google_drive_folder_id,
      enable_face_ai,
      allow_downloads,
      is_public,
      cover_image,
    } = body;

    if (!title) {
      return NextResponse.json({ error: "Album title is required" }, { status: 400 });
    }

    const albumSlug = (slug || title)
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, "-")
      .replace(/(^-|-$)+/g, "");

    if (id) {
      // Update existing
      const updated = await db.weddingAlbum.update({
        where: { id: BigInt(id) },
        data: {
          title,
          slug: albumSlug,
          couple_names: couple_names || null,
          location: location || null,
          pin_code: pin_code || null,
          google_drive_folder_id: google_drive_folder_id || null,
          guest_google_drive_folder_id: guest_google_drive_folder_id || null,
          enable_face_ai: Boolean(enable_face_ai),
          allow_downloads: allow_downloads !== false,
          is_public: Boolean(is_public),
          cover_image: cover_image || null,
          updated_at: new Date(),
        },
      }).then(serializeData);

      return NextResponse.json({ success: true, album: updated });
    } else {
      // Create new
      const created = await db.weddingAlbum.create({
        data: {
          title,
          slug: albumSlug,
          couple_names: couple_names || null,
          location: location || null,
          pin_code: pin_code || null,
          google_drive_folder_id: google_drive_folder_id || null,
          guest_google_drive_folder_id: guest_google_drive_folder_id || null,
          enable_face_ai: Boolean(enable_face_ai),
          allow_downloads: allow_downloads !== false,
          is_public: Boolean(is_public),
          cover_image: cover_image || "/images/1.jpg",
          created_at: new Date(),
          updated_at: new Date(),
        },
      }).then(serializeData);

      return NextResponse.json({ success: true, album: created });
    }
  } catch (error) {
    console.error("POST /api/admin/albums error:", error);
    return NextResponse.json({ error: "Failed to save album" }, { status: 500 });
  }
}
