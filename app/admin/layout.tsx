"use client";

import React from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { LayoutDashboard, Users, Camera, Image, Settings, Sparkles, Search, ExternalLink } from "lucide-react";

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();

  if (pathname === "/admin/login") {
    return <>{children}</>;
  }

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
              Admin
            </span>
          </div>

          <nav className="space-y-1.5 text-xs font-medium">
            <Link
              href="/admin"
              className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-colors ${
                pathname === "/admin"
                  ? "bg-[#c4a472]/15 text-[#c4a472] font-semibold border border-[#c4a472]/30"
                  : "text-white/80 hover:text-white hover:bg-white/5"
              }`}
            >
              <LayoutDashboard size={16} className={pathname === "/admin" ? "text-[#c4a472]" : "text-white/60"} /> Dashboard
            </Link>
            <Link
              href="/admin/enquiries"
              className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-colors ${
                pathname === "/admin/enquiries"
                  ? "bg-[#c4a472]/15 text-[#c4a472] font-semibold border border-[#c4a472]/30"
                  : "text-white/80 hover:text-white hover:bg-white/5"
              }`}
            >
              <Users size={16} className={pathname === "/admin/enquiries" ? "text-[#c4a472]" : "text-white/60"} /> Inquiries CRM
            </Link>
            <Link
              href="/admin/stories"
              className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-colors ${
                pathname === "/admin/stories"
                  ? "bg-[#c4a472]/15 text-[#c4a472] font-semibold border border-[#c4a472]/30"
                  : "text-white/80 hover:text-white hover:bg-white/5"
              }`}
            >
              <Camera size={16} className={pathname === "/admin/stories" ? "text-[#c4a472]" : "text-white/60"} /> Stories & Media
            </Link>
            <Link
              href="/admin/wedding-albums"
              className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-colors ${
                pathname === "/admin/wedding-albums"
                  ? "bg-[#c4a472]/15 text-[#c4a472] font-semibold border border-[#c4a472]/30"
                  : "text-white/80 hover:text-white hover:bg-white/5"
              }`}
            >
              <Image size={16} className={pathname === "/admin/wedding-albums" ? "text-[#c4a472]" : "text-white/60"} /> Client Albums & AI
            </Link>
            <Link
              href="/admin/ai-blog-writer"
              className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-colors ${
                pathname === "/admin/ai-blog-writer"
                  ? "bg-[#00f0ff]/20 text-[#00f0ff] font-semibold border border-[#00f0ff]/40"
                  : "text-[#00f0ff] hover:bg-[#00f0ff]/10 font-semibold"
              }`}
            >
              <Sparkles size={16} /> 1-Click AI Blog Writer
            </Link>
            <Link
              href="/admin/seo-scanner"
              className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-colors ${
                pathname === "/admin/seo-scanner"
                  ? "bg-[#c4a472]/15 text-[#c4a472] font-semibold border border-[#c4a472]/30"
                  : "text-white/80 hover:text-white hover:bg-white/5"
              }`}
            >
              <Search size={16} className={pathname === "/admin/seo-scanner" ? "text-[#c4a472]" : "text-white/60"} /> SEO Health Audit
            </Link>
            <Link
              href="/admin/settings"
              className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-colors ${
                pathname === "/admin/settings"
                  ? "bg-[#c4a472]/15 text-[#c4a472] font-semibold border border-[#c4a472]/30"
                  : "text-white/80 hover:text-white hover:bg-white/5"
              }`}
            >
              <Settings size={16} className={pathname === "/admin/settings" ? "text-[#c4a472]" : "text-white/60"} /> Website Settings
            </Link>
          </nav>
        </div>

        <div className="pt-6 border-t border-white/5">
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