import React from "react";
import Link from "next/link";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Clock, User } from "lucide-react";

import type { Metadata } from "next";

export const revalidate = 60;

export async function generateMetadata(): Promise<Metadata> {
  return {
    title: "Wedding Stories, Guides & Editorial Journal | Paneventz",
    description:
      "Read luxury destination wedding planning guides, royal palace photography insights, and cinematic love stories from Paneventz.",
    alternates: {
      canonical: "https://paneventz.in/blog",
    },
    openGraph: {
      title: "Wedding Stories, Guides & Editorial Journal | Paneventz",
      description:
        "Read luxury destination wedding planning guides, royal palace photography insights, and cinematic love stories from Paneventz.",
      url: "https://paneventz.in/blog",
    },
  };
}

export default async function BlogIndexPage() {
  const [setting, posts] = await Promise.all([
    db.websiteSetting.findFirst().then(serializeData),
    db.blogPost.findMany({
      where: { is_published: true },
      orderBy: { published_at: "desc" },
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
        "name": "Wedding Journal",
        "item": "https://paneventz.in/blog",
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
          JOURNAL & EDITORIALS
        </span>
        <h1 className="font-serif text-5xl sm:text-7xl text-[#f5f5f7] font-light mb-6">
          Wedding Stories & Guides
        </h1>
        <p className="text-sm sm:text-base text-[#a1a1aa] font-light max-w-xl mx-auto">
          Insights into destination wedding planning, royal palace celebrations, and the art of cinematic storytelling.
        </p>
      </section>

      <section className="pb-28 px-6 lg:px-12 max-w-7xl mx-auto">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {posts.map((post: any) => (
            <Link
              key={post.id}
              href={`/blog/${post.slug}`}
              className="group bg-[#121214] border border-white/5 rounded-3xl overflow-hidden flex flex-col hover:border-[#c4a472]/40 transition-all duration-300"
            >
              <div className="relative aspect-[16/10] overflow-hidden bg-[#09090b]">
                <img
                  src={post.featured_image || "https://paneventz.in/images/1.jpg"}
                  alt={post.title}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                />
                {post.category && (
                  <span className="absolute top-4 left-4 bg-black/70 backdrop-blur-sm text-[#c4a472] px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-semibold border border-[#c4a472]/20">
                    {post.category}
                  </span>
                )}
              </div>

              <div className="p-8 flex-1 flex flex-col justify-between">
                <div>
                  <div className="flex items-center gap-4 text-[11px] text-[#71717a] uppercase tracking-wider mb-3">
                    <span className="flex items-center gap-1">
                      <Clock size={12} /> {post.read_time_minutes || 5} min read
                    </span>
                    <span>•</span>
                    <span>{post.author_name || "Paneventz Studio"}</span>
                  </div>
                  <h2 className="font-serif text-2xl text-[#f5f5f7] font-light group-hover:text-[#c4a472] transition-colors leading-snug mb-3">
                    {post.title}
                  </h2>
                  <p className="text-xs text-[#a1a1aa] font-light line-clamp-3 leading-relaxed">
                    {post.excerpt}
                  </p>
                </div>
                <span className="text-xs uppercase tracking-widest text-[#c4a472] font-semibold mt-6 block">
                  Read Article →
                </span>
              </div>
            </Link>
          ))}
        </div>
      </section>

      <Footer setting={setting} />
    </main>
  );
}