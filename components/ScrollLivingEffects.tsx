"use client";

import React, { useEffect, useRef } from "react";

export default function ScrollLivingEffects() {
  const progressBarRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    let ticking = false;

    const onScroll = () => {
      if (!ticking) {
        window.requestAnimationFrame(() => {
          if (progressBarRef.current) {
            const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = totalHeight > 0 ? window.scrollY / totalHeight : 0;
            progressBarRef.current.style.transform = `scaleX(${progress})`;
          }
          ticking = false;
        });
        ticking = true;
      }
    };

    window.addEventListener("scroll", onScroll, { passive: true });

    // Lightweight Intersection Observer for headings
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("in-view-glow");
          }
        });
      },
      { threshold: 0.1, rootMargin: "0px 0px -40px 0px" }
    );

    const targets = document.querySelectorAll(
      "h1, h2, h3, .font-serif, .stat-counter-box, .luxury-card-interactive, [data-glow]"
    );
    targets.forEach((el) => observer.observe(el));

    return () => {
      window.removeEventListener("scroll", onScroll);
      observer.disconnect();
    };
  }, []);

  return (
    <>
      {/* 1. TOP AMBIENT GOLD SCROLL PROGRESSION BEAM (Zero-re-render direct GPU transform) */}
      <div className="fixed top-0 left-0 w-full h-[2.5px] z-[70] pointer-events-none bg-transparent">
        <div
          ref={progressBarRef}
          className="h-full w-full origin-left bg-gradient-to-r from-[#c4a472] via-[#fff4df] to-[#b38a4c] shadow-[0_0_12px_rgba(196,164,114,0.9),0_0_20px_rgba(247,235,215,0.6)]"
          style={{ transform: "scaleX(0)", willChange: "transform" }}
        />
      </div>

      {/* 2. LIVING MULTI-COLOR AMBIENT BACKGROUND SYSTEM (100% GPU Hardware Accelerated) */}
      <div
        className="fixed inset-0 pointer-events-none z-[0] overflow-hidden select-none"
        style={{ contain: "strict", transform: "translateZ(0)" }}
      >
        {/* Layer A: Royal Champagne Gold Aurora (Top-Right) */}
        <div
          className="absolute -top-[15%] -right-[10%] w-[600px] h-[600px] rounded-full animate-bg-drift-1 pointer-events-none"
          style={{
            background:
              "radial-gradient(circle at center, rgba(196,164,114,0.12) 0%, rgba(212,175,55,0.05) 40%, transparent 70%)",
          }}
        />

        {/* Layer B: Velvet Rose / Sunset Blush Aurora (Mid-Left) */}
        <div
          className="absolute top-[30%] -left-[15%] w-[600px] h-[600px] rounded-full animate-bg-drift-2 pointer-events-none"
          style={{
            background:
              "radial-gradient(circle at center, rgba(180,60,105,0.08) 0%, rgba(217,119,87,0.04) 45%, transparent 70%)",
          }}
        />

        {/* Layer C: Cinematic Sapphire Indigo Glow (Mid-Right) */}
        <div
          className="absolute top-[60%] -right-[12%] w-[580px] h-[580px] rounded-full animate-bg-drift-3 pointer-events-none"
          style={{
            background:
              "radial-gradient(circle at center, rgba(20,90,160,0.08) 0%, rgba(0,180,216,0.035) 45%, transparent 70%)",
          }}
        />

        {/* Layer D: Deep Warm Gold Ember Glow (Bottom-Center) */}
        <div
          className="absolute -bottom-[15%] left-[20%] w-[650px] h-[650px] rounded-full animate-bg-drift-4 pointer-events-none"
          style={{
            background:
              "radial-gradient(circle at center, rgba(196,164,114,0.1) 0%, rgba(245,210,140,0.04) 45%, transparent 70%)",
          }}
        />
      </div>
    </>
  );
}
