"use client";

import React, { useEffect, useRef, useState } from "react";

function AnimatedCounter({ target, suffix }: { target: number; suffix: string }) {
  const [count, setCount] = useState(0);
  const [isVisible, setIsVisible] = useState(false);
  const containerRef = useRef<HTMLSpanElement>(null);

  useEffect(() => {
    const el = containerRef.current;
    if (!el) return;

    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting) {
          setIsVisible(true);
          let startTimestamp: number | null = null;
          const duration = 2200; // 2.2s smooth deceleration

          const step = (timestamp: number) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            // Ultra-smooth ease-out quartic
            const ease = 1 - Math.pow(1 - progress, 4);
            const current = Math.floor(ease * target);
            setCount(current);

            if (progress < 1) {
              window.requestAnimationFrame(step);
            } else {
              setCount(target);
            }
          };

          window.requestAnimationFrame(step);
          observer.disconnect();
        }
      },
      { threshold: 0.15, rootMargin: "0px 0px -40px 0px" }
    );

    observer.observe(el);
    return () => observer.disconnect();
  }, [target]);

  return (
    <span
      ref={containerRef}
      className="tabular-nums font-serif text-4xl sm:text-6xl lg:text-7xl font-light tracking-tight mb-2.5 flex items-baseline justify-center select-none"
    >
      <span className="bg-gradient-to-b from-[#fff6e5] via-[#d8b886] to-[#a87f45] bg-clip-text text-transparent drop-shadow-[0_2px_20px_rgba(196,164,114,0.3)] animate-pulse [animation-duration:4s]">
        {isVisible ? count : target}
      </span>
      <span className="text-2xl sm:text-3xl lg:text-4xl text-[#c4a472]/80 ml-1 font-light">
        {suffix}
      </span>
    </span>
  );
}

export default function CounterStats({ setting }: { setting: any }) {
  const stats = [
    {
      number: setting?.stat_1_number ?? 250,
      suffix: setting?.stat_1_suffix ?? "+",
      label:
        setting?.stat_1_label && setting.stat_1_label !== "Weddings Documented"
          ? setting.stat_1_label
          : "Weddings & Marriages Documented",
    },
    {
      number: setting?.stat_2_number ?? 10,
      suffix: setting?.stat_2_suffix ?? "+",
      label:
        setting?.stat_2_label && setting.stat_2_label !== "Years Experience"
          ? setting.stat_2_label
          : "Years of Artistic Experience",
    },
    {
      number: setting?.stat_3_number ?? 35,
      suffix: setting?.stat_3_suffix ?? "+",
      label:
        setting?.stat_3_label || "Royal Palaces & Destinations",
    },
    {
      number: setting?.stat_4_number ?? 100,
      suffix: setting?.stat_4_suffix ?? "%",
      label:
        setting?.stat_4_label && setting.stat_4_label !== "Handcrafted Heirlooms"
          ? setting.stat_4_label
          : "Client Trust & Handcrafted Heirlooms",
    },
  ];

  return (
    <section className="relative py-16 sm:py-20 px-4 sm:px-6 lg:px-12 bg-[#09090b] border-b border-white/[0.06] overflow-hidden">
      {/* LUXURY AMBIENT BACKDROP LIGHT */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-6xl h-48 bg-[radial-gradient(ellipse_70%_50%_at_50%_50%,rgba(196,164,114,0.06),transparent_70%)] pointer-events-none" />

      <div className="relative max-w-7xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 lg:gap-12 text-center">
        {stats.map((item, idx) => (
          <div
            key={idx}
            className="flex flex-col items-center justify-start p-3 sm:p-4 rounded-2xl bg-white/[0.015] border border-white/[0.03] sm:border-transparent hover:border-white/[0.06] transition-all duration-300"
          >
            <AnimatedCounter target={item.number} suffix={item.suffix} />
            <span className="text-[11px] sm:text-xs uppercase tracking-[0.16em] sm:tracking-[0.2em] text-[#a1a1aa] font-light max-w-[170px] sm:max-w-[200px] leading-relaxed">
              {item.label}
            </span>
          </div>
        ))}
      </div>
    </section>
  );
}