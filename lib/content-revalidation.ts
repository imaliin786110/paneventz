import { revalidatePath } from "next/cache";

/**
 * Keep public pages in sync immediately after an admin mutation.  The site
 * used time-based ISR only, which meant a successful admin save could still
 * show the previous content until the cache expired.
 */
export function refreshPublicContent(resource?: string) {
  revalidatePath("/", "layout");

  const sharedPaths = [
    "/",
    "/services",
    "/blog",
    "/galleries",
    "/terms",
    "/wedding-photographer-mumbai",
    "/wedding-photographer-udaipur",
    "/wedding-photographer-goa",
    "/wedding-photographer-delhi",
    "/wedding-photographer-jaipur",
  ];
  for (const path of sharedPaths) revalidatePath(path);

  const pathsByResource: Record<string, string[]> = {
    stories: ["/"],
    films: ["/"],
    services: ["/", "/services"],
    testimonials: ["/"],
    faqs: ["/"],
    terms: ["/terms"],
    blog: ["/blog"],
    galleries: ["/galleries", "/client-portal"],
  };

  for (const path of resource ? pathsByResource[resource] || [] : []) {
    revalidatePath(path);
  }
}

export function refreshBlogPost(slug?: string) {
  refreshPublicContent("blog");
  if (slug) revalidatePath(`/blog/${slug}`);
}

export function refreshGallery(slug?: string) {
  refreshPublicContent("galleries");
  if (slug) revalidatePath(`/gallery/${slug}`);
}
