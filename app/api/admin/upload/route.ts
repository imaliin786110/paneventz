import { NextResponse } from "next/server";
import { writeFile, mkdir } from "fs/promises";
import path from "path";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

export async function POST(req: Request) {
  try {
    let buffer: Buffer | null = null;
    let fileName = "";
    let fileType = "image/jpeg";
    let fileSize = 0;

    const contentType = req.headers.get("content-type") || "";

    if (contentType.includes("application/json")) {
      const json = await req.json();
      const rawData = json.dataUrl || json.file || "";
      fileName = json.fileName || json.name || "upload.jpg";
      fileType = json.fileType || "image/jpeg";

      if (rawData.startsWith("data:")) {
        const commaIdx = rawData.indexOf(",");
        buffer = Buffer.from(rawData.slice(commaIdx + 1), "base64");
      } else {
        buffer = Buffer.from(rawData, "base64");
      }
      fileSize = buffer.length;
    } else {
      // Fallback for standard formData
      try {
        const formData = await req.formData();
        const file = formData.get("file") as File | null;
        if (!file) {
          return NextResponse.json({ error: "No file provided" }, { status: 400 });
        }
        const bytes = await file.arrayBuffer();
        buffer = Buffer.from(bytes);
        fileName = file.name;
        fileType = file.type || "image/jpeg";
        fileSize = file.size;
      } catch (err: any) {
        console.warn("FormData parse failed, checking raw stream:", err);
        return NextResponse.json(
          { error: `FormData error: ${err?.message || "Invalid multipart body"}` },
          { status: 400 }
        );
      }
    }

    if (!buffer || buffer.length === 0) {
      return NextResponse.json({ error: "No file data received" }, { status: 400 });
    }

    // Sanitize filename and create unique timestamped name
    const timestamp = Date.now();
    const ext = path.extname(fileName) || ".jpg";
    const cleanBaseName = path
      .basename(fileName, ext)
      .replace(/[^a-zA-Z0-9_-]/g, "_")
      .slice(0, 30);
    const finalFileName = `${cleanBaseName || "upload"}_${timestamp}${ext}`;

    // Candidate directories to try in order of preference
    const candidateDirs = [
      { dir: path.join(process.cwd(), "public", "storage", "uploads"), publicPrefix: "/storage/uploads" },
      { dir: path.join(process.cwd(), "public", "storage", "stories"), publicPrefix: "/storage/stories" },
      { dir: path.join(process.cwd(), "public", "images"), publicPrefix: "/images" },
      { dir: path.join(process.cwd(), "public", "uploads"), publicPrefix: "/uploads" },
    ];

    let savedUrl: string | null = null;

    for (const candidate of candidateDirs) {
      try {
        await mkdir(candidate.dir, { recursive: true });
        const filePath = path.join(candidate.dir, finalFileName);
        await writeFile(filePath, buffer);
        savedUrl = `${candidate.publicPrefix}/${finalFileName}`;
        break;
      } catch (err) {
        console.warn(`Could not save to ${candidate.dir}:`, err);
      }
    }

    if (savedUrl) {
      return NextResponse.json({
        success: true,
        url: savedUrl,
        fileName: finalFileName,
        size: fileSize,
        type: fileType,
      });
    }

    // Fallback: If writing to disk is blocked on serverless, return base64 Data URL
    const dataUrl = `data:${fileType};base64,${buffer.toString("base64")}`;
    return NextResponse.json({
      success: true,
      url: dataUrl,
      fileName: finalFileName,
      size: fileSize,
      type: fileType,
      fallback: true,
    });
  } catch (error: any) {
    console.error("Upload error:", error);
    return NextResponse.json(
      { error: `File upload failed: ${error?.message || "Internal server error"}` },
      { status: 500 }
    );
  }
}
