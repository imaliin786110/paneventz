import React from "react";
import Link from "next/link";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { ArrowRight, Compass, Sparkles } from "lucide-react";

export default function NotFound() {
  return (
    <main className="min-h-screen bg-[#0c0c0d] text-[#d6d6d8] flex flex-col justify-between">
      <Navbar studioName="Paneventz" />

      <section className="pt-44 pb-28 px-6 lg:px-12 flex flex-col items-center justify-center text-center my-auto">
        <div className="max-w-2xl mx-auto">
          {/* Eyebrow */}
          <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#c4a472]/10 border border-[#c4a472]/20 text-[#c4a472] text-[11px] uppercase tracking-[0.25em] font-semibold mb-6">
            <Compass size={13} />
            <span>Page Not Found · 404</span>
          </div>

          <h1 className="font-serif text-5xl sm:text-7xl lg:text-8xl text-white font-light tracking-tight mb-6">
            A Moment Lost in Time
          </h1>

          <p className="text-sm sm:text-base text-[#a1a1aa] font-light leading-relaxed max-w-lg mx-auto mb-10">
            The wedding story, gallery, or editorial page you are looking for may have been moved or is no longer available.
          </p>

          {/* Quick Helpful Navigation Hub */}
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg mx-auto mb-10 text-left">
            <Link
              href="/"
              className="p-4 rounded-2xl bg-[#121214] border border-white/5 hover:border-[#c4a472]/40 hover:bg-[#161619] transition-all group"
            >
              <span className="text-[11px] uppercase tracking-wider text-[#c4a472] font-semibold block mb-1">
                Homepage
              </span>
              <span className="text-sm text-white font-serif flex items-center justify-between">
                Return to Stories & Films
                <ArrowRight size={14} className="group-hover:translate-x-1 text-[#c4a472] transition-transform" />
              </span>
            </Link>

            <Link
              href="/services"
              className="p-4 rounded-2xl bg-[#121214] border border-white/5 hover:border-[#c4a472]/40 hover:bg-[#161619] transition-all group"
            >
              <span className="text-[11px] uppercase tracking-wider text-[#c4a472] font-semibold block mb-1">
                Pricing & Collections
              </span>
              <span className="text-sm text-white font-serif flex items-center justify-between">
                Investment Packages
                <ArrowRight size={14} className="group-hover:translate-x-1 text-[#c4a472] transition-transform" />
              </span>
            </Link>

            <Link
              href="/blog"
              className="p-4 rounded-2xl bg-[#121214] border border-white/5 hover:border-[#c4a472]/40 hover:bg-[#161619] transition-all group"
            >
              <span className="text-[11px] uppercase tracking-wider text-[#c4a472] font-semibold block mb-1">
                The Journal
              </span>
              <span className="text-sm text-white font-serif flex items-center justify-between">
                Wedding Guides & Articles
                <ArrowRight size={14} className="group-hover:translate-x-1 text-[#c4a472] transition-transform" />
              </span>
            </Link>

            <Link
              href="/#enquire"
              className="p-4 rounded-2xl bg-[#121214] border border-white/5 hover:border-[#c4a472]/40 hover:bg-[#161619] transition-all group"
            >
              <span className="text-[11px] uppercase tracking-wider text-[#c4a472] font-semibold block mb-1">
                Consultation
              </span>
              <span className="text-sm text-white font-serif flex items-center justify-between">
                Reserve Your Wedding Date
                <ArrowRight size={14} className="group-hover:translate-x-1 text-[#c4a472] transition-transform" />
              </span>
            </Link>
          </div>

          <Link
            href="/"
            className="inline-flex items-center gap-2 px-8 py-3.5 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b45309] text-[#0c0c0d] font-bold shadow-xl hover:scale-105 transition-all"
          >
            <span>Back to Home</span>
          </Link>
        </div>
      </section>

      <Footer setting={null} />
    </main>
  );
}
