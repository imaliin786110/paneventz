import type { Metadata } from "next";
import { Cormorant_Garamond, Manrope } from "next/font/google";
import "./globals.css";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";

const cormorant = Cormorant_Garamond({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600"],
  variable: "--font-cormorant",
});

const manrope = Manrope({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600"],
  variable: "--font-manrope",
});

export async function generateMetadata(): Promise<Metadata> {
  let setting = null;
  try {
    setting = await db.websiteSetting.findFirst();
  } catch (e) {
    console.error("Error fetching website settings:", e);
  }

  const title = setting?.meta_title || "Paneventz | Luxury Wedding Photography & Cinematic Films";
  const description =
    setting?.meta_description ||
    "Timeless luxury wedding photography and cinematic love stories documented across Mumbai, Udaipur, Goa, and worldwide destinations.";

  return {
    title,
    description,
    metadataBase: new URL("https://paneventz.in"),
    alternates: {
      canonical: "/",
    },
    openGraph: {
      title,
      description,
      url: "https://paneventz.in",
      siteName: setting?.studio_name || "Paneventz",
      locale: "en_IN",
      type: "website",
      images: [
        {
          url: "https://paneventz.in/images/1.jpg",
          width: 1200,
          height: 630,
          alt: "Paneventz Luxury Wedding Photography",
        },
      ],
    },
    twitter: {
      card: "summary_large_image",
      title,
      description,
      images: ["https://paneventz.in/images/1.jpg"],
    },
  };
}

import WhatsAppButton from "@/components/WhatsAppButton";

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className={`${cormorant.variable} ${manrope.variable}`}>
      <body className="bg-[#0c0c0d] text-[#d6d6d8] antialiased selection:bg-[#c4a472] selection:text-[#0c0c0d]">
        {children}
        <WhatsAppButton />
      </body>
    </html>
  );
}