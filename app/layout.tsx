import type { Metadata } from "next";
import { Cormorant_Garamond, Manrope } from "next/font/google";
import "./globals.css";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";

const cormorant = Cormorant_Garamond({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600"],
  variable: "--font-cormorant",
  display: "swap",
  preload: true,
});

const manrope = Manrope({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600"],
  variable: "--font-manrope",
  display: "swap",
  preload: true,
});

export async function generateMetadata(): Promise<Metadata> {
  let setting = null;
  try {
    setting = await db.websiteSetting.findFirst();
  } catch (e) {
    console.error("Error fetching website settings:", e);
  }

  const title = setting?.meta_title || "Paneventz | Luxury Wedding Photography & Films in India";
  const description =
    setting?.meta_description ||
    "Timeless luxury wedding photography and cinematic love stories documented across Mumbai, Udaipur, Goa, and worldwide destinations.";

  return {
    metadataBase: new URL("https://paneventz.in"),
    title: {
      default: title,
      template: "%s | Paneventz",
    },
    description,
    alternates: {
      canonical: "https://paneventz.in",
    },
    authors: [{ name: "Paneventz Studio", url: "https://paneventz.in" }],
    creator: "Paneventz",
    publisher: "Paneventz",
    formatDetection: {
      telephone: true,
      email: true,
      address: true,
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
          alt: "Paneventz Luxury Wedding Photography and Cinematic Films",
        },
      ],
    },
    twitter: {
      card: "summary_large_image",
      title,
      description,
      images: ["https://paneventz.in/images/1.jpg"],
    },
    robots: {
      index: true,
      follow: true,
      googleBot: {
        index: true,
        follow: true,
        "max-video-preview": -1,
        "max-image-preview": "large",
        "max-snippet": -1,
      },
    },
  };
}

import WhatsAppButton from "@/components/WhatsAppButton";

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  let setting: any = null;
  try {
    setting = await db.websiteSetting.findFirst().then(serializeData);
  } catch (e) {
    console.error("Error fetching settings for schema:", e);
  }

  const rawPhones = setting?.phone
    ? setting.phone.split(/[,/|]+/).map((p: string) => p.trim()).filter(Boolean)
    : ["+91 80820 24787", "+91 98213 37523"];

  const schemaJsonLd = {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebSite",
        "@id": "https://paneventz.in/#website",
        "url": "https://paneventz.in",
        "name": setting?.studio_name || "Paneventz",
        "description":
          setting?.meta_description ||
          "Timeless luxury wedding photography and cinematic love stories documented across Mumbai, Udaipur, Goa, and worldwide destinations.",
        "publisher": {
          "@id": "https://paneventz.in/#organization",
        },
        "inLanguage": "en-IN",
      },
      {
        "@type": "ProfessionalService",
        "@id": "https://paneventz.in/#organization",
        "name": setting?.studio_name || "Paneventz",
        "legalName": "Paneventz Luxury Wedding Photography & Films",
        "url": "https://paneventz.in",
        "logo": "https://paneventz.in/images/1.jpg",
        "image": "https://paneventz.in/images/hero.webp",
        "description":
          "Bespoke luxury wedding photography, 4K cinematic wedding films, and fine-art heirloom albums documented across Mumbai, Rajasthan, Goa, and worldwide destinations.",
        "telephone": rawPhones,
        "email": setting?.email || "imaliinmirza@gmail.com",
        "priceRange": "₹₹₹₹",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Mumbai",
          "addressRegion": "Maharashtra",
          "addressCountry": "IN",
        },
        "geo": {
          "@type": "GeoCoordinates",
          "latitude": "19.0760",
          "longitude": "72.8777",
        },
        "areaServed": [
          { "@type": "AdministrativeArea", "name": "India" },
          { "@type": "City", "name": "Mumbai" },
          { "@type": "City", "name": "Udaipur" },
          { "@type": "City", "name": "Goa" },
          { "@type": "City", "name": "Delhi" },
          { "@type": "City", "name": "Jaipur" },
          { "@type": "Country", "name": "Worldwide" },
        ],
        "openingHoursSpecification": [
          {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
              "Monday",
              "Tuesday",
              "Wednesday",
              "Thursday",
              "Friday",
              "Saturday",
              "Sunday",
            ],
            "opens": "09:00",
            "closes": "21:00",
          },
        ],
        "sameAs": [
          "https://www.paneventz.com",
        ],
      },
    ],
  };

  return (
    <html lang="en" className={`${cormorant.variable} ${manrope.variable}`}>
      <head>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(schemaJsonLd) }}
        />
      </head>
      <body className="bg-[#0c0c0d] text-[#d6d6d8] antialiased selection:bg-[#c4a472] selection:text-[#0c0c0d]">
        {children}
        <WhatsAppButton />
      </body>
    </html>
  );
}