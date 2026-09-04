import { NextResponse } from "next/server";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";

export async function GET(
  req: Request,
  { params }: { params: Promise<{ resource: string }> }
) {
  try {
    const { resource } = await params;

    let data: any[] = [];
    switch (resource) {
      case "stories":
        data = await db.story.findMany({ orderBy: { sort_order: "asc" } });
        break;
      case "films":
        data = await db.film.findMany({ orderBy: { sort_order: "asc" } });
        break;
      case "services":
        data = await db.service.findMany({ orderBy: { sort_order: "asc" } });
        break;
      case "testimonials":
        data = await db.testimonial.findMany({ orderBy: { sort_order: "asc" } });
        break;
      case "faqs":
        data = await db.faq.findMany({ orderBy: { sort_order: "asc" } });
        break;
      case "terms":
        data = await db.termsAndCondition.findMany({ orderBy: { version: "desc" } });
        break;
      default:
        return NextResponse.json({ error: "Unknown resource" }, { status: 400 });
    }

    return NextResponse.json({ data: serializeData(data) });
  } catch (error) {
    console.error("GET /api/admin/content error:", error);
    return NextResponse.json({ error: "Failed to fetch data" }, { status: 500 });
  }
}

export async function POST(
  req: Request,
  { params }: { params: Promise<{ resource: string }> }
) {
  try {
    const { resource } = await params;
    const body = await req.json();
    const { id, ...payload } = body;

    let result: any = null;

    if (resource === "stories") {
      if (id) {
        result = await db.story.update({
          where: { id: BigInt(id) },
          data: {
            couple_name: payload.couple_name,
            location: payload.location,
            cover_image: payload.cover_image,
            description: payload.description,
            is_published: payload.is_published !== false,
            updated_at: new Date(),
          },
        });
      } else {
        result = await db.story.create({
          data: {
            couple_name: payload.couple_name,
            location: payload.location,
            cover_image: payload.cover_image || "/images/1.jpg",
            description: payload.description,
            is_published: payload.is_published !== false,
            created_at: new Date(),
            updated_at: new Date(),
          },
        });
      }
    } else if (resource === "films") {
      if (id) {
        result = await db.film.update({
          where: { id: BigInt(id) },
          data: {
            title: payload.title,
            couple_name: payload.couple_name,
            location: payload.location,
            video_url: payload.video_url,
            thumbnail: payload.thumbnail,
            description: payload.description,
            is_featured: Boolean(payload.is_featured),
            is_published: payload.is_published !== false,
            updated_at: new Date(),
          },
        });
      } else {
        result = await db.film.create({
          data: {
            title: payload.title,
            couple_name: payload.couple_name,
            location: payload.location,
            video_url: payload.video_url,
            thumbnail: payload.thumbnail || "/images/hero.jpg",
            description: payload.description,
            is_featured: Boolean(payload.is_featured),
            is_published: payload.is_published !== false,
            created_at: new Date(),
            updated_at: new Date(),
          },
        });
      }
    } else if (resource === "services") {
      if (id) {
        result = await db.service.update({
          where: { id: BigInt(id) },
          data: {
            name: payload.name,
            short_description: payload.short_description,
            description: payload.description,
            price_from: payload.price_from ? Number(payload.price_from) : null,
            is_published: payload.is_published !== false,
            updated_at: new Date(),
          },
        });
      } else {
        result = await db.service.create({
          data: {
            name: payload.name,
            short_description: payload.short_description,
            description: payload.description,
            price_from: payload.price_from ? Number(payload.price_from) : null,
            is_published: payload.is_published !== false,
            created_at: new Date(),
            updated_at: new Date(),
          },
        });
      }
    } else if (resource === "testimonials") {
      if (id) {
        result = await db.testimonial.update({
          where: { id: BigInt(id) },
          data: {
            couple_name: payload.couple_name,
            location: payload.location,
            rating: Number(payload.rating) || 5,
            review: payload.review,
            photo: payload.photo,
            is_published: payload.is_published !== false,
            updated_at: new Date(),
          },
        });
      } else {
        result = await db.testimonial.create({
          data: {
            couple_name: payload.couple_name,
            location: payload.location,
            rating: Number(payload.rating) || 5,
            review: payload.review,
            photo: payload.photo || "/images/1.jpg",
            is_published: payload.is_published !== false,
            created_at: new Date(),
            updated_at: new Date(),
          },
        });
      }
    } else if (resource === "faqs") {
      if (id) {
        result = await db.faq.update({
          where: { id: BigInt(id) },
          data: {
            question: payload.question,
            answer: payload.answer,
            is_published: payload.is_published !== false,
            updated_at: new Date(),
          },
        });
      } else {
        result = await db.faq.create({
          data: {
            question: payload.question,
            answer: payload.answer,
            is_published: payload.is_published !== false,
            created_at: new Date(),
            updated_at: new Date(),
          },
        });
      }
    } else if (resource === "terms") {
      if (id) {
        result = await db.termsAndCondition.update({
          where: { id: BigInt(id) },
          data: {
            advance_percentage: Number(payload.advance_percentage) || 40,
            balance_percentage: Number(payload.balance_percentage) || 60,
            balance_due: payload.balance_due,
            estimated_delivery_period: payload.estimated_delivery_period,
            cancellation_policy: payload.cancellation_policy,
            content: payload.content,
            updated_at: new Date(),
          },
        });
      } else {
        result = await db.termsAndCondition.create({
          data: {
            advance_percentage: Number(payload.advance_percentage) || 40,
            balance_percentage: Number(payload.balance_percentage) || 60,
            balance_due: payload.balance_due,
            estimated_delivery_period: payload.estimated_delivery_period,
            cancellation_policy: payload.cancellation_policy,
            content: payload.content,
            created_at: new Date(),
            updated_at: new Date(),
          },
        });
      }
    }

    return NextResponse.json({ success: true, item: serializeData(result) });
  } catch (error) {
    console.error("POST /api/admin/content error:", error);
    return NextResponse.json({ error: "Failed to save item" }, { status: 500 });
  }
}

export async function DELETE(
  req: Request,
  { params }: { params: Promise<{ resource: string }> }
) {
  try {
    const { resource } = await params;
    const url = new URL(req.url);
    const id = url.searchParams.get("id");

    if (!id) {
      return NextResponse.json({ error: "Missing ID" }, { status: 400 });
    }

    const bigId = BigInt(id);

    switch (resource) {
      case "stories":
        await db.story.delete({ where: { id: bigId } });
        break;
      case "films":
        await db.film.delete({ where: { id: bigId } });
        break;
      case "services":
        await db.service.delete({ where: { id: bigId } });
        break;
      case "testimonials":
        await db.testimonial.delete({ where: { id: bigId } });
        break;
      case "faqs":
        await db.faq.delete({ where: { id: bigId } });
        break;
      case "terms":
        await db.termsAndCondition.delete({ where: { id: bigId } });
        break;
      default:
        return NextResponse.json({ error: "Unknown resource" }, { status: 400 });
    }

    return NextResponse.json({ success: true });
  } catch (error) {
    console.error("DELETE /api/admin/content error:", error);
    return NextResponse.json({ error: "Failed to delete item" }, { status: 500 });
  }
}
