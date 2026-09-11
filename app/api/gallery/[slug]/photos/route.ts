import { NextResponse } from "next/server";
import { cookies } from "next/headers";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
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
      pin_code: Boolean(album.pin_code),
      enable_face_ai: album.enable_face_ai,
      allow_downloads: album.allow_downloads,
    };

    return NextResponse.json({
      album: serializeData(safeAlbum),
      photos: isUnlocked ? serializeData(album.photos) : [],
      isUnlocked,
    });
  } catch (error) {
    console.error("Gallery Photos API Error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
