import { NextResponse } from "next/server";
import { db } from "@/lib/db";

export async function GET() {
  try {
    const now = new Date();
    const result = await db.blogPost.updateMany({
      where: {
        is_published: false,
        published_at: { lte: now },
      },
      data: {
        is_published: true,
      },
    });

    return NextResponse.json({
      success: true,
      published_count: result.count,
      timestamp: now.toISOString(),
    });
  } catch (error) {
    return NextResponse.json({ error: "Cron execution error" }, { status: 500 });
  }
}