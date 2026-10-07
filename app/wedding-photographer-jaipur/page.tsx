import LocationPageView, { getLocationMetadata } from "@/components/LocationPageView";
import type { Metadata } from "next";

export const dynamic = "force-dynamic";
export const revalidate = 0;

export async function generateMetadata(): Promise<Metadata> {
  return getLocationMetadata("jaipur");
}

export default function Page() {
  return <LocationPageView slug="jaipur" />;
}
