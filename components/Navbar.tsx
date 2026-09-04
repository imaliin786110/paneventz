"use client";

import React, { useState, useEffect } from "react";
import Link from "next/link";
import { Menu, X, Sparkles, Lock, ArrowRight } from "lucide-react";

export default function Navbar({ studioName = "Paneventz" }: { studioName?: string }) {
  const [scrolled, setScrolled] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 20);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <header className="fixed top-0 left-0 w-full z-50 transition-all duration-300">
      {/* Top Bar with Glassmorphism & High-Contrast Shadow */}
      <div
        className={`w-full transition-all duration-300 px-6 lg:px-12 ${
          scrolled
            ? "py-3 bg-[#070709]/95 backdrop-blur-2xl border-b border-white/10 shadow-2xl"
            : "py-5 bg-gradient-to-b from-black/90 via-black/60 to-transparent backdrop-blur-md"
        }`}
      >
        <div className="max-w-7xl mx-auto flex items-center justify-between">
          {/* Brand Logo */}
          <Link href="/" className="flex items-center gap-2 group">
            <span className="font-serif text-2xl lg:text-3xl tracking-[0.2em] text-white font-normal uppercase group-hover:text-[#c4a472] transition-colors drop-shadow-md">
              {studioName}
            </span>
          </Link>

          {/* Center Navigation Links - Floating Glassmorphism Pill */}
          <nav className="hidden xl:flex items-center gap-6 bg-black/60 backdrop-blur-xl border border-white/15 px-6 py-2 rounded-full shadow-2xl text-[11px] uppercase tracking-widest">
            <Link
              href="/#stories"
              className="text-zinc-200 hover:text-[#c4a472] transition-colors font-medium drop-shadow-sm"
            >
              Stories
            </Link>
            <Link
              href="/#films"
              className="text-zinc-200 hover:text-[#c4a472] transition-colors font-medium drop-shadow-sm"
            >
              Films
            </Link>
            <Link
              href="/services"
              className="text-zinc-200 hover:text-[#c4a472] transition-colors font-medium drop-shadow-sm"
            >
              Services
            </Link>
            <Link
              href="/galleries"
              className="bg-[#00f0ff]/15 hover:bg-[#00f0ff]/25 text-[#00f0ff] border border-[#00f0ff]/40 px-3.5 py-1 rounded-full font-bold flex items-center gap-1.5 transition-all shadow-sm"
            >
              <Sparkles size={13} className="text-[#00f0ff]" />
              <span>Guest Photos AI</span>
            </Link>
            <Link
              href="/client-portal"
              className="bg-[#c4a472]/15 hover:bg-[#c4a472]/25 text-[#c4a472] border border-[#c4a472]/40 px-3.5 py-1 rounded-full font-bold flex items-center gap-1.5 transition-all shadow-sm"
            >
              <Lock size={12} className="text-[#c4a472]" />
              <span>Download Story</span>
            </Link>
            <Link
              href="/blog"
              className="text-zinc-200 hover:text-[#c4a472] transition-colors font-medium drop-shadow-sm"
            >
              Journal
            </Link>
          </nav>

          {/* Right Action Button */}
          <div className="hidden md:flex items-center gap-4">
            <Link
              href="/#enquire"
              className="px-6 py-2.5 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b09060] text-black font-bold hover:scale-105 transition-all shadow-lg shadow-[#c4a472]/25 flex items-center gap-1.5"
            >
              <span>Check Availability</span>
              <ArrowRight size={14} />
            </Link>
          </div>

          {/* Mobile Menu Toggle */}
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="xl:hidden text-white p-2 rounded-xl bg-black/60 border border-white/10 backdrop-blur-md focus:outline-none"
            aria-label="Toggle Menu"
          >
            {mobileMenuOpen ? <X size={22} /> : <Menu size={22} />}
          </button>
        </div>
      </div>

      {/* Mobile Drawer */}
      {mobileMenuOpen && (
        <div className="xl:hidden fixed inset-0 top-[70px] bg-[#070709]/98 backdrop-blur-3xl p-8 flex flex-col justify-between border-t border-white/10 z-50">
          <div className="flex flex-col gap-5 text-sm uppercase tracking-widest font-medium">
            <Link
              href="/#stories"
              onClick={() => setMobileMenuOpen(false)}
              className="text-white hover:text-[#c4a472] py-2 border-b border-white/5"
            >
              Stories
            </Link>
            <Link
              href="/#films"
              onClick={() => setMobileMenuOpen(false)}
              className="text-white hover:text-[#c4a472] py-2 border-b border-white/5"
            >
              Cinematic Films
            </Link>
            <Link
              href="/services"
              onClick={() => setMobileMenuOpen(false)}
              className="text-white hover:text-[#c4a472] py-2 border-b border-white/5"
            >
              Services & Collections
            </Link>
            <Link
              href="/galleries"
              onClick={() => setMobileMenuOpen(false)}
              className="bg-[#00f0ff]/15 text-[#00f0ff] border border-[#00f0ff]/30 p-3.5 rounded-2xl font-bold flex items-center justify-between"
            >
              <div className="flex items-center gap-2">
                <Sparkles size={16} />
                <span>Guest Photos AI (Face Recognition)</span>
              </div>
              <ArrowRight size={16} />
            </Link>
            <Link
              href="/client-portal"
              onClick={() => setMobileMenuOpen(false)}
              className="bg-[#c4a472]/15 text-[#c4a472] border border-[#c4a472]/30 p-3.5 rounded-2xl font-bold flex items-center justify-between"
            >
              <div className="flex items-center gap-2">
                <Lock size={16} />
                <span>Client Master Download</span>
              </div>
              <ArrowRight size={16} />
            </Link>
            <Link
              href="/blog"
              onClick={() => setMobileMenuOpen(false)}
              className="text-white hover:text-[#c4a472] py-2 border-b border-white/5"
            >
              Wedding Journal
            </Link>
          </div>

          <div className="pt-6">
            <Link
              href="/#enquire"
              onClick={() => setMobileMenuOpen(false)}
              className="w-full py-4 rounded-2xl text-xs uppercase tracking-widest bg-[#c4a472] text-black font-bold flex items-center justify-center gap-2 shadow-xl shadow-[#c4a472]/20"
            >
              <span>Check Availability & Pricing</span>
              <ArrowRight size={16} />
            </Link>
          </div>
        </div>
      )}
    </header>
  );
}