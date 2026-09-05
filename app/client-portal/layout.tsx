import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "VIP Client Portal | Download Wedding Story | Paneventz",
  description: "Private portal for couples to access and download high-resolution wedding photographs and 4K cinematic films.",
  robots: {
    index: false,
    follow: false,
  },
};

export default function ClientPortalLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return <>{children}</>;
}
