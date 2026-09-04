"use client";

import React from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import {
  LayoutDashboard,
  Users,
  Camera,
  Image,
  Settings,
  Sparkles,
  Search,
  ExternalLink,
  Film,
  Award,
  Star,
  HelpCircle,
  FileText,
} from "lucide-react";

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();

  if (pathname === "/admin/login") {
    return <>{children}</>;
  }

  const navItems = [
    { label: "Dashboard", href: "/admin", icon: LayoutDashboard },
    { label: "Inquiries CRM", href: "/admin/enquiries", icon: Users },
    { label: "Global Content CMS", href: "/admin/settings", icon: Settings },
    { label: "Stories & Media", href: "/admin/stories", icon: Camera },
    { label: "Cinema Films (4K)", href: "/admin/films", icon: Film },
    { label: "Services & Pricing", href: "/admin/services", icon: Award },
    { label: "Reviews / Testimonials", href: "/admin/testimonials", icon: Star },
    { label: "FAQs Manager", href: "/admin/faqs", icon: HelpCircle },
    { label: "Contract Terms & Policies", href: "/admin/terms", icon: FileText },
    { label: "VIP Portals & Facial AI", href: "/admin/wedding-albums", icon: Image },
    { label: "1-Click AI Blog Writer", href: "/admin/ai-blog-writer", icon: Sparkles, special: true },
    { label: "SEO Health Audit", href: "/admin/seo-scanner", icon: Search },
  ];

  return (
    <div className="min-h-screen bg-[#09090b] text-[#d6d6d8] flex flex-col md:flex-row">
      {/* Sidebar */}
      <aside className="w-full md:w-64 bg-[#111113] border-r border-white/5 p-6 flex flex-col justify-between shrink-0">
        <div>
          <div className="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
            <Link href="/admin" className="font-serif text-2xl text-white tracking-widest uppercase">
              Paneventz
            </Link>
            <span className="text-[10px] bg-[#c4a472]/20 text-[#c4a472] px-2 py-0.5 rounded-full font-bold uppercase">
              CMS Suite
            </span>
          </div>

          <nav className="space-y-1 text-xs font-medium max-h-[calc(100vh-220px)] overflow-y-auto pr-1">
            {navItems.map((item) => {
              const Icon = item.icon;
              const isActive = pathname === item.href;

              if (item.special) {
                return (
                  <Link
                    key={item.href}
                    href={item.href}
                    className={`flex items-center gap-3 px-4 py-2.5 rounded-xl transition-colors ${
                      isActive
                        ? "bg-[#00f0ff]/20 text-[#00f0ff] font-semibold border border-[#00f0ff]/40"
                        : "text-[#00f0ff] hover:bg-[#00f0ff]/10 font-semibold"
                    }`}
                  >
                    <Icon size={15} /> {item.label}
                  </Link>
                );
              }

              return (
                <Link
                  key={item.href}
                  href={item.href}
                  className={`flex items-center gap-3 px-4 py-2.5 rounded-xl transition-colors ${
                    isActive
                      ? "bg-[#c4a472]/15 text-[#c4a472] font-semibold border border-[#c4a472]/30"
                      : "text-white/80 hover:text-white hover:bg-white/5"
                  }`}
                >
                  <Icon size={15} className={isActive ? "text-[#c4a472]" : "text-white/60"} /> {item.label}
                </Link>
              );
            })}
          </nav>
        </div>

        <div className="pt-6 border-t border-white/5 mt-4">
          <Link
            href="/"
            target="_blank"
            className="flex items-center justify-between text-xs text-[#a1a1aa] hover:text-[#c4a472] transition-colors"
          >
            <span>View Live Website</span>
            <ExternalLink size={14} />
          </Link>
        </div>
      </aside>

      {/* Main Content Area */}
      <main className="flex-1 p-6 md:p-10 overflow-y-auto">
        {children}
      </main>
    </div>
  );
}