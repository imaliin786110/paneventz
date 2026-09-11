import { NextRequest, NextResponse } from "next/server";
import { adminAccessCookieName, hasAdminAccess } from "@/lib/admin-access";

export async function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl;
  const isAdminPage = pathname.startsWith("/admin");
  const isAdminApi = pathname.startsWith("/api/admin");

  if (!isAdminPage && !isAdminApi) return NextResponse.next();
  if (pathname.startsWith("/admin/login") || pathname.startsWith("/api/admin/auth/")) {
    return NextResponse.next();
  }

  const hasAccess = await hasAdminAccess(request.cookies.get(adminAccessCookieName())?.value);
  if (hasAccess) return NextResponse.next();

  if (isAdminApi) {
    return NextResponse.json({ error: "Admin authentication required." }, { status: 401 });
  }

  const loginUrl = new URL("/admin/login", request.url);
  loginUrl.searchParams.set("next", pathname);
  return NextResponse.redirect(loginUrl);
}

export const config = {
  matcher: ["/admin/:path*", "/api/admin/:path*"],
};
