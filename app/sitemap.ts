import { MetadataRoute } from "next";
import { db } from "@/lib/db";

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const baseUrl = "https://paneventz.in";

  let blogPosts: any[] = [];
  let locations: any[] = [];

  try {
    [blogPosts, locations] = await Promise.all([
      db.blogPost.findMany({
        where: { is_published: true },
        select: { slug: true, updated_at: true },
      }),
      db.location.findMany({
        where: { is_published: true },
        select: { slug: true, updated_at: true },
      }),
    ]);
  } catch (e) {
    console.error("Sitemap error:", e);
  }

  const staticRoutes: MetadataRoute.Sitemap = [
    { url: `${baseUrl}`, lastModified: new Date(), changeFrequency: "daily", priority: 1.0 },
    { url: `${baseUrl}/services`, lastModified: new Date(), changeFrequency: "weekly", priority: 0.9 },
    { url: `${baseUrl}/blog`, lastModified: new Date(), changeFrequency: "daily", priority: 0.9 },
    { url: `${baseUrl}/galleries`, lastModified: new Date(), changeFrequency: "weekly", priority: 0.8 },
    { url: `${baseUrl}/terms`, lastModified: new Date(), changeFrequency: "monthly", priority: 0.5 },
  ];

  const blogRoutes: MetadataRoute.Sitemap = blogPosts.map((post) => ({
    url: `${baseUrl}/blog/${post.slug}`,
    lastModified: post.updated_at || new Date(),
    changeFrequency: "weekly",
    priority: 0.8,
  }));

  const defaultLocationSlugs = ["mumbai", "udaipur", "goa", "jaipur", "delhi"];
  const allLocSlugs = Array.from(new Set([...defaultLocationSlugs, ...locations.map((l: any) => l.slug)]));

  const locationRoutes: MetadataRoute.Sitemap = allLocSlugs.map((slug) => ({
    url: `${baseUrl}/wedding-photographer-${slug}`,
    lastModified: new Date(),
    changeFrequency: "weekly",
    priority: 0.8,
  }));

  return [...staticRoutes, ...blogRoutes, ...locationRoutes];
}