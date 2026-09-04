"use client";

import React, { useState } from "react";

export default function ColorGrading({ setting }: { setting: any }) {
  const [sliderPos, setSliderPos] = useState(50);
  const heading = setting?.color_grade_heading || "Raw Capture vs. Signature Grade";
  const description =
    setting?.color_grade_description ||
    "Every celebration is masterfully developed with custom color matrices, delicate highlight roll-offs, and authentic 35mm film emulation that stands the test of time.";

  return (
    <section className="py-28 px-6 lg:px-12 bg-[#09090b] border-y border-white/5">
      <div className="max-w-6xl mx-auto text-center mb-16">
        <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
          THE ART OF MASTERY
        </span>
        <h2 className="font-serif text-3xl sm:text-5xl text-[#f5f5f7] font-light mb-6">
          {heading}
        </h2>
        <p className="max-w-2xl mx-auto text-sm sm:text-base text-[#a1a1aa] font-light leading-relaxed">
          {description}
        </p>
      </div>

      <div className="max-w-4xl mx-auto relative aspect-[16/10] rounded-2xl overflow-hidden border border-white/10 shadow-2xl select-none">
        {/* After / Graded Image */}
        <img
          src={setting?.color_grade_after_image || "/images/signature-color-grade.webp"}
          alt="Signature Color Grade"
          className="absolute inset-0 w-full h-full object-cover"
        />

        {/* Before / Raw Image with Clip Path */}
        <div
          className="absolute inset-0 overflow-hidden"
          style={{ clipPath: `inset(0 ${100 - sliderPos}% 0 0)` }}
        >
          <img
            src={setting?.color_grade_before_image || "/images/signature-color-grade.jpg.orig"}
            alt="Raw Camera Capture"
            className="absolute inset-0 w-full h-full object-cover filter contrast-75 brightness-90 saturate-50"
          />
        </div>

        {/* Slider Divider Line */}
        <div
          className="absolute top-0 bottom-0 w-0.5 bg-[#c4a472] cursor-ew-resize z-20"
          style={{ left: `${sliderPos}%` }}
        >
          <div className="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-8 h-8 rounded-full bg-[#c4a472] text-[#0c0c0d] flex items-center justify-center font-bold text-xs shadow-lg">
            ↔
          </div>
        </div>

        {/* Overlay Badges */}
        <span className="absolute bottom-4 left-4 bg-black/60 backdrop-blur-sm text-white/80 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-medium z-10">
          Raw Log Capture
        </span>
        <span className="absolute bottom-4 right-4 bg-[#c4a472] text-[#0c0c0d] px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-bold z-10 shadow-lg">
          Signature Film Grade
        </span>

        {/* Hidden Range Input for Accessibility & Touch */}
        <input
          type="range"
          min="0"
          max="100"
          value={sliderPos}
          onChange={(e) => setSliderPos(Number(e.target.value))}
          className="absolute inset-0 w-full h-full opacity-0 cursor-ew-resize z-30"
          aria-label="Color Grading Comparison Slider"
        />
      </div>
    </section>
  );
}