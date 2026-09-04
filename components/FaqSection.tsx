"use client";

import React, { useState } from "react";
import { ChevronDown } from "lucide-react";

export default function FaqSection({ faqs }: { faqs: any[] }) {
  const [openIdx, setOpenIdx] = useState<number | null>(0);

  if (!faqs || faqs.length === 0) return null;

  return (
    <section className="py-28 px-6 lg:px-12 bg-[#0c0c0d]">
      <div className="max-w-4xl mx-auto">
        <div className="text-center mb-16">
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
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
                className="bg-[#121214] border border-white/5 rounded-2xl overflow-hidden transition-colors"
              >
                <button
                  onClick={() => setOpenIdx(isOpen ? null : idx)}
                  className="w-full p-6 text-left flex items-center justify-between gap-4 font-serif text-xl text-[#f5f5f7] font-light hover:text-[#c4a472] transition-colors"
                >
                  <span>{faq.question}</span>
                  <ChevronDown
                    size={20}
                    className={`text-[#c4a472] transition-transform duration-300 ${
                      isOpen ? "rotate-180" : ""
                    }`}
                  />
                </button>
                {isOpen && (
                  <div className="px-6 pb-6 text-xs sm:text-sm text-[#a1a1aa] font-light leading-relaxed border-t border-white/5 pt-4">
                    {faq.answer}
                  </div>
                )}
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}