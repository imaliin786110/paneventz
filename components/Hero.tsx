import React from "react";
import Link from "next/link";
import { ArrowDown } from "lucide-react";

export default function Hero({ setting }: { setting: any }) {
  const eyebrow = setting?.hero_eyebrow || "Wedding Photography & Films";
  const heading = setting?.hero_heading || "Paneventz";
  const description =
    setting?.hero_description ||
    "We create timeless photographs and cinematic films for couples who want their wedding story to live far beyond the day itself.";
  const buttonLabel = setting?.hero_button_label || "Explore Our Stories";
  const buttonUrl = setting?.hero_button_url || "#stories";

  return (
    <section className="relative min-h-screen flex items-center justify-center text-center px-6 overflow-hidden pt-20">
      {/* Background Image / Gradient */}
      <div
        className="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat transition-all duration-700"
        style={{
          backgroundImage: `linear-gradient(rgba(12,12,13,0.5), rgba(12,12,13,0.85)), url('https://paneventz.in/images/hero.webp')`,
        }}
      />

      <div className="relative z-10 max-w-4xl mx-auto flex flex-col items-center">
        <span className="text-xs lg:text-sm tracking-[0.3em] uppercase text-[#c4a472] mb-6 font-light">
          {eyebrow}
        </span>
        <h1 className="font-serif text-5xl sm:text-7xl lg:text-8xl tracking-tight text-[#f5f5f7] mb-8 font-light leading-tight">
          {heading}
        </h1>
        <p className="max-w-2xl text-base sm:text-lg text-[#d6d6d8]/90 font-light leading-relaxed mb-10">
          {description}
        </p>

        <div className="flex flex-col sm:flex-row items-center gap-4">
          <Link
            href={buttonUrl}
            className="px-8 py-4 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b45309] text-[#0c0c0d] font-semibold hover:scale-105 transition-all shadow-xl hover:shadow-[#c4a472]/20"
          >
            {buttonLabel}
          </Link>
          <Link
            href="#enquire"
            className="px-8 py-4 rounded-full text-xs uppercase tracking-widest border border-white/20 text-[#f5f5f7] hover:border-[#c4a472] hover:text-[#c4a472] transition-all backdrop-blur-sm"
          >
            Book Consultation
          </Link>
        </div>
      </div>

      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/40 flex flex-col items-center gap-2 animate-bounce">
        <span className="text-[10px] uppercase tracking-widest">Scroll</span>
        <ArrowDown size={14} />
      </div>
    </section>
  );
}