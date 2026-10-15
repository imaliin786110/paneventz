import { createHmac, timingSafeEqual } from "crypto";

const cookieName = (slug: string) => `paneventz_gallery_${slug}`;
const faceCookieName = (slug: string) => `paneventz_face_${slug}`;

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

/** Short-lived browser proof created after the guest's local face match. */
export function faceAccessCookieName(slug: string): string {
  return faceCookieName(slug);
}

export function faceAccessToken(slug: string): string {
  return createHmac("sha256", secret()).update(`${slug}:face-match`).digest("hex");
}

export function hasFaceAccess(slug: string, token?: string): boolean {
  if (!token) return false;
  const expected = Buffer.from(faceAccessToken(slug));
  const actual = Buffer.from(token);
  return actual.length === expected.length && timingSafeEqual(actual, expected);
}

export function hasGalleryAccess(slug: string, pin: string | null, token?: string): boolean {
  if (!pin) return true;
  if (!token) return false;

  const expected = galleryAccessToken(slug, pin);
  const actual = Buffer.from(token);
  const expectedBuffer = Buffer.from(expected);

  return actual.length === expectedBuffer.length && timingSafeEqual(actual, expectedBuffer);
}
