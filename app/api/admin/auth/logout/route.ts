import { NextResponse } from "next/server";
import { adminAccessCookieName } from "@/lib/admin-access";

export async function POST() {
  const response = NextResponse.json({ success: true });
  response.cookies.set({ name: adminAccessCookieName(), value: "", maxAge: 0, path: "/" });
  return response;
}
