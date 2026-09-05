import React from "react";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";

import type { Metadata } from "next";

export const revalidate = 60;

export async function generateMetadata(): Promise<Metadata> {
  return {
    title: "Client Agreement & Booking Terms | Paneventz",
    description:
      "Review our transparent wedding photography reservation, payment schedule, delivery timeline, and master copyright policies.",
    alternates: {
      canonical: "https://paneventz.in/terms",
    },
    openGraph: {
      title: "Client Agreement & Booking Terms | Paneventz",
      description:
        "Review our transparent wedding photography reservation, payment schedule, delivery timeline, and master copyright policies.",
      url: "https://paneventz.in/terms",
    },
  };
}

export default async function TermsPage() {
  const [setting, terms] = await Promise.all([
    db.websiteSetting.findFirst().then(serializeData),
    db.termsAndCondition.findFirst({ orderBy: { version: "desc" } }).then(serializeData),
  ]);

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
        "name": "Terms & Conditions",
        "item": "https://paneventz.in/terms",
      },
    ],
  };

  return (
    <main className="min-h-screen bg-[#0c0c0d] text-[#d6d6d8]">
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbsSchema) }}
      />
      <Navbar studioName={setting?.studio_name || "Paneventz"} />

      <section className="pt-40 pb-20 px-6 lg:px-12 text-center max-w-4xl mx-auto">
        <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-4 block font-light">
          TRANSPARENT CLIENT AGREEMENT
        </span>
        <h1 className="font-serif text-5xl sm:text-6xl text-[#f5f5f7] font-light mb-6">
          Terms & Conditions
        </h1>
        <p className="text-sm text-[#a1a1aa] font-light max-w-xl mx-auto">
          Clear, transparent policies ensuring seamless coverage and timely delivery of your handcrafted memories.
        </p>
      </section>

      <section className="pb-28 px-6 lg:px-12 max-w-4xl mx-auto space-y-10">
        <div className="bg-[#121214] border border-white/5 rounded-3xl p-8 sm:p-12 space-y-8">
          <div>
            <h2 className="font-serif text-2xl text-[#f5f5f7] font-light mb-3">
              1. Payment Schedule & Retainer
            </h2>
            <p className="text-sm text-[#a1a1aa] font-light leading-relaxed">
              A retainer of <strong>{terms?.advance_percentage || 40}%</strong> is required to secure and reserve your dates. The remaining balance of <strong>{terms?.balance_percentage || 60}%</strong> is due {terms?.balance_due || "on or before the event date"}. Retainers are {terms?.advance_refundable ? "refundable" : "non-refundable"} as we turn down all other commissions for your reserved dates.
            </p>
          </div>

          <div>
            <h2 className="font-serif text-2xl text-[#f5f5f7] font-light mb-3">
              2. Delivery Timelines & Master Files
            </h2>
            <p className="text-sm text-[#a1a1aa] font-light leading-relaxed">
              {terms?.delivery_policy ||
                `Initial highlights preview will be shared within 7 to 10 days. The complete edited wedding gallery and cinematic films will be delivered within ${terms?.estimated_delivery_period || "6 to 8 weeks"} through our VIP Client Portal.`}
            </p>
          </div>

          <div>
            <h2 className="font-serif text-2xl text-[#f5f5f7] font-light mb-3">
              3. Travel & Destination Logistics
            </h2>
            <p className="text-sm text-[#a1a1aa] font-light leading-relaxed">
              {terms?.late_night_transportation ||
                "For destination weddings across India and worldwide, client provides travel flights and hotel accommodations for the principal photography and cinematography crew."}
            </p>
          </div>

          {terms?.content && (
            <div className="pt-6 border-t border-white/5 text-sm text-[#a1a1aa] font-light leading-relaxed whitespace-pre-line">
              {terms.content}
            </div>
          )}
        </div>
      </section>

      <Footer setting={setting} />
    </main>
  );
}