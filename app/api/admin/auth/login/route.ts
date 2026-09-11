import { NextResponse } from "next/server";
import {
  adminAccessCookieName,
  adminAccessToken,
  hasValidAdminPassword,
} from "@/lib/admin-access";

export async function POST(request: Request) {
  try {
    const { password } = await request.json();

    if (!hasValidAdminPassword(password)) {
      return NextResponse.json({ error: "Invalid admin password." }, { status: 401 });
    }

    const response = NextResponse.json({ success: true });
    response.cookies.set({
      name: adminAccessCookieName(),
      value: await adminAccessToken(),
      httpOnly: true,
      sameSite: "lax",
      secure: process.env.NODE_ENV === "production",
      maxAge: 60 * 60 * 12,
      path: "/",
    });
    return response;
  } catch (error) {
    console.error("Admin login error:", error);
    return NextResponse.json({ error: "Unable to sign in." }, { status: 500 });
  }
}
