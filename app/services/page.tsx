import React from "react";
import { db } from "@/lib/db";
import { serializeData, formatIndianCurrency } from "@/lib/utils";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import Link from "next/link";
import { Check, ShieldCheck, Sparkles, Film, Camera } from "lucide-react";

import type { Metadata } from "next";

export const revalidate = 60;

export async function generateMetadata(): Promise<Metadata> {
  return {
    title: "Wedding Photography Packages & Pricing | Paneventz",
    description:
      "Explore bespoke wedding photography and 4K cinematography collections, pricing, and handcrafted deliverables by Paneventz.",
    alternates: {
      canonical: "https://paneventz.in/services",
    },
    openGraph: {
      title: "Wedding Photography Packages & Pricing | Paneventz",
      description:
        "Explore bespoke wedding photography and 4K cinematography collections, pricing, and handcrafted deliverables by Paneventz.",
      url: "https://paneventz.in/services",
    },
  };
}

export default async function ServicesPage() {
  const [setting, services] = await Promise.all([
    db.websiteSetting.findFirst().then(serializeData),
    db.service.findMany({ where: { is_published: true }, orderBy: { sort_order: "asc" } }).then(serializeData),
  ]);

  const servicesSchema = {
    "@context": "https://schema.org",
    "@type": "OfferCatalog",
    "name": "Paneventz Wedding Photography & Cinematography Collections",
    "itemListElement": (services || []).map((srv: any, idx: number) => ({
      "@type": "Offer",
      "itemOffered": {
        "@type": "Service",
        "name": srv.name,
        "description": srv.short_description || srv.description || "Bespoke wedding photography and cinematography collection.",
        "provider": {
          "@type": "ProfessionalService",
          "name": "Paneventz",
          "url": "https://paneventz.in",
        },
      },
      "price": srv.price_from || undefined,
      "priceCurrency": "INR",
      "position": idx + 1,
    })),
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
        "name": "Services & Pricing",
        "item": "https://paneventz.in/services",
      },
    ],
  };

  return (
    <main className="min-h-screen bg-[#0c0c0d] text-[#d6d6d8]">
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(servicesSchema) }}
      />
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbsSchema) }}
      />
      <Navbar studioName={setting?.studio_name || "Paneventz"} />

      <section className="pt-40 pb-20 px-6 lg:px-12 text-center max-w-4xl mx-auto">
        <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-4 block font-light">
          INVESTMENT & COLLECTIONS
        </span>
        <h1 className="font-serif text-5xl sm:text-7xl text-[#f5f5f7] font-light mb-6">
          Bespoke Commission Pricing
        </h1>
        <p className="text-sm sm:text-base text-[#a1a1aa] font-light leading-relaxed max-w-2xl mx-auto">
          We offer handcrafted coverage tailored to the cadence and intimacy of your celebration.
        </p>
      </section>

      <section className="pb-28 px-6 lg:px-12 max-w-7xl mx-auto">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {services.map((srv: any, idx: number) => (
            <div
              key={srv.id}
              className={`rounded-3xl p-8 sm:p-10 flex flex-col justify-between border ${
                idx === 1
                  ? "bg-[#141417] border-[#c4a472] shadow-2xl relative shadow-[#c4a472]/10"
                  : "bg-[#121214] border-white/5 hover:border-white/20"
              }`}
            >
              <div>
                <span className="text-[11px] uppercase tracking-widest text-[#c4a472] font-semibold block mb-2">
                  Collection {idx + 1}
                </span>
                <h2 className="font-serif text-3xl text-[#f5f5f7] font-light mb-2">{srv.name}</h2>
                {srv.short_description && (
                  <p className="text-xs text-[#a1a1aa] font-light mb-6">{srv.short_description}</p>
                )}
                <div className="mb-8">
                  <span className="text-xs text-[#71717a] uppercase tracking-wider block mb-1">
                    Investment Starting From
                  </span>
                  <span className="font-serif text-4xl text-[#f5f5f7] font-light">
                    {formatIndianCurrency(srv.price_from)}
                  </span>
                </div>
                {srv.description && (
                  <p className="text-[#a1a1aa] text-xs font-light leading-relaxed mb-8">
                    {srv.description}
                  </p>
                )}
              </div>

              <Link
                href="/#enquire"
                className={`w-full py-3.5 rounded-full text-xs uppercase tracking-widest text-center font-bold transition-all ${
                  idx === 1
                    ? "bg-[#c4a472] text-[#0c0c0d] hover:bg-[#b45309]"
                    : "border border-white/20 text-[#f5f5f7] hover:border-[#c4a472] hover:text-[#c4a472]"
                }`}
              >
                Inquire Date Availability
              </Link>
            </div>
          ))}
        </div>
      </section>

      <Footer setting={setting} />
    </main>
  );
}