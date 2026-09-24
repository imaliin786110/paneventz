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

  const videoBg = setting?.hero_background_video
    ? setting.hero_background_video.startsWith("http") || setting.hero_background_video.startsWith("/")
      ? setting.hero_background_video
      : `/storage/${setting.hero_background_video}`
    : null;

  const imageBg = setting?.hero_background_image
    ? setting.hero_background_image.startsWith("http") || setting.hero_background_image.startsWith("/")
      ? setting.hero_background_image
      : `/storage/${setting.hero_background_image}`
    : "https://paneventz.in/images/hero.webp";

  return (
    <section className="relative min-h-screen flex items-center justify-center text-center px-6 overflow-hidden pt-20">
      {/* Dynamic Autoplay Background Video / Image with Subtle Ken Burns Drift */}
      {videoBg ? (
        <div className="absolute inset-0 z-0 overflow-hidden">
          <video
            autoPlay
            loop
            muted
            playsInline
            preload="auto"
            className="w-full h-full object-cover scale-105 animate-ken-burns"
            poster={imageBg}
          >
            <source src={videoBg} type="video/mp4" />
          </video>
          <div className="absolute inset-0 bg-gradient-to-b from-[#0c0c0d]/70 via-[#0c0c0d]/60 to-[#0c0c0d]/95" />
        </div>
      ) : (
        <div className="absolute inset-0 z-0 overflow-hidden">
          <img
            src={imageBg}
            alt={`${heading || "Paneventz"} - Luxury Wedding Photography & Cinematic Films in India`}
            // @ts-ignore
            fetchPriority="high"
            decoding="async"
            className="w-full h-full object-cover object-center scale-105 animate-ken-burns transition-all duration-1000"
          />
          <div className="absolute inset-0 bg-gradient-to-b from-[#0c0c0d]/50 via-[#0c0c0d]/60 to-[#0c0c0d]/85" />
        </div>
      )}

      {/* AMBIENT LIVING GOLD AURA */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-[radial-gradient(circle,rgba(196,164,114,0.12),transparent_70%)] blur-3xl pointer-events-none animate-ambient-glow" />

      <div className="relative z-10 max-w-4xl mx-auto flex flex-col items-center">
        <span className="text-xs lg:text-sm tracking-[0.3em] uppercase text-[#c4a472] mb-6 font-light animate-pulse [animation-duration:5s]">
          {eyebrow}
        </span>
        <h1 className="font-serif text-5xl sm:text-7xl lg:text-8xl tracking-tight text-[#f5f5f7] mb-8 font-light leading-tight drop-shadow-2xl">
          {heading}
        </h1>
        <p className="max-w-2xl text-base sm:text-lg text-[#d6d6d8]/90 font-light leading-relaxed mb-10">
          {description}
        </p>

        <div className="flex flex-col sm:flex-row items-center gap-4">
          <Link
            href={buttonUrl}
            className="btn-shimmer px-8 py-4 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b45309] text-[#0c0c0d] font-bold hover:scale-105 active:scale-95 transition-all duration-300 shadow-xl hover:shadow-[0_10px_30px_rgba(196,164,114,0.35)]"
          >
            {buttonLabel}
          </Link>
          <Link
            href="#enquire"
            className="px-8 py-4 rounded-full text-xs uppercase tracking-widest border border-white/20 text-[#f5f5f7] hover:border-[#c4a472] hover:text-[#c4a472] hover:bg-white/[0.04] hover:scale-105 active:scale-95 transition-all duration-300 backdrop-blur-sm shadow-lg"
          >
            Book Consultation
          </Link>
        </div>
      </div>

      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/40 flex flex-col items-center gap-2 animate-subtle-float">
        <span className="text-[10px] uppercase tracking-widest text-[#c4a472]/70 font-medium">Scroll</span>
        <ArrowDown size={14} className="animate-bounce" />
      </div>
    </section>
  );
}