import React from "react";
import { Star } from "lucide-react";

export default function TestimonialsSection({ testimonials }: { testimonials: any[] }) {
  if (!testimonials || testimonials.length === 0) return null;

  return (
    <section className="py-28 px-6 lg:px-12 bg-[#09090b] border-t border-white/5">
      <div className="max-w-6xl mx-auto">
        <div className="text-center mb-16">
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
            WORDS OF PRAISE
          </span>
          <h2 className="font-serif text-4xl sm:text-5xl text-[#f5f5f7] font-light">
            Kind Words From Our Couples
          </h2>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {testimonials.map((t, idx) => (
            <div
              key={t.id || idx}
              className="bg-[#121214] border border-white/5 p-8 lg:p-10 rounded-2xl flex flex-col justify-between"
            >
              <div>
                <div className="flex items-center gap-1 text-[#f59e0b] mb-6">
                  {[...Array(t.rating || 5)].map((_, i) => (
                    <Star key={i} size={16} className="fill-current" />
                  ))}
                </div>
                <p className="font-serif text-lg sm:text-xl text-[#f5f5f7]/90 font-light leading-relaxed italic mb-8">
                  "{t.review}"
                </p>
              </div>

              <div className="flex items-center gap-4 pt-6 border-t border-white/5">
                {t.avatar && (
                  <img
                    src={t.avatar.startsWith("http") || t.avatar.startsWith("/") ? t.avatar : `/storage/${t.avatar}`}
                    alt={`${t.couple_name} - Paneventz Wedding Review`}
                    loading="lazy"
                    decoding="async"
                    className="w-12 h-12 rounded-full object-cover border border-[#c4a472]/30"
                  />
                )}
                <div>
                  <h3 className="font-serif text-xl text-[#f5f5f7] font-normal">
                    {t.couple_name}
                  </h3>
                  <span className="text-[11px] uppercase tracking-widest text-[#c4a472] font-light">
                    {t.location || "Destination Wedding"}
                  </span>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}