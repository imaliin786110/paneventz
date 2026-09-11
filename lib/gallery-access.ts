import { createHmac, timingSafeEqual } from "crypto";

const cookieName = (slug: string) => `paneventz_gallery_${slug}`;

function secret(): string {
  const value =
    process.env.GALLERY_SESSION_SECRET ||
    process.env.NEXTAUTH_SECRET ||
    process.env.APP_KEY;

  if (!value) {
    throw new Error("GALLERY_SESSION_SECRET or NEXTAUTH_SECRET must be configured.");
  }

  return value;
}

export function galleryAccessToken(slug: string, pin: string): string {
  return createHmac("sha256", secret()).update(`${slug}:${pin}`).digest("hex");
}

export function galleryAccessCookieName(slug: string): string {
  return cookieName(slug);
}

export function hasGalleryAccess(slug: string, pin: string | null, token?: string): boolean {
  if (!pin) return true;
  if (!token) return false;

  const expected = galleryAccessToken(slug, pin);
  const actual = Buffer.from(token);
  const expectedBuffer = Buffer.from(expected);

  return actual.length === expectedBuffer.length && timingSafeEqual(actual, expectedBuffer);
}
