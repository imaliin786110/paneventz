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

export const revalidate = 60;

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

  return (
    <main className="min-h-screen bg-[#0c0c0d] text-[#d6d6d8]">
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