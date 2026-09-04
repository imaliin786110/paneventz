"use client";

import React, { useState, useRef, useCallback, useEffect } from "react";
import { Sliders } from "lucide-react";

export default function ColorGrading({ setting }: { setting: any }) {
  const [sliderPos, setSliderPos] = useState(50);
  const [isDragging, setIsDragging] = useState(false);
  const containerRef = useRef<HTMLDivElement | null>(null);

  const heading =
    setting?.color_grade_heading || "Raw Capture vs. Paneventz Signature Grade";
  const description =
    setting?.color_grade_description ||
    "Every celebration is masterfully developed with custom Paneventz color science, delicate highlight roll-offs, and authentic 35mm film warmth that stands the test of time.";

  const updatePosition = useCallback((clientX: number) => {
    if (!containerRef.current) return;
    const rect = containerRef.current.getBoundingClientRect();
    const x = clientX - rect.left;
    const percentage = Math.max(0, Math.min(100, (x / rect.width) * 100));
    setSliderPos(percentage);
  }, []);

  const handlePointerDown = (e: React.PointerEvent) => {
    setIsDragging(true);
    (e.target as HTMLElement).setPointerCapture?.(e.pointerId);
    updatePosition(e.clientX);
  };

  const handlePointerMove = (e: React.PointerEvent) => {
    if (!isDragging) return;
    updatePosition(e.clientX);
  };

  const handlePointerUp = (e: React.PointerEvent) => {
    setIsDragging(false);
    try {
      (e.target as HTMLElement).releasePointerCapture?.(e.pointerId);
    } catch {}
  };

  return (
    <section className="py-28 px-6 lg:px-12 bg-[#09090b] border-y border-white/5 select-none">
      <div className="max-w-6xl mx-auto text-center mb-16">
        <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-semibold">
          THE ART OF MASTERY
        </span>
        <h2 className="font-serif text-3xl sm:text-5xl text-white font-light mb-4">
          {heading}
        </h2>
        <p className="max-w-2xl mx-auto text-xs sm:text-sm text-zinc-400 font-light leading-relaxed">
          {description}
        </p>
      </div>

      <div
        ref={containerRef}
        onPointerDown={handlePointerDown}
        onPointerMove={handlePointerMove}
        onPointerUp={handlePointerUp}
        onPointerCancel={handlePointerUp}
        className="max-w-4xl mx-auto relative aspect-[16/10] rounded-3xl overflow-hidden border border-white/10 shadow-2xl cursor-ew-resize touch-none bg-black"
      >
        {/* Right / Paneventz Signature Graded Image (Underneath) */}
        <img
          src={setting?.color_grade_after_image || "/images/signature-color-grade.webp"}
          alt="Paneventz Signature Grade"
          className="absolute inset-0 w-full h-full object-cover pointer-events-none"
        />

        {/* Left / RAW Camera Capture Image (Clipped) */}
        <div
          className="absolute inset-0 overflow-hidden pointer-events-none"
          style={{ clipPath: `inset(0 ${100 - sliderPos}% 0 0)` }}
        >
          <img
            src={setting?.color_grade_before_image || "/images/signature-color-grade.jpg.orig"}
            alt="Raw Camera Capture"
            className="absolute inset-0 w-full h-full object-cover filter contrast-75 brightness-90 saturate-50"
          />
        </div>

        {/* Divider Bar & Handle */}
        <div
          className="absolute top-0 bottom-0 w-1 bg-[#c4a472] pointer-events-none z-20 shadow-[0_0_15px_rgba(196,164,114,0.6)]"
          style={{ left: `${sliderPos}%` }}
        >
          <div className="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-10 h-10 rounded-full bg-[#c4a472] text-black flex items-center justify-center font-bold text-xs shadow-2xl border-2 border-white/80">
            <Sliders size={16} />
          </div>
        </div>

        {/* Overlay Badges */}
        <div className="absolute top-4 left-4 bg-black/80 backdrop-blur-md text-white/90 border border-white/10 px-3.5 py-1.5 rounded-full text-[10px] uppercase tracking-wider font-semibold z-10 pointer-events-none">
          Raw Camera Capture
        </div>

        <div className="absolute top-4 right-4 bg-[#c4a472] text-black px-4 py-1.5 rounded-full text-[10px] uppercase tracking-wider font-bold z-10 shadow-lg pointer-events-none">
          Paneventz Signature Grade
        </div>

        <div className="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/70 backdrop-blur-md text-zinc-400 border border-white/10 px-4 py-1 rounded-full text-[10px] uppercase tracking-wider z-10 pointer-events-none">
          ← Drag Slider to Compare →
        </div>
      </div>
    </section>
  );
}