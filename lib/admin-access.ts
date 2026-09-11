const encoder = new TextEncoder();
const ADMIN_COOKIE_NAME = "paneventz_admin";

function sessionSecret() {
  const value =
    process.env.ADMIN_SESSION_SECRET ||
    process.env.GALLERY_SESSION_SECRET ||
    process.env.NEXTAUTH_SECRET ||
    process.env.APP_KEY;

  if (!value) {
    throw new Error("Set ADMIN_SESSION_SECRET before enabling the admin console.");
  }

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

export function hasValidAdminPassword(password: unknown) {
  const configuredPassword = process.env.ADMIN_PASSWORD;
  return Boolean(configuredPassword && typeof password === "string" && password === configuredPassword);
}
