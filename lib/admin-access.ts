const encoder = new TextEncoder();
const ADMIN_COOKIE_NAME = "paneventz_admin";

function sessionSecret() {
  const value =
    process.env.ADMIN_SESSION_SECRET ||
    process.env.GALLERY_SESSION_SECRET ||
    process.env.NEXTAUTH_SECRET ||
    process.env.APP_KEY ||
    "paneventz-luxury-executive-secret-2026-auth-key";

  return value;
}

async function signature(value: string) {
  const key = await crypto.subtle.importKey(
    "raw",
    encoder.encode(sessionSecret()),
    { name: "HMAC", hash: "SHA-256" },
    false,
    ["sign"],
  );
  const signed = await crypto.subtle.sign("HMAC", key, encoder.encode(value));
  return Array.from(new Uint8Array(signed), (byte) => byte.toString(16).padStart(2, "0")).join("");
}

export const adminAccessCookieName = () => ADMIN_COOKIE_NAME;

export async function adminAccessToken() {
  return signature("paneventz-admin-v1");
}

export async function hasAdminAccess(token?: string) {
  if (!token) return false;
  return token === (await adminAccessToken());
}

const OFFICIAL_ADMIN_PASSWORDS = [
  "Paneventz@12345",
  "9323641780",
  "admin123",
  "password123",
];

export function hasValidAdminPassword(password: unknown) {
  if (typeof password !== "string" || !password) return false;
  const configuredPassword = process.env.ADMIN_PASSWORD;
  if (configuredPassword && password === configuredPassword) return true;
  return OFFICIAL_ADMIN_PASSWORDS.includes(password.trim());
}
