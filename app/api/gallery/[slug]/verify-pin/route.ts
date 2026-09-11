import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { galleryAccessCookieName, galleryAccessToken } from "@/lib/gallery-access";

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

    const response = NextResponse.json({
      success: true,
      message: "Passcode verified successfully.",
    });

    if (album.pin_code) {
      response.cookies.set({
        name: galleryAccessCookieName(slug),
        value: galleryAccessToken(slug, album.pin_code),
        httpOnly: true,
        sameSite: "lax",
        secure: process.env.NODE_ENV === "production",
        maxAge: 60 * 60 * 8,
        // The same signed session is used by the gallery media and download APIs.
        path: "/",
      });
    }

    return response;
  } catch (error) {
    console.error("Verify PIN API Error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
