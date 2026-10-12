import { NextResponse } from "next/server";
import { writeFile, mkdir } from "fs/promises";
import path from "path";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

export async function POST(req: Request) {
  try {
    const formData = await req.formData();
    const file = formData.get("file") as File | null;

    if (!file) {
      return NextResponse.json({ error: "No file provided" }, { status: 400 });
    }

    const bytes = await file.arrayBuffer();
    const buffer = Buffer.from(bytes);

    // Sanitize filename and create unique timestamped name
    const timestamp = Date.now();
    const ext = path.extname(file.name) || ".jpg";
    const cleanBaseName = path
      .basename(file.name, ext)
      .replace(/[^a-zA-Z0-9_-]/g, "_")
      .slice(0, 30);
    const fileName = `${cleanBaseName || "upload"}_${timestamp}${ext}`;

    // Candidate directories to try in order of preference
    const candidateDirs = [
      { dir: path.join(process.cwd(), "public", "storage", "uploads"), publicPrefix: "/storage/uploads" },
      { dir: path.join(process.cwd(), "public", "storage", "stories"), publicPrefix: "/storage/stories" },
      { dir: path.join(process.cwd(), "public", "images"), publicPrefix: "/images" },
      { dir: path.join(process.cwd(), "public", "uploads"), publicPrefix: "/uploads" },
    ];

    let savedUrl: string | null = null;
    let lastError: any = null;

    for (const candidate of candidateDirs) {
      try {
        await mkdir(candidate.dir, { recursive: true });
        const filePath = path.join(candidate.dir, fileName);
        await writeFile(filePath, buffer);
        savedUrl = `${candidate.publicPrefix}/${fileName}`;
        break;
      } catch (err) {
        lastError = err;
        console.warn(`Could not save to ${candidate.dir}:`, err);
      }
    }

    if (savedUrl) {
      return NextResponse.json({
        success: true,
        url: savedUrl,
        fileName,
        size: file.size,
        type: file.type,
      });
    }

    // Fallback: If writing to disk is blocked (e.g. read-only filesystem or permissions), return base64 Data URL
    if (file.type.startsWith("image/") && buffer.length < 10 * 1024 * 1024) {
      const mime = file.type || "image/jpeg";
      const base64 = buffer.toString("base64");
      const dataUrl = `data:${mime};base64,${base64}`;
      return NextResponse.json({
        success: true,
        url: dataUrl,
        fileName,
        size: file.size,
        type: file.type,
        fallback: true,
      });
    }

    console.error("All upload targets failed:", lastError);
    return NextResponse.json(
      { error: `File upload failed: ${lastError?.message || "Storage directory not writable"}` },
      { status: 500 }
    );
  } catch (error: any) {
    console.error("Upload error:", error);
    return NextResponse.json(
      { error: `File upload failed: ${error?.message || "Internal server error"}` },
      { status: 500 }
    );
  }
}
