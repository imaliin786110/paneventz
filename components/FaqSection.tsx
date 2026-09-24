"use client";

import React, { useState } from "react";
import { ChevronDown } from "lucide-react";

export default function FaqSection({ faqs }: { faqs: any[] }) {
  const [openIdx, setOpenIdx] = useState<number | null>(0);

  if (!faqs || faqs.length === 0) return null;

  return (
    <section className="py-28 px-6 lg:px-12 bg-[#0c0c0d] relative overflow-hidden" id="faqs">
      {/* AMBIENT LIVING LIGHT */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[radial-gradient(circle,rgba(196,164,114,0.06),transparent_70%)] pointer-events-none animate-ambient-glow" />

      <div className="relative z-10 max-w-4xl mx-auto">
        <div className="text-center mb-16">
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-semibold animate-pulse [animation-duration:6s]">
            COMMON INQUIRIES
          </span>
          <h2 className="font-serif text-4xl sm:text-5xl text-[#f5f5f7] font-light">
            Frequently Asked Questions
          </h2>
        </div>

        <div className="space-y-4">
          {faqs.map((faq, idx) => {
            const isOpen = openIdx === idx;
            return (
              <div
                key={faq.id || idx}
                className={`bg-[#121214] border rounded-2xl overflow-hidden transition-all duration-300 ${
                  isOpen ? "border-[#c4a472]/40 shadow-[0_4px_20px_rgba(196,164,114,0.08)]" : "border-white/5 hover:border-white/20"
                }`}
              >
                <button
                  onClick={() => setOpenIdx(isOpen ? null : idx)}
                  className="w-full p-6 text-left flex items-center justify-between gap-4 font-serif text-xl text-[#f5f5f7] font-light hover:text-[#c4a472] transition-colors"
                >
                  <span>{faq.question}</span>
                  <div className={`w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-300 ${
                    isOpen ? "bg-[#c4a472]/15 text-[#c4a472]" : "text-[#c4a472]/60"
                  }`}>
                    <ChevronDown
                      size={18}
                      className={`transition-transform duration-300 ${
                        isOpen ? "rotate-180 text-[#c4a472]" : ""
                      }`}
                    />
                  </div>
                </button>
                <div
                  className={`grid transition-all duration-300 ease-in-out ${
                    isOpen ? "grid-rows-[1fr] opacity-100" : "grid-rows-[0fr] opacity-0"
                  }`}
                >
                  <div className="overflow-hidden">
                    <div className="px-6 pb-6 text-xs sm:text-sm text-[#a1a1aa] font-light leading-relaxed border-t border-white/5 pt-4">
                      {faq.answer}
                    </div>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}