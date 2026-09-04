import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";

export async function GET() {
  try {
    let setting = await db.websiteSetting.findFirst();
    if (!setting) {
      setting = await db.websiteSetting.create({
        data: {
          studio_name: "Paneventz",
          tagline: "Luxury Wedding Photography & Cinematography",
          hero_eyebrow: "Wedding Photography & Films",
          hero_heading: "Paneventz",
          hero_description: "We create timeless photographs and cinematic films for couples who want their wedding story to live far beyond the day itself.",
          hero_button_label: "Explore Our Stories",
          hero_button_url: "#stories",
          hero_background_image: "https://paneventz.in/images/hero.webp",
          stat_1_number: 250,
          stat_1_suffix: "+",
          stat_1_label: "Weddings Captured",
          stat_2_number: 10,
          stat_2_suffix: "+",
          stat_2_label: "Years of Craft",
          stat_3_number: 35,
          stat_3_suffix: "+",
          stat_3_label: "Destination Cities",
          stat_4_number: 100,
          stat_4_suffix: "%",
          stat_4_label: "Five-Star Experience",
          color_grade_heading: "Signature Cinematic Grade",
          color_grade_description: "Every frame is meticulously hand-colored to bring out timeless film warmth and emotional resonance.",
          color_grade_before_image: "/images/signature-color-grade.webp",
          color_grade_after_image: "/images/signature-color-grade.webp",
          email: "info@paneventz.in",
          phone: "+91 98765 43210",
          whatsapp: "+919876543210",
          instagram_url: "https://instagram.com/paneventz",
          youtube_url: "https://youtube.com/@paneventz",
          footer_address: "Mumbai · Udaipur · Delhi · Worldwide",
          footer_copyright: "© 2026 Paneventz Studio. All Rights Reserved.",
        },
      });
    }

    return NextResponse.json({ setting: serializeData(setting) });
  } catch (error) {
    console.error("GET /api/admin/settings error:", error);
    return NextResponse.json({ error: "Failed to fetch settings" }, { status: 500 });
  }
}

export async function POST(req: Request) {
  try {
    const body = await req.json();

    const existing = await db.websiteSetting.findFirst();

    let updated;
    if (existing) {
      updated = await db.websiteSetting.update({
        where: { id: existing.id },
        data: {
          studio_name: body.studio_name,
          tagline: body.tagline,
          hero_eyebrow: body.hero_eyebrow,
          hero_heading: body.hero_heading,
          hero_description: body.hero_description,
          hero_button_label: body.hero_button_label,
          hero_button_url: body.hero_button_url,
          hero_background_image: body.hero_background_image,
          hero_background_video: body.hero_background_video,
          stat_1_number: Number(body.stat_1_number) || 250,
          stat_1_suffix: body.stat_1_suffix,
          stat_1_label: body.stat_1_label,
          stat_2_number: Number(body.stat_2_number) || 10,
          stat_2_suffix: body.stat_2_suffix,
          stat_2_label: body.stat_2_label,
          stat_3_number: Number(body.stat_3_number) || 35,
          stat_3_suffix: body.stat_3_suffix,
          stat_3_label: body.stat_3_label,
          stat_4_number: Number(body.stat_4_number) || 100,
          stat_4_suffix: body.stat_4_suffix,
          stat_4_label: body.stat_4_label,
          color_grade_heading: body.color_grade_heading,
          color_grade_description: body.color_grade_description,
          color_grade_before_image: body.color_grade_before_image,
          color_grade_after_image: body.color_grade_after_image,
          email: body.email,
          phone: body.phone,
          whatsapp: body.whatsapp,
          instagram_url: body.instagram_url,
          facebook_url: body.facebook_url,
          youtube_url: body.youtube_url,
          footer_address: body.footer_address,
          footer_copyright: body.footer_copyright,
          google_drive_api_key: body.google_drive_api_key,
          updated_at: new Date(),
        },
      });
    } else {
      updated = await db.websiteSetting.create({
        data: {
          studio_name: body.studio_name || "Paneventz",
          ...body,
          created_at: new Date(),
          updated_at: new Date(),
        },
      });
    }

    return NextResponse.json({ success: true, setting: serializeData(updated) });
  } catch (error) {
    console.error("POST /api/admin/settings error:", error);
    return NextResponse.json({ error: "Failed to update settings" }, { status: 500 });
  }
}
