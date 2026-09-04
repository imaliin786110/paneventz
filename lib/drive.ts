/**
 * Google Drive Helper Utility
 * Extracts file/folder IDs from various Google Drive URL formats
 * and constructs direct streaming/download URLs.
 */

export function extractDriveId(urlOrId: string | null | undefined): { id: string; type: "file" | "folder" } | null {
  if (!urlOrId) return null;
  const str = urlOrId.trim();

  // Pattern 1: /folders/ID
  const folderMatch = str.match(/\/folders\/([a-zA-Z0-9_-]+)/);
  if (folderMatch && folderMatch[1]) {
    return { id: folderMatch[1], type: "folder" };
  }

  // Pattern 2: /file/d/ID or /d/ID
  const fileMatch = str.match(/\/d\/([a-zA-Z0-9_-]+)/);
  if (fileMatch && fileMatch[1]) {
    return { id: fileMatch[1], type: "file" };
  }

  // Pattern 3: id=ID query param
  const queryMatch = str.match(/[?&]id=([a-zA-Z0-9_-]+)/);
  if (queryMatch && queryMatch[1]) {
    return { id: queryMatch[1], type: "file" };
  }

  // Pattern 4: Raw ID string (alphanumeric, hyphens, underscores, length >= 15)
  if (/^[a-zA-Z0-9_-]{15,}$/.test(str)) {
    return { id: str, type: "file" };
  }

  return { id: str, type: "file" };
}

export function getDirectDriveDownloadUrl(driveId: string): string {
  return `https://drive.google.com/uc?export=download&id=${driveId}`;
}
