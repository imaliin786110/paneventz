import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";

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

    return NextResponse.json({
      album: serializeData(album),
      photos: serializeData(album.photos),
    });
  } catch (error) {
    console.error("Gallery Photos API Error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}