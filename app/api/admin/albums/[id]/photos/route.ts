import { NextRequest, NextResponse } from "next/server";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import { refreshGallery } from "@/lib/content-revalidation";

export const dynamic = "force-dynamic";
export const revalidate = 0;

const noStore = { "Cache-Control": "no-store, max-age=0" };

const asDescriptors = (value: unknown) => {
  if (!Array.isArray(value)) return [];
  return value.filter((descriptor): descriptor is number[] => Array.isArray(descriptor) && descriptor.length === 128 && descriptor.every((item) => typeof item === "number" && Number.isFinite(item))).map((descriptor) => descriptor.map(Number));
};

export async function GET(_request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params;
    const album = await db.weddingAlbum.findUnique({ where: { id: BigInt(id) }, include: { photos: { orderBy: { created_at: "desc" } } } });
    if (!album) return NextResponse.json({ error: "Album not found" }, { status: 404 });
    return NextResponse.json({ album: serializeData(album) }, { headers: noStore });
  } catch {
    return NextResponse.json({ error: "Unable to load album media" }, { status: 400 });
  }
}

export async function POST(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params;
    const body = await request.json();
    if (typeof body.photo_url !== "string" || !body.photo_url.trim()) return NextResponse.json({ error: "A media URL is required" }, { status: 400 });
    const album = await db.weddingAlbum.findUnique({ where: { id: BigInt(id) }, select: { id: true, slug: true } });
    if (!album) return NextResponse.json({ error: "Album not found" }, { status: 404 });
    const descriptors = asDescriptors(body.face_descriptors);
    const photo = await db.albumPhoto.create({ data: { wedding_album_id: album.id, photo_url: body.photo_url.trim(), thumbnail_url: typeof body.thumbnail_url === "string" ? body.thumbnail_url : null, file_name: typeof body.file_name === "string" ? body.file_name : null, is_video: Boolean(body.is_video), file_size: typeof body.file_size === "string" ? body.file_size : null, face_descriptors: descriptors, faces_count: descriptors.length, created_at: new Date(), updated_at: new Date() } });
    refreshGallery(album.slug);
    return NextResponse.json({ success: true, photo: serializeData(photo) }, { headers: noStore });
  } catch (error) {
    console.error("Album media create error:", error);
    return NextResponse.json({ error: "Unable to save media" }, { status: 500 });
  }
}

export async function DELETE(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params;
    const photoId = new URL(request.url).searchParams.get("photoId");
    if (!photoId) return NextResponse.json({ error: "Photo ID is required" }, { status: 400 });
    const album = await db.weddingAlbum.findUnique({ where: { id: BigInt(id) }, select: { slug: true } });
    await db.albumPhoto.deleteMany({ where: { id: BigInt(photoId), wedding_album_id: BigInt(id) } });
    refreshGallery(album?.slug);
    return NextResponse.json({ success: true }, { headers: noStore });
  } catch {
    return NextResponse.json({ error: "Unable to remove media" }, { status: 400 });
  }
}
