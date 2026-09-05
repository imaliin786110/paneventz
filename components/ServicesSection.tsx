import React from "react";
import Link from "next/link";
import { Check } from "lucide-react";
import { formatIndianCurrency } from "@/lib/utils";

export default function ServicesSection({ services }: { services: any[] }) {
  if (!services || services.length === 0) return null;

  return (
    <section className="py-28 px-6 lg:px-12 bg-[#0c0c0d]">
      <div className="max-w-7xl mx-auto">
        <div className="text-center max-w-3xl mx-auto mb-20">
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
            CURATED EXPERIENCES
          </span>
          <h2 className="font-serif text-4xl sm:text-6xl text-[#f5f5f7] font-light mb-6">
            Bespoke Wedding Packages
          </h2>
          <p className="text-[#a1a1aa] text-sm sm:text-base font-light leading-relaxed">
            Every celebration is treated as a fine art editorial piece. Select a curated collection or request a tailored commission.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {services.map((srv, idx) => (
            <div
              key={srv.id || idx}
              className={`rounded-3xl p-8 lg:p-10 flex flex-col justify-between border transition-all duration-300 ${
                idx === 1
                  ? "bg-[#141417] border-[#c4a472] shadow-2xl relative shadow-[#c4a472]/10"
                  : "bg-[#121214] border-white/5 hover:border-white/20"
              }`}
            >
              {idx === 1 && (
                <span className="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#c4a472] text-[#0c0c0d] px-4 py-1 rounded-full text-[10px] uppercase tracking-widest font-bold shadow-lg">
                  Most Popular
                </span>
              )}
              <div>
                <h3 className="font-serif text-3xl text-[#f5f5f7] font-light mb-2">
                  {srv.name}
                </h3>
                {srv.short_description && (
                  <p className="text-xs text-[#c4a472] font-light uppercase tracking-wider mb-6">
                    {srv.short_description}
                  </p>
                )}
                <div className="mb-8">
                  <span className="text-xs text-[#a1a1aa] uppercase tracking-wider block mb-1">
                    Investment Starting From
                  </span>
                  <span className="font-serif text-4xl text-[#f5f5f7] font-light">
                    {formatIndianCurrency(srv.price_from)}
                  </span>
                </div>
                {srv.description && (
                  <p className="text-[#a1a1aa] text-xs font-light leading-relaxed mb-8">
                    {srv.description}
                  </p>
                )}
              </div>

              <Link
                href="#enquire"
                className={`w-full py-3.5 rounded-full text-xs uppercase tracking-widest text-center font-semibold transition-all ${
                  idx === 1
                    ? "bg-[#c4a472] text-[#0c0c0d] hover:bg-[#b45309]"
                    : "border border-white/20 text-[#f5f5f7] hover:border-[#c4a472] hover:text-[#c4a472]"
                }`}
              >
                Inquire Collection
              </Link>
            </div>
          ))}
        </div>

        <div className="text-center mt-12">
          <Link
            href="/services"
            className="inline-flex items-center gap-2 text-xs uppercase tracking-[0.25em] text-[#c4a472] hover:text-white transition-colors border-b border-[#c4a472]/40 pb-1"
          >
            <span>Explore Detailed Investment Collections &amp; Deliverables</span>
            <span>→</span>
          </Link>
        </div>
      </div>
    </section>
  );
}