"use client";

import React, { useState, useEffect } from "react";
import Link from "next/link";
import { Menu, X, Sparkles, Lock, ArrowRight } from "lucide-react";

export default function Navbar({ studioName = "Paneventz" }: { studioName?: string }) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  return (
    <header className="fixed top-0 left-0 w-full z-50 bg-[#09090b]/95 backdrop-blur-2xl border-b border-[#c4a472]/30 shadow-2xl transition-all duration-300">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
        {/* Brand Logo */}
        <Link href="/" className="flex items-center gap-2 group shrink-0">
          <span className="font-serif text-2xl lg:text-3xl tracking-[0.2em] text-[#ffffff] font-medium uppercase group-hover:text-[#c4a472] transition-colors drop-shadow-md">
            {studioName}
          </span>
        </Link>

        {/* Center Navigation Links */}
        <nav className="hidden lg:flex items-center gap-5 xl:gap-7 text-xs uppercase tracking-widest font-semibold">
          <Link
            href="/#stories"
            className="text-white hover:text-[#c4a472] transition-colors py-1.5 border-b-2 border-transparent hover:border-[#c4a472]"
          >
            Stories
          </Link>
          <Link
            href="/#films"
            className="text-white hover:text-[#c4a472] transition-colors py-1.5 border-b-2 border-transparent hover:border-[#c4a472]"
          >
            Films
          </Link>
          <Link
            href="/services"
            className="text-white hover:text-[#c4a472] transition-colors py-1.5 border-b-2 border-transparent hover:border-[#c4a472]"
          >
            Services
          </Link>
          <Link
            href="/galleries"
            className="bg-[#00f0ff]/20 hover:bg-[#00f0ff]/30 text-[#00f0ff] border border-[#00f0ff] px-4 py-1.5 rounded-full font-bold flex items-center gap-1.5 transition-all shadow-md shadow-[#00f0ff]/20"
          >
            <Sparkles size={14} className="text-[#00f0ff]" />
            <span>Guest Photos AI</span>
          </Link>
          <Link
            href="/client-portal"
            className="bg-[#c4a472]/20 hover:bg-[#c4a472]/30 text-[#c4a472] border border-[#c4a472] px-4 py-1.5 rounded-full font-bold flex items-center gap-1.5 transition-all shadow-md shadow-[#c4a472]/20"
          >
            <Lock size={13} className="text-[#c4a472]" />
            <span>Download Story</span>
          </Link>
          <Link
            href="/blog"
            className="text-white hover:text-[#c4a472] transition-colors py-1.5 border-b-2 border-transparent hover:border-[#c4a472]"
          >
            Journal
          </Link>
        </nav>

        {/* Right Action Button */}
        <div className="hidden md:flex items-center gap-4 shrink-0">
          <Link
            href="/#enquire"
            className="px-6 py-2.5 rounded-full text-xs uppercase tracking-widest bg-[#c4a472] hover:bg-[#b09060] text-black font-bold hover:scale-105 transition-all shadow-lg shadow-[#c4a472]/30 flex items-center gap-1.5"
          >
            <span>Contact Us</span>
            <ArrowRight size={14} />
          </Link>
        </div>

        {/* Mobile Menu Toggle */}
        <button
          onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
          className="lg:hidden text-white p-2 rounded-xl bg-white/10 border border-white/20 focus:outline-none"
          aria-label="Toggle Menu"
        >
          {mobileMenuOpen ? <X size={22} /> : <Menu size={22} />}
        </button>
      </div>

      {/* Mobile Drawer */}
      {mobileMenuOpen && (
        <div className="lg:hidden bg-[#09090b] border-t border-[#c4a472]/30 p-6 flex flex-col gap-4 text-xs uppercase tracking-widest font-semibold shadow-2xl">
          <Link
            href="/#stories"
            onClick={() => setMobileMenuOpen(false)}
            className="text-white hover:text-[#c4a472] py-2 border-b border-white/10"
          >
            Stories
          </Link>
          <Link
            href="/#films"
            onClick={() => setMobileMenuOpen(false)}
            className="text-white hover:text-[#c4a472] py-2 border-b border-white/10"
          >
            Cinematic Films
          </Link>
          <Link
            href="/services"
            onClick={() => setMobileMenuOpen(false)}
            className="text-white hover:text-[#c4a472] py-2 border-b border-white/10"
          >
            Services & Investment
          </Link>
          <Link
            href="/galleries"
            onClick={() => setMobileMenuOpen(false)}
            className="bg-[#00f0ff]/20 text-[#00f0ff] border border-[#00f0ff] p-3.5 rounded-2xl font-bold flex items-center justify-between"
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
            className="bg-[#c4a472]/20 text-[#c4a472] border border-[#c4a472] p-3.5 rounded-2xl font-bold flex items-center justify-between"
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
            className="text-white hover:text-[#c4a472] py-2 border-b border-white/10"
          >
            Wedding Journal
          </Link>
          <Link
            href="/#enquire"
            onClick={() => setMobileMenuOpen(false)}
            className="w-full py-3.5 rounded-2xl text-xs uppercase tracking-widest bg-[#c4a472] text-black font-bold flex items-center justify-center gap-2 shadow-xl shadow-[#c4a472]/20 mt-2"
          >
            <span>Contact Us</span>
            <ArrowRight size={16} />
          </Link>
        </div>
      )}
    </header>
  );
}