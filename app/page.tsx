import React from "react";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Navbar from "@/components/Navbar";
import Hero from "@/components/Hero";
import CounterStats from "@/components/CounterStats";
import Approach from "@/components/Approach";
import StoriesGrid from "@/components/StoriesGrid";
import FilmsSection from "@/components/FilmsSection";
import ColorGrading from "@/components/ColorGrading";
import ServicesSection from "@/components/ServicesSection";
import TestimonialsSection from "@/components/TestimonialsSection";
import FaqSection from "@/components/FaqSection";
import EnquiryForm from "@/components/EnquiryForm";
import Footer from "@/components/Footer";

import type { Metadata } from "next";

export const revalidate = 60;

export async function generateMetadata(): Promise<Metadata> {
  let setting = null;
  try {
    setting = await db.websiteSetting.findFirst();
  } catch (e) {
    console.error("Error fetching homepage settings:", e);
  }

  const title = setting?.meta_title || "Paneventz | Luxury Wedding Photography & Films in India";
  const description =
    setting?.meta_description ||
    "Timeless luxury wedding photography and cinematic love stories documented across Mumbai, Udaipur, Goa, and worldwide destinations.";

  return {
    title,
    description,
    alternates: {
      canonical: "https://paneventz.in",
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
  };
}

export default async function HomePage() {
  let setting = null;
  let stories: any[] = [];
  let films: any[] = [];
  let services: any[] = [];
  let testimonials: any[] = [];
  let faqs: any[] = [];

  try {
    const [dbSetting, dbStories, dbFilms, dbServices, dbTestimonials, dbFaqs] = await Promise.all([
      db.websiteSetting.findFirst(),
      db.story.findMany({
        where: { is_published: true },
        orderBy: { sort_order: "asc" },
      }),
      db.film.findMany({
        where: { is_published: true },
        orderBy: { sort_order: "asc" },
      }),
      db.service.findMany({
        where: { is_published: true },
        orderBy: { sort_order: "asc" },
      }),
      db.testimonial.findMany({
        where: { is_published: true },
        orderBy: { sort_order: "asc" },
      }),
      db.faq.findMany({
        where: { is_published: true },
        orderBy: { sort_order: "asc" },
      }),
    ]);

    setting = serializeData(dbSetting);
    stories = serializeData(dbStories);
    films = serializeData(dbFilms);
    services = serializeData(dbServices);
    testimonials = serializeData(dbTestimonials);
    faqs = serializeData(dbFaqs);
  } catch (error) {
    console.error("Database connection error on Homepage:", error);
  }

  const faqSchema = faqs.length > 0 ? {
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
      {faqSchema && (
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(faqSchema) }}
        />
      )}
      <Navbar studioName={setting?.studio_name || "Paneventz"} />
      <Hero setting={setting} />
      <CounterStats setting={setting} />
      <Approach setting={setting} />
      <StoriesGrid stories={stories} />
      <FilmsSection films={films} />
      <ColorGrading setting={setting} />
      <ServicesSection services={services} />
      <TestimonialsSection testimonials={testimonials} />
      <FaqSection faqs={faqs} />
      <EnquiryForm />
      <Footer setting={setting} />
    </main>
  );
}