"use client";

import React, { useState, useEffect } from "react";
import Link from "next/link";
import { Menu, X, Sparkles } from "lucide-react";

export default function Navbar({ studioName = "Paneventz" }: { studioName?: string }) {
  const [scrolled, setScrolled] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 40);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <nav
      className={`fixed top-0 left-0 w-full z-50 transition-all duration-300 px-6 lg:px-12 py-5 flex items-center justify-between ${
        scrolled
          ? "bg-[#0c0c0d]/90 backdrop-blur-md border-b border-white/5 py-4 shadow-2xl"
          : "bg-transparent"
      }`}
    >
      <Link href="/" className="flex items-center gap-3">
        <span className="font-serif text-2xl lg:text-3xl tracking-widest text-[#f5f5f7] font-normal uppercase">
          {studioName}
        </span>
      </Link>

      <div className="hidden md:flex items-center gap-8 text-xs uppercase tracking-widest font-light text-[#a1a1aa]">
        <Link href="#stories" className="hover:text-[#c4a472] transition-colors">
          Stories
        </Link>
        <Link href="#films" className="hover:text-[#c4a472] transition-colors">
          Films
        </Link>
        <Link href="/services" className="hover:text-[#c4a472] transition-colors">
          Services
        </Link>
        <Link href="/blog" className="hover:text-[#c4a472] transition-colors">
          Journal
        </Link>
        <Link href="/client-portal" className="hover:text-[#c4a472] transition-colors">
          VIP Portal
        </Link>
      </div>

      <div className="hidden md:flex items-center gap-4">
        <Link
          href="#enquire"
          className="px-6 py-2.5 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b45309] text-[#0c0c0d] font-semibold hover:opacity-90 transition-all shadow-lg hover:shadow-[#c4a472]/20"
        >
          Check Availability
        </Link>
      </div>

      <button
        onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
        className="md:hidden text-[#f5f5f7] p-2 focus:outline-none"
        aria-label="Toggle Menu"
      >
        {mobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
      </button>

      {mobileMenuOpen && (
        <div className="md:hidden fixed inset-0 top-[70px] bg-[#0c0c0d] p-8 flex flex-col gap-6 text-sm uppercase tracking-widest font-light border-t border-white/10">
          <Link
            href="#stories"
            onClick={() => setMobileMenuOpen(false)}
            className="text-lg text-[#f5f5f7] hover:text-[#c4a472]"
          >
            Stories
          </Link>
          <Link
            href="#films"
            onClick={() => setMobileMenuOpen(false)}
            className="text-lg text-[#f5f5f7] hover:text-[#c4a472]"
          >
            Films
          </Link>
          <Link
            href="/services"
            onClick={() => setMobileMenuOpen(false)}
            className="text-lg text-[#f5f5f7] hover:text-[#c4a472]"
          >
            Services & Investment
          </Link>
          <Link
            href="/blog"
            onClick={() => setMobileMenuOpen(false)}
            className="text-lg text-[#f5f5f7] hover:text-[#c4a472]"
          >
            Journal
          </Link>
          <Link
            href="/client-portal"
            onClick={() => setMobileMenuOpen(false)}
            className="text-lg text-[#f5f5f7] hover:text-[#c4a472]"
          >
            VIP Client Portal
          </Link>
          <Link
            href="#enquire"
            onClick={() => setMobileMenuOpen(false)}
            className="mt-4 px-6 py-3.5 text-center rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b45309] text-[#0c0c0d] font-bold"
          >
            Check Availability
          </Link>
        </div>
      )}
    </nav>
  );
}