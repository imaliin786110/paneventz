import React from "react";
import type { Metadata } from "next";
import { db } from "@/lib/db";
import GalleryClient from "./GalleryClient";

export const revalidate = 60;

export async function generateStaticParams() {
  try {
    const albums = await db.weddingAlbum.findMany({
      select: { slug: true },
    });
    return albums.map((a) => ({ slug: a.slug }));
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
  try {
    const album = await db.weddingAlbum.findUnique({
      where: { slug },
      select: { title: true, couple_names: true, location: true },
    });

    if (!album) {
      return {
        title: "Wedding Gallery | Paneventz Studio",
      };
    }

    return {
      title: `${album.title} | Luxury Wedding Gallery | Paneventz`,
      description: `View the luxury wedding heirloom collection for ${album.couple_names || album.title} in ${album.location || "India"}.`,
    };
  } catch {
    return {
      title: "Wedding Gallery | Paneventz Studio",
    };
  }
}

export default async function GalleryPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  return <GalleryClient initialSlug={slug} />;
}