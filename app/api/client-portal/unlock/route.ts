import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { galleryAccessCookieName, galleryAccessToken } from "@/lib/gallery-access";

export async function POST(req: Request) {
  try {
    const { pin } = await req.json();
    if (!pin) {
      return NextResponse.json({ error: "PIN is required" }, { status: 422 });
    }

    const album = await db.weddingAlbum.findFirst({
      where: { pin_code: pin },
    });

    if (!album) {
      return NextResponse.json({ error: "Invalid PIN code" }, { status: 401 });
    }

    const response = NextResponse.json({
      success: true,
      // Do not return the Drive folder ID or the private PIN to the browser.
      album: {
        slug: album.slug,
        title: album.title,
        couple_names: album.couple_names,
        location: album.location,
      },
    });

    response.cookies.set({
      name: galleryAccessCookieName(album.slug),
      value: galleryAccessToken(album.slug, album.pin_code || pin),
      httpOnly: true,
      sameSite: "lax",
      secure: process.env.NODE_ENV === "production",
      maxAge: 60 * 60 * 8,
      path: "/",
    });

    return response;
  } catch (error) {
    console.error("Client Portal Unlock Error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}
