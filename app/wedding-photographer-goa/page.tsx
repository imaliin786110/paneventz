import LocationPageView, { getLocationMetadata } from "@/components/LocationPageView";
import type { Metadata } from "next";

export const revalidate = 60;

export async function generateMetadata(): Promise<Metadata> {
  return getLocationMetadata("goa");
}

export default function Page() {
  return <LocationPageView slug="goa" />;
}
