"use client";

import React, { useState, useEffect } from "react";
import Link from "next/link";
import { Menu, X, Sparkles, Lock, ArrowRight } from "lucide-react";

export default function Navbar({ studioName = "Paneventz" }: { studioName?: string }) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  // Prevent background page scrolling when mobile menu is open
  useEffect(() => {
    if (mobileMenuOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [mobileMenuOpen]);

  // Close on Escape key press
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape" && mobileMenuOpen) {
        setMobileMenuOpen(false);
      }
    };
    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [mobileMenuOpen]);

  const navLinks = [
    { label: "Stories", href: "/#stories" },
    { label: "Cinematic Films", href: "/#films" },
    { label: "Services & Investment", href: "/services" },
    { label: "Guest Photos", href: "/galleries", isAi: true },
    { label: "Client Portal", href: "/client-portal", isLock: true },
    { label: "Wedding Journal", href: "/blog" },
  ];

  return (
    <>
      <header className="fixed top-0 left-0 w-full z-50 bg-[#09090b]/95 backdrop-blur-2xl border-b border-[#c4a472]/30 shadow-2xl transition-all duration-300">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
          {/* Brand Logo */}
          <Link href="/" className="flex items-center gap-2 group shrink-0">
            <span className="font-serif text-2xl lg:text-3xl tracking-[0.2em] text-[#ffffff] font-medium uppercase group-hover:text-[#c4a472] transition-colors drop-shadow-md">
              {studioName}
            </span>
          </Link>

          {/* Center Navigation Links (Desktop Unchanged) */}
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
              <span>Guest Photos</span>
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

          {/* Right Action Button (Desktop Unchanged) */}
          <div className="hidden md:flex items-center gap-4 shrink-0">
            <Link
              href="/#enquire"
              className="px-6 py-2.5 rounded-full text-xs uppercase tracking-widest bg-[#c4a472] hover:bg-[#b09060] text-black font-bold hover:scale-105 transition-all shadow-lg shadow-[#c4a472]/30 flex items-center gap-1.5"
            >
              <span>Contact Us</span>
              <ArrowRight size={14} />
            </Link>
          </div>

          {/* Mobile Menu Toggle Button */}
          <button
            onClick={() => setMobileMenuOpen(true)}
            className="lg:hidden w-11 h-11 flex items-center justify-center rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/[0.12] hover:border-[#c4a472]/40 text-white hover:text-[#c4a472] transition-all focus:outline-none"
            aria-label="Open Mobile Menu"
            aria-expanded={mobileMenuOpen}
          >
            <Menu size={22} />
          </button>
        </div>
      </header>

      {/* FULL-SCREEN EDITORIAL LUXURY MOBILE NAVIGATION OVERLAY */}
      <div
        className={`fixed inset-0 z-[60] bg-[#070709]/98 backdrop-blur-2xl flex flex-col justify-between transition-all duration-500 lg:hidden overflow-hidden ${
          mobileMenuOpen
            ? "opacity-100 pointer-events-auto visible"
            : "opacity-0 pointer-events-none invisible"
        }`}
        role="dialog"
        aria-modal="true"
        aria-label="Mobile Navigation Menu"
      >
        {/* AMBIENT WARM RADIAL GLOWS */}
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-lg h-80 bg-[radial-gradient(ellipse_70%_50%_at_50%_0%,rgba(196,164,114,0.14),transparent_70%)] pointer-events-none" />
        <div className="absolute bottom-0 right-0 w-64 h-64 bg-[radial-gradient(circle_at_bottom_right,rgba(196,164,114,0.06),transparent_70%)] pointer-events-none" />

        {/* TOP BAR WITH LOGO & CLOSE BUTTON */}
        <div className="relative px-6 py-4 flex items-center justify-between border-b border-white/[0.08]">
          <Link
            href="/"
            onClick={() => setMobileMenuOpen(false)}
            className="flex items-center gap-2 group"
          >
            <span className="font-serif text-2xl tracking-[0.2em] text-white font-medium uppercase group-hover:text-[#c4a472] transition-colors">
              {studioName}
            </span>
          </Link>

          <button
            onClick={() => setMobileMenuOpen(false)}
            className="w-12 h-12 flex items-center justify-center rounded-full bg-white/[0.04] hover:bg-white/[0.08] border border-white/[0.12] hover:border-[#c4a472]/50 text-white hover:text-[#c4a472] transition-all focus:outline-none"
            aria-label="Close Mobile Navigation"
          >
            <X size={22} />
          </button>
        </div>

        {/* SCROLLABLE NAVIGATION CONTENT */}
        <div className="relative flex-1 overflow-y-auto px-6 py-8 flex flex-col justify-between gap-8">
          {/* PRIMARY EDITORIAL NAVIGATION LINKS */}
          <nav className="flex flex-col space-y-1">
            <span className="text-[10px] uppercase tracking-[0.3em] text-[#c4a472] font-semibold mb-3 px-2">
              NAVIGATION
            </span>

            {navLinks.map((item, idx) => (
              <Link
                key={idx}
                href={item.href}
                onClick={() => setMobileMenuOpen(false)}
                className="group flex items-center justify-between py-3 px-3 rounded-xl hover:bg-white/[0.03] transition-all duration-300"
              >
                <div className="flex items-baseline gap-3">
                  <span className="text-[10px] font-mono text-[#c4a472]/60 group-hover:text-[#c4a472] transition-colors">
                    0{idx + 1}
                  </span>
                  <span className="font-serif text-2xl sm:text-3xl text-white/90 group-hover:text-white group-hover:translate-x-1.5 transition-all duration-300 font-light">
                    {item.label}
                  </span>
                </div>

                <div className="flex items-center gap-2">
                  {item.isAi ? (
                    <span className="text-[9px] uppercase tracking-wider text-[#00f0ff] bg-[#00f0ff]/10 border border-[#00f0ff]/30 px-2.5 py-0.5 rounded-full flex items-center gap-1 font-semibold">
                      <Sparkles size={10} /> AI
                    </span>
                  ) : item.isLock ? (
                    <span className="text-[9px] uppercase tracking-wider text-[#c4a472] bg-[#c4a472]/10 border border-[#c4a472]/30 px-2.5 py-0.5 rounded-full flex items-center gap-1 font-semibold">
                      <Lock size={10} /> VIP
                    </span>
                  ) : (
                    <span className="text-white/30 group-hover:text-[#c4a472] group-hover:translate-x-1 transition-all">
                      <ArrowRight size={16} />
                    </span>
                  )}
                </div>
              </Link>
            ))}
          </nav>

          {/* PRIMARY CONVERSION CTA & FOOTER STATEMENT */}
          <div className="pt-6 border-t border-white/[0.08] space-y-4">
            <Link
              href="/#enquire"
              onClick={() => setMobileMenuOpen(false)}
              className="group w-full py-4 rounded-full text-xs uppercase tracking-[0.25em] bg-gradient-to-r from-[#d8b886] via-[#c4a472] to-[#b38a4c] hover:from-[#e2c79b] hover:via-[#d8b886] hover:to-[#c4a472] text-[#09090b] font-bold flex items-center justify-center gap-2 shadow-[0_10px_30px_rgba(196,164,114,0.3)] hover:shadow-[0_15px_40px_rgba(196,164,114,0.45)] transition-all duration-300"
            >
              <span>Book Consultation</span>
              <ArrowRight size={14} className="group-hover:translate-x-1 transition-transform" />
            </Link>

            <div className="text-center pt-2">
              <p className="text-[10px] uppercase tracking-[0.3em] text-[#94a3b8] font-light">
                WEDDING PHOTOGRAPHY &amp; FILMS
              </p>
              <p className="text-[10px] text-zinc-500 mt-1 font-light tracking-wider">
                Mumbai · Udaipur · Goa · Available Worldwide
              </p>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}