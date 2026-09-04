import { NextResponse } from "next/server";
import { db } from "@/lib/db";

export async function POST(req: Request) {
  try {
    const body = await req.json();
    const { name, email, phone, wedding_date, wedding_location, service, message } = body;

    if (!name || !email || !phone || !wedding_location) {
      return NextResponse.json(
        { error: "Please provide all required fields." },
        { status: 422 }
      );
    }

    const enquiry = await db.enquiry.create({
      data: {
        name,
        email,
        phone,
        wedding_date: wedding_date ? new Date(wedding_date) : null,
        wedding_location,
        service: service || "General Wedding Inquiry",
        message: message || "",
        status: "new",
      },
    });

    const setting = await db.websiteSetting.findFirst();
    const adminPhone = (setting?.whatsapp || "918082024787").replace(/[^0-9]/g, "");

    const waText = encodeURIComponent(
      `*New Wedding Inquiry via Paneventz*\n\n` +
      `*Couple:* ${name}\n` +
      `*Location:* ${wedding_location}\n` +
      `*Date:* ${wedding_date || "To be decided"}\n` +
      `*Phone:* ${phone}\n` +
      `*Email:* ${email}\n` +
      `*Notes:* ${message || "N/A"}`
    );

    const whatsappUrl = `https://wa.me/${adminPhone}?text=${waText}`;

    return NextResponse.json({
      success: true,
      message: "Enquiry submitted successfully.",
      enquiry_id: enquiry.id.toString(),
      whatsapp_url: whatsappUrl,
    });
  } catch (error) {
    console.error("Enquiry API Error:", error);
    return NextResponse.json(
      { error: "An unexpected error occurred. Please try again." },
      { status: 500 }
    );
  }
}