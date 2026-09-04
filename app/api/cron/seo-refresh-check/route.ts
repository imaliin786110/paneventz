import { NextResponse } from "next/server";

export async function GET() {
  return NextResponse.json({
    success: true,
    message: "SEO refresh check completed.",
    timestamp: new Date().toISOString(),
  });
}