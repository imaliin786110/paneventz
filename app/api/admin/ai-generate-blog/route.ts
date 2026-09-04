import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { GoogleGenerativeAI } from "@google/generative-ai";

export async function POST(req: Request) {
  try {
    const { topic, location, keywords } = await req.json();
    const slug = topic
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, "-")
      .replace(/(^-|-$)/g, "");

    const apiKey = process.env.GEMINI_API_KEY || "";
    let content = "";

    if (apiKey) {
      try {
        const genAI = new GoogleGenerativeAI(apiKey);
        const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });
        const result = await model.generateContent(
          `Write a luxury, high-converting 1,200 word wedding editorial article for Paneventz Studio on: "${topic}". Destination focus: "${location}". Focus SEO Keywords: "${keywords}". Include rich subheadings, photography advice, and royal heritage aesthetics.`
        );
        content = result.response.text();
      } catch (aiErr) {
        console.warn("Gemini generation fallback:", aiErr);
      }
    }

    if (!content) {
      content = `
<h2>A Royal Affair: Crafting Memories in ${location}</h2>
<p>Destination weddings across ${location} are defined by timeless romance, majestic architecture, and vivid cultural heritage. At <strong>Paneventz</strong>, our photographic philosophy centers on unscripted emotion paired with editorial cinematic mastery.</p>

<h3>1. The Magic of Royal Venues</h3>
<p>From sunset pheras overlooking ancient lakes to illuminated palace courtyards, every venue presents a distinct lighting canvas. Capturing natural skin tones while preserving authentic colors requires delicate highlight roll-offs and high dynamic range capture.</p>

<h3>2. Cinematic Storytelling</h3>
<p>Your wedding film is more than footage—it is an heirloom motion picture with original soundscapes and authentic vows preserved for generations.</p>
      `;
    }

    const post = await db.blogPost.upsert({
      where: { slug },
      update: {
        title: topic,
        category: "Destination Weddings",
        excerpt: `Discover the art of planning and documenting an unforgettable luxury wedding in ${location}.`,
        content,
        focus_keyword: keywords.split(",")[0]?.trim() || "luxury wedding",
        is_published: true,
        published_at: new Date(),
      },
      create: {
        title: topic,
        slug,
        category: "Destination Weddings",
        excerpt: `Discover the art of planning and documenting an unforgettable luxury wedding in ${location}.`,
        content,
        featured_image: "/images/1.jpg",
        focus_keyword: keywords.split(",")[0]?.trim() || "luxury wedding",
        is_published: true,
        published_at: new Date(),
      },
    });

    return NextResponse.json({
      success: true,
      article: {
        id: post.id.toString(),
        title: post.title,
        slug: post.slug,
        content: post.content,
      },
    });
  } catch (error) {
    console.error("AI Blog Generator Error:", error);
    return NextResponse.json({ error: "Generation failed" }, { status: 500 });
  }
}