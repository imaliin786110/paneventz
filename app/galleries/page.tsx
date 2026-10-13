import React from "react";
import Link from "next/link";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Lock, Camera, MapPin, Calendar, Search } from "lucide-react";
import { extractDriveId, getDriveThumbnailUrl } from "@/lib/drive";
function formatMediaUrl(url?: string | null, fallback = "/images/1.jpg"): string {
  if (!url) return fallback;
  if (url.startsWith("http") || url.startsWith("/") || url.startsWith("data:")) return url;
  return `/storage/${url}`;
}

import type { Metadata } from "next";

export const dynamic = "force-dynamic";
export const revalidate = 0;

export async function generateMetadata(): Promise<Metadata> {
  return {
    title: "Guest Photos & Celebration Galleries | Paneventz",
    description:
      "Access your couple's wedding celebration gallery. Use our 1-second selfie AI finder to discover and download every photo you appear in.",
    alternates: {
      canonical: "https://paneventz.in/galleries",
    },
    openGraph: {
      title: "Guest Photos & Celebration Galleries | Paneventz",
      description:
        "Access your couple's wedding celebration gallery. Use our 1-second selfie AI finder to discover and download every photo you appear in.",
      url: "https://paneventz.in/galleries",
    },
  };
}

export default async function GalleriesIndexPage({
  searchParams,
}: {
  searchParams?: Promise<{ q?: string }>;
}) {
  let q: string | undefined = undefined;
  try {
    if (searchParams) {
      const resolved = await searchParams;
      q = resolved?.q;
    }
  } catch (e) {
    console.warn("Could not read searchParams:", e);
  }

  let setting: any = null;
  let albums: any[] = [];

  try {
    setting = await db.websiteSetting.findFirst().then(serializeData).catch(() => null);
  } catch (e) {
    console.warn("Setting fetch error:", e);
  }

  try {
    const dbAlbums = await db.weddingAlbum
      .findMany({
        orderBy: { created_at: "desc" },
        include: {
          _count: { select: { photos: true } },
          photos: {
            take: 1,
            orderBy: { created_at: "desc" },
            select: { file_name: true, photo_url: true, thumbnail_url: true },
          },
        },
      })
      .then(serializeData);
    if (Array.isArray(dbAlbums)) {
      // Admin thumbnails are uploaded with their original filename. Resolve
      // that filename against the durable synced album media so a deployment
      // cannot leave the public card with a broken local `/storage` image.
      albums = await Promise.all(
        dbAlbums.map(async (album: any) => {
          const cover = String(album.cover_image || "");
          if (!cover.startsWith("/storage/") || !album.id) return album;
          const stem = cover.split("/").pop()?.split("_")[0];
          if (!stem) return album;
          const coverPhoto = await db.albumPhoto.findFirst({
            where: {
              wedding_album_id: BigInt(album.id),
              file_name: { startsWith: stem },
            },
            select: { file_name: true, photo_url: true, thumbnail_url: true },
          }).then(serializeData).catch(() => null);
          return coverPhoto ? { ...album, coverPhoto } : album;
        }),
      );
    }
  } catch (error) {
    console.warn("Albums with count fetch error:", error);
    try {
      const simpleAlbums = await db.weddingAlbum
        .findMany({
          orderBy: { created_at: "desc" },
        })
        .then(serializeData);
      if (Array.isArray(simpleAlbums)) {
        albums = simpleAlbums;
      }
    } catch (e2) {
      console.warn("Simple albums fetch error:", e2);
    }
  }

  const fallbackAlbums = [
    {
      id: "demo-1",
      title: "Royal Palace Wedding — Udaipur",
      slug: "aditi-kabir",
      couple_names: "Aditi & Kabir",
      location: "Udaipur, Rajasthan",
      cover_image: "/images/1.jpg",
      pin_code: "1235",
      _count: { photos: 150 },
    },
    {
      id: "demo-2",
      title: "Sunset Heritage Celebration — Goa",
      slug: "priya-rohit",
      couple_names: "Priya & Rohit",
      location: "Goa, India",
      cover_image: "/images/2.jpg",
      pin_code: null,
      _count: { photos: 220 },
    },
    {
      id: "demo-3",
      title: "Regal Haveli Union — Jaipur",
      slug: "zoya-ayaan",
      couple_names: "Zoya & Ayaan",
      location: "Jaipur, Rajasthan",
      cover_image: "/images/3.jpg",
      pin_code: "5678",
      _count: { photos: 180 },
    },
  ];

  const sourceAlbums = albums.length > 0 ? albums : fallbackAlbums;

  const displayAlbums = q
    ? sourceAlbums.filter((a: any) => {
        const query = q.toLowerCase();
        return (
          (a.title && a.title.toLowerCase().includes(query)) ||
          (a.couple_names && a.couple_names.toLowerCase().includes(query)) ||
          (a.location && a.location.toLowerCase().includes(query))
        );
      })
    : sourceAlbums;

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
          {displayAlbums.map((album: any) => (
            <div
              key={album.id}
              className="group bg-[#121214] border border-white/5 rounded-3xl overflow-hidden flex flex-col hover:border-[#00f0ff]/40 transition-all duration-300 shadow-2xl"
            >
              <div className="relative aspect-[16/10] overflow-hidden bg-[#09090b]">
                <img
                  src={(() => {
                    const cover = album.cover_image as string | null | undefined;
                    const coverInfo = extractDriveId(cover);
                    const firstPhoto = album.photos?.[0];
                    if (cover && /drive\.google\.com/i.test(cover) && coverInfo?.type === "file") return getDriveThumbnailUrl(coverInfo.id);
                    // Prefer the thumbnail selected in the admin panel when
                    // it is a local/uploaded image. Only Drive folder links
                    // need the first synced photo fallback below.
                    if (cover && !/drive\.google\.com/i.test(cover)) {
                      const coverFile = cover.split("/").pop()?.split("_")[0]?.toLowerCase();
                      const matchingPhoto = album.coverPhoto || (coverFile
                        ? album.photos?.find((photo: any) => String(photo.file_name || "").toLowerCase().startsWith(coverFile))
                        : null);
                      const matchingInfo = extractDriveId(matchingPhoto?.thumbnail_url || matchingPhoto?.photo_url);
                      if (matchingPhoto && matchingInfo?.type === "file" && /drive\.google\.com/i.test(matchingPhoto.thumbnail_url || matchingPhoto.photo_url)) {
                        return getDriveThumbnailUrl(matchingInfo.id);
                      }
                      // Local admin uploads are deployment-local. If the
                      // original file is gone, use the synced Drive photo
                      // rather than rendering a broken image icon.
                      if (cover.startsWith("/storage/") && firstPhoto?.photo_url) {
                        const firstSource = firstPhoto.thumbnail_url || firstPhoto.photo_url;
                        const firstInfo = extractDriveId(firstSource);
                        if (/drive\.google\.com/i.test(firstSource) && firstInfo?.type === "file") {
                          return getDriveThumbnailUrl(firstInfo.id);
                        }
                      }
                      return formatMediaUrl(cover, "/images/1.jpg");
                    }
                    if (firstPhoto?.thumbnail_url || firstPhoto?.photo_url) {
                      const source = firstPhoto.thumbnail_url || firstPhoto.photo_url;
                      const info = extractDriveId(source);
                      return /drive\.google\.com/i.test(source) && info?.type === "file"
                        ? getDriveThumbnailUrl(info.id)
                        : formatMediaUrl(source, "/images/1.jpg");
                    }
                    return formatMediaUrl(cover, "/images/1.jpg");
                  })()}
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
