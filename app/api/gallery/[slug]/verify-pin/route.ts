import { NextResponse } from "next/server";
import { db } from "@/lib/db";

export async function POST(
  req: Request,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;
    const { pin } = await req.json();

    const album = await db.weddingAlbum.findUnique({
      where: { slug },
    });

    if (!album) {
      return NextResponse.json({ error: "Gallery not found" }, { status: 404 });
    }

    if (album.pin_code && album.pin_code.trim() !== pin?.trim()) {
      return NextResponse.json({ error: "Invalid PIN code" }, { status: 401 });
    }

    return NextResponse.json({
      success: true,
      message: "Passcode verified successfully.",
    });
  } catch (error) {
    console.error("Verify PIN API Error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}