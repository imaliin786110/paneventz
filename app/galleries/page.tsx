import React from "react";
import Link from "next/link";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Lock, Camera, MapPin, Calendar, Search } from "lucide-react";

import type { Metadata } from "next";

export const revalidate = 60;

export async function generateMetadata(): Promise<Metadata> {
  return {
    title: "Guest Photos AI & Celebration Galleries | Paneventz",
    description:
      "Access your couple's wedding celebration gallery. Use our 1-second selfie AI finder to discover and download every photo you appear in.",
    alternates: {
      canonical: "https://paneventz.in/galleries",
    },
    openGraph: {
      title: "Guest Photos AI & Celebration Galleries | Paneventz",
      description:
        "Access your couple's wedding celebration gallery. Use our 1-second selfie AI finder to discover and download every photo you appear in.",
      url: "https://paneventz.in/galleries",
    },
  };
}

export default async function GalleriesIndexPage({
  searchParams,
}: {
  searchParams: Promise<{ q?: string }>;
}) {
  const { q } = await searchParams;
  const [setting, albums] = await Promise.all([
    db.websiteSetting.findFirst().then(serializeData),
    db.weddingAlbum.findMany({
      where: q
        ? {
            OR: [
              { title: { contains: q, mode: "insensitive" } },
              { couple_names: { contains: q, mode: "insensitive" } },
              { location: { contains: q, mode: "insensitive" } },
            ],
          }
        : {},
      include: {
        _count: { select: { photos: true } },
      },
      orderBy: { created_at: "desc" },
    }).then(serializeData),
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
        "name": "Guest Photos & Galleries",
        "item": "https://paneventz.in/galleries",
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
        <span className="text-xs uppercase tracking-[0.3em] text-[#00f0ff] mb-4 block font-semibold">
          CLIENT GALLERIES & GUEST PORTAL
        </span>
        <h1 className="font-serif text-5xl sm:text-7xl text-[#f5f5f7] font-light mb-6">
          Find Your Celebration Photos
        </h1>
        <p className="text-sm sm:text-base text-[#a1a1aa] font-light max-w-xl mx-auto mb-10">
          Select your couple's wedding below, take a 1-second selfie, and our AI will automatically find every photo you appear in!
        </p>

        {/* Search Bar */}
        <form method="GET" action="/galleries" className="max-w-md mx-auto flex items-center gap-3 bg-[#121214] border border-white/10 rounded-full px-5 py-2 shadow-2xl">
          <Search size={18} className="text-[#a1a1aa]" />
          <input
            type="text"
            name="q"
            defaultValue={q || ""}
            placeholder="Search by couple name or city..."
            className="w-full bg-transparent text-sm text-white placeholder-white/30 focus:outline-none"
          />
          <button
            type="submit"
            className="px-5 py-2 rounded-full text-xs uppercase tracking-wider bg-[#c4a472] text-[#0c0c0d] font-bold hover:opacity-90 transition-opacity"
          >
            Search
          </button>
        </form>
      </section>

      <section className="pb-28 px-6 lg:px-12 max-w-7xl mx-auto">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {albums.map((album: any) => (
            <div
              key={album.id}
              className="group bg-[#121214] border border-white/5 rounded-3xl overflow-hidden flex flex-col hover:border-[#00f0ff]/40 transition-all duration-300 shadow-2xl"
            >
              <div className="relative aspect-[16/10] overflow-hidden bg-[#09090b]">
                <img
                  src={album.cover_image ? (album.cover_image.startsWith("http") || album.cover_image.startsWith("/") ? album.cover_image : `/${album.cover_image}`) : "/images/1.jpg"}
                  alt={album.title}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                />

                {album.pin_code && (
                  <span className="absolute top-4 right-4 bg-black/70 backdrop-blur-md text-[#c4a472] px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-semibold border border-[#c4a472]/30 flex items-center gap-1">
                    <Lock size={10} /> PIN Protected
                  </span>
                )}

                <span className="absolute bottom-4 left-4 bg-black/70 backdrop-blur-md text-white px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-medium flex items-center gap-1">
                  <Camera size={10} /> {album._count?.photos || 0} Media Files
                </span>
              </div>

              <div className="p-8 flex-1 flex flex-col justify-between">
                <div>
                  <span className="text-[11px] uppercase tracking-widest text-[#c4a472] font-semibold block mb-1">
                    {album.couple_names || "Wedding Celebration"}
                  </span>
                  <h2 className="font-serif text-2xl text-[#f5f5f7] font-light mb-4">
                    {album.title}
                  </h2>
                  <div className="flex items-center gap-4 text-xs text-[#71717a] mb-6">
                    <span className="flex items-center gap-1">
                      <MapPin size={12} /> {album.location || "Destination"}
                    </span>
                  </div>
                </div>

                <Link
                  href={`/gallery/${album.slug}`}
                  className="w-full py-3.5 rounded-full text-xs uppercase tracking-widest text-center font-bold bg-gradient-to-r from-[#0099cc] to-[#00f0ff] text-[#070a12] hover:opacity-90 transition-all shadow-lg"
                >
                  Open Gallery & AI Face Finder →
                </Link>
              </div>
            </div>
          ))}
        </div>
      </section>

      <Footer setting={setting} />
    </main>
  );
}