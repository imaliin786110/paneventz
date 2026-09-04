import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";

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

    return NextResponse.json({
      success: true,
      album: serializeData(album),
    });
  } catch (error) {
    console.error("Client Portal Unlock Error:", error);
    return NextResponse.json({ error: "Internal server error" }, { status: 500 });
  }
}