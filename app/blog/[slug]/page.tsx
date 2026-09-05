import React from "react";
import { notFound } from "next/navigation";
import type { Metadata } from "next";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import Link from "next/link";
import { Clock, ArrowLeft } from "lucide-react";

export const revalidate = 60;

export async function generateStaticParams() {
  try {
    const posts = await db.blogPost.findMany({
      where: { is_published: true },
      select: { slug: true },
    });
    return posts.map((post) => ({ slug: post.slug }));
  } catch {
    return [];
  }
}

export async function generateMetadata({ params }: { params: Promise<{ slug: string }> }): Promise<Metadata> {
  const { slug } = await params;
  if (!slug) return {};

  const post = await db.blogPost.findFirst({ where: { slug } });
  if (!post) return {};

  const title = `${post.title} | Paneventz Journal`;
  const description = post.excerpt || "Timeless insights on luxury wedding photography and destination celebrations.";
  const canonicalUrl = `https://paneventz.in/blog/${post.slug}`;
  const imageUrl = post.featured_image || "https://paneventz.in/images/1.jpg";

  return {
    title,
    description,
    alternates: {
      canonical: canonicalUrl,
    },
    openGraph: {
      title: post.title,
      description,
      url: canonicalUrl,
      type: "article",
      publishedTime: post.published_at?.toISOString() || undefined,
      authors: [post.author_name || "Paneventz Studio"],
      images: [
        {
          url: imageUrl,
          width: 1200,
          height: 630,
          alt: post.title,
        },
      ],
    },
    twitter: {
      card: "summary_large_image",
      title: post.title,
      description,
      images: [imageUrl],
    },
  };
}

export default async function BlogPostPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  if (!slug) notFound();

  const [setting, post] = await Promise.all([
    db.websiteSetting.findFirst().then(serializeData),
    db.blogPost.findFirst({ where: { slug } }).then(serializeData),
  ]);

  if (!post) notFound();

  const blogPostSchema = {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "mainEntityOfPage": {
      "@type": "WebPage",
      "@id": `https://paneventz.in/blog/${post.slug}`,
    },
    "headline": post.title,
    "description": post.excerpt || undefined,
    "image": [post.featured_image || "https://paneventz.in/images/1.jpg"],
    "datePublished": post.published_at || post.created_at,
    "dateModified": post.updated_at || post.published_at || post.created_at,
    "author": {
      "@type": "Person",
      "name": post.author_name || "Paneventz Studio",
      "url": "https://paneventz.in",
    },
    "publisher": {
      "@type": "ProfessionalService",
      "name": "Paneventz",
      "logo": {
        "@type": "ImageObject",
        "url": "https://paneventz.in/images/1.jpg",
      },
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
        "name": "Wedding Journal",
        "item": "https://paneventz.in/blog",
      },
      {
        "@type": "ListItem",
        "position": 3,
        "name": post.title,
        "item": `https://paneventz.in/blog/${post.slug}`,
      },
    ],
  };

  return (
    <main className="min-h-screen bg-[#0c0c0d] text-[#d6d6d8]">
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(blogPostSchema) }}
      />
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbsSchema) }}
      />
      <Navbar studioName={setting?.studio_name || "Paneventz"} />

      <article className="pt-40 pb-28 px-6 lg:px-12 max-w-4xl mx-auto">
        <Link
          href="/blog"
          className="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-[#a1a1aa] hover:text-[#c4a472] transition-colors mb-8"
        >
          <ArrowLeft size={14} /> Back to Journal
        </Link>

        {post.category && (
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-4 block font-semibold">
            {post.category}
          </span>
        )}

        <h1 className="font-serif text-4xl sm:text-6xl text-[#f5f5f7] font-light leading-tight mb-6">
          {post.title}
        </h1>

        <div className="flex items-center gap-6 text-xs text-[#71717a] uppercase tracking-wider pb-8 mb-10 border-b border-white/5">
          <span>By {post.author_name || "Paneventz Studio"}</span>
          <span>•</span>
          <span className="flex items-center gap-1">
            <Clock size={12} /> {post.read_time_minutes || 5} min read
          </span>
        </div>

        {post.featured_image && (
          <div className="relative aspect-[16/9] rounded-3xl overflow-hidden mb-12 bg-[#121214]">
            <img src={post.featured_image} alt={post.title} className="w-full h-full object-cover" />
          </div>
        )}

        <div
          className="prose prose-invert prose-amber max-w-none text-base sm:text-lg font-light leading-relaxed space-y-6 text-[#d6d6d8]/90"
          dangerouslySetInnerHTML={{ __html: post.content }}
        />
      </article>

      <Footer setting={setting} />
    </main>
  );
}