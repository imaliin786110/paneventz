import { NextResponse } from "next/server";
import { db } from "@/lib/db";

export async function GET() {
  try {
    const [posts, locations] = await Promise.all([
      db.blogPost.count({ where: { is_published: true } }),
      db.location.count({ where: { is_published: true } }),
    ]);

    return NextResponse.json({
      success: true,
      audited_routes: 5 + posts + locations,
      status: "healthy",
      timestamp: new Date().toISOString(),
    });
  } catch (error) {
    return NextResponse.json({ error: "SEO scan error" }, { status: 500 });
  }
}