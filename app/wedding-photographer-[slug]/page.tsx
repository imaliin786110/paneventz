import React from "react";
import { notFound } from "next/navigation";
import type { Metadata } from "next";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import EnquiryForm from "@/components/EnquiryForm";
import Link from "next/link";
import { MapPin, Sparkles } from "lucide-react";

export const revalidate = 60;

export async function generateStaticParams() {
  try {
    const locations = await db.location.findMany({
      where: { is_published: true },
      select: { slug: true },
    });
    return locations.map((loc) => ({ slug: loc.slug }));
  } catch {
    return [];
  }
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  if (!slug) return {};

  const loc = await db.location.findFirst({ where: { slug } });
  if (!loc) return {};

  const title = loc.headline || `Luxury Wedding Photographer in ${loc.name} | Paneventz`;
  const description =
    loc.intro ||
    `Award-winning luxury wedding photography and cinematic films in ${loc.name}, capturing timeless love stories.`;
  const canonicalUrl = `https://paneventz.in/wedding-photographer-${slug}`;
  const imageUrl = loc.hero_image || "https://paneventz.in/images/hero.webp";

  return {
    title,
    description,
    alternates: {
      canonical: canonicalUrl,
    },
    openGraph: {
      title,
      description,
      url: canonicalUrl,
      images: [
        {
          url: imageUrl,
          width: 1200,
          height: 630,
          alt: title,
        },
      ],
    },
    twitter: {
      card: "summary_large_image",
      title,
      description,
      images: [imageUrl],
    },
  };
}

export default async function LocationPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  if (!slug) notFound();

  const [setting, loc] = await Promise.all([
    db.websiteSetting.findFirst().then(serializeData),
    db.location.findFirst({ where: { slug } }).then(serializeData),
  ]);

  if (!loc) notFound();

  const venues = Array.isArray(loc.popular_venues) ? loc.popular_venues : [];
  const faqs = Array.isArray(loc.faqs) ? loc.faqs : [];

  const locationServiceSchema = {
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": `Paneventz Luxury Wedding Photography - ${loc.name}`,
    "url": `https://paneventz.in/wedding-photographer-${slug}`,
    "image": loc.hero_image || "https://paneventz.in/images/hero.webp",
    "description": loc.intro || `Luxury wedding photography & cinematic films in ${loc.name}`,
    "areaServed": {
      "@type": "City",
      "name": loc.name,
    },
    "provider": {
      "@type": "Organization",
      "name": "Paneventz",
      "url": "https://paneventz.in",
    },
  };

  const breadcrumbsSchema = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
      {
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "https://paneventz.in",
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": `${loc.name} Wedding Photography`,
        "item": `https://paneventz.in/wedding-photographer-${slug}`,
      },
    ],
  };

  const locationFaqSchema = faqs.length > 0 ? {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": faqs.map((f: any) => ({
      "@type": "Question",
      "name": f.question,
      "acceptedAnswer": {
        "@type": "Answer",
        "text": f.answer,
      },
    })),
  } : null;

  return (
    <main className="min-h-screen bg-[#0c0c0d] text-[#d6d6d8]">
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(locationServiceSchema) }}
      />
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbsSchema) }}
      />
      {locationFaqSchema && (
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(locationFaqSchema) }}
        />
      )}
      <Navbar studioName={setting?.studio_name || "Paneventz"} />

      {/* Hero Section */}
      <section className="relative min-h-[70vh] flex items-center justify-center text-center px-6 pt-32 pb-20 overflow-hidden">
        <div
          className="absolute inset-0 z-0 bg-cover bg-center"
          style={{
            backgroundImage: `linear-gradient(rgba(12,12,13,0.6), rgba(12,12,13,0.9)), url('${
              loc.hero_image || "https://paneventz.in/images/hero.webp"
            }')`,
          }}
        />

        <div className="relative z-10 max-w-4xl mx-auto">
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-4 inline-flex items-center gap-1.5 font-light">
            <MapPin size={14} /> {loc.name}, {loc.state || "India"}
          </span>
          <h1 className="font-serif text-4xl sm:text-6xl lg:text-7xl text-[#f5f5f7] font-light leading-tight mb-6">
            {loc.headline || `Luxury Wedding Photography & Films in ${loc.name}`}
          </h1>
          <p className="text-sm sm:text-base text-[#d6d6d8]/90 font-light leading-relaxed max-w-2xl mx-auto mb-8">
            {loc.intro ||
              `Documenting unforgettable royal weddings and destination love stories across ${loc.name}.`}
          </p>
          <Link
            href="#enquire"
            className="inline-block px-8 py-4 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b45309] text-[#0c0c0d] font-bold shadow-xl"
          >
            Inquire {loc.name} Dates
          </Link>
        </div>
      </section>

      {/* Editorial Content */}
      {loc.content && (
        <section className="py-20 px-6 lg:px-12 max-w-4xl mx-auto">
          <div
            className="prose prose-invert prose-amber max-w-none text-base font-light leading-relaxed space-y-6 text-[#d6d6d8]/90"
            dangerouslySetInnerHTML={{ __html: loc.content }}
          />
        </section>
      )}

      {/* Popular Venues */}
      {venues.length > 0 && (
        <section className="py-20 px-6 lg:px-12 bg-[#09090b] border-y border-white/5">
          <div className="max-w-6xl mx-auto">
            <div className="text-center mb-16">
              <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
                ICONIC LOCATIONS
              </span>
              <h2 className="font-serif text-3xl sm:text-5xl text-[#f5f5f7] font-light">
                Popular Wedding Venues in {loc.name}
              </h2>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
              {venues.map((venue: any, idx: number) => (
                <div
                  key={idx}
                  className="bg-[#121214] border border-white/5 rounded-2xl p-6 flex items-start gap-3"
                >
                  <Sparkles size={18} className="text-[#c4a472] shrink-0 mt-1" />
                  <div>
                    <h3 className="font-serif text-xl text-[#f5f5f7] font-normal mb-1">
                      {typeof venue === "string" ? venue : venue.name}
                    </h3>
                    {venue.description && (
                      <p className="text-xs text-[#a1a1aa] font-light">{venue.description}</p>
                    )}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Localized FAQs */}
      {faqs.length > 0 && (
        <section className="py-20 px-6 lg:px-12 bg-[#0c0c0d]">
          <div className="max-w-4xl mx-auto">
            <div className="text-center mb-12">
              <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
                DESTINATION PLANNING
              </span>
              <h2 className="font-serif text-3xl sm:text-4xl text-[#f5f5f7] font-light">
                Frequently Asked Questions about Weddings in {loc.name}
              </h2>
            </div>
            <div className="space-y-4">
              {faqs.map((faq: any, idx: number) => (
                <div key={idx} className="bg-[#121214] border border-white/5 rounded-2xl p-6">
                  <h3 className="font-serif text-xl text-[#f5f5f7] font-light mb-2">
                    {faq.question}
                  </h3>
                  <p className="text-xs text-[#a1a1aa] font-light leading-relaxed">{faq.answer}</p>
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      <EnquiryForm />
      <Footer setting={setting} />
    </main>
  );
}