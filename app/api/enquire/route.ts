import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import nodemailer from "nodemailer";

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
    const adminEmail = setting?.email || "imaliinmirza@gmail.com";

    // Asynchronous SMTP Notification (non-blocking)
    try {
      const transporter = nodemailer.createTransport({
        host: process.env.MAIL_HOST || "smtp.gmail.com",
        port: parseInt(process.env.MAIL_PORT || "587"),
        secure: false,
        auth: {
          user: process.env.MAIL_USERNAME || "imaliinmirza@gmail.com",
          pass: process.env.MAIL_PASSWORD || "",
        },
      });

      if (process.env.MAIL_PASSWORD) {
        await transporter.sendMail({
          from: `"Paneventz Inquiries" <${process.env.MAIL_FROM_ADDRESS || "no-reply@paneventz.in"}>`,
          to: adminEmail,
          subject: `✨ New Wedding Inquiry: ${name} (${wedding_location})`,
          html: `
            <div style="font-family: Arial, sans-serif; background: #0c0c0d; color: #fff; padding: 30px; border-radius: 12px; max-width: 600px;">
              <h2 style="color: #c4a472; margin-top: 0;">New Luxury Wedding Commission Inquiry</h2>
              <p><strong>Couple:</strong> ${name}</p>
              <p><strong>Phone / WhatsApp:</strong> ${phone}</p>
              <p><strong>Email:</strong> ${email}</p>
              <p><strong>Wedding Date:</strong> ${wedding_date || "To be decided"}</p>
              <p><strong>Location / Venue:</strong> ${wedding_location}</p>
              <p><strong>Service Requested:</strong> ${service || "Full Photography & Cinema"}</p>
              <div style="background: #18181b; padding: 15px; border-radius: 8px; border-left: 4px solid #c4a472; margin-top: 20px;">
                <strong>Client Vision Notes:</strong><br/>
                ${message || "No additional notes provided."}
              </div>
            </div>
          `,
        });
      }
    } catch (mailErr) {
      console.warn("Mail dispatch skipped/failed:", mailErr);
    }

    const waText = encodeURIComponent(
      `*New Wedding Inquiry via Paneventz*\n\n` +
      `*Couple:* ${name}\n` +
      `*Location:* ${wedding_location}\n` +
      `*Date:* ${wedding_date || "To be decided"}\n` +
      `*Phone:* ${phone}\n` +
      `*Email:* ${email}\n` +
      `*Service:* ${service || "Full Photography & Cinema"}\n` +
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