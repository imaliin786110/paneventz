import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { faceAccessCookieName, faceAccessToken } from "@/lib/gallery-access";

export async function POST(
  _req: Request,
  { params }: { params: Promise<{ slug: string }> },
) {
  try {
    const { slug } = await params;
    const album = await db.weddingAlbum.findUnique({
      where: { slug },
      select: { enable_face_ai: true },
    });
    if (!album) return NextResponse.json({ error: "Gallery not found" }, { status: 404 });
    if (!album.enable_face_ai) return NextResponse.json({ error: "Face matching is not enabled" }, { status: 403 });

    const response = NextResponse.json({ success: true });
    response.cookies.set({
      name: faceAccessCookieName(slug),
      value: faceAccessToken(slug),
      httpOnly: true,
      sameSite: "lax",
      secure: process.env.NODE_ENV === "production",
      maxAge: 60 * 60,
      path: "/",
    });
    return response;
  } catch (error) {
    console.error("Face access API error:", error);
    return NextResponse.json({ error: "Could not authorize face match" }, { status: 500 });
  }
}
