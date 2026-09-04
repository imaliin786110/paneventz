import React from "react";
import { Camera, Film, Sparkles } from "lucide-react";

export default function Approach({ setting }: { setting: any }) {
  const eyebrow = setting?.about_eyebrow || "THE PAN EVENTZ APPROACH";
  const heading = setting?.about_heading || "Your wedding deserves more than photographs.";

  const pillars = [
    {
      num: "01",
      tag: "01 — NATURAL & CANDID",
      title: "Let the moments happen naturally.",
      desc1: "We believe the best photographs aren't always posed.",
      desc2: "We stay in the background, capturing genuine emotions as they unfold — without interrupting the moment.",
      bottomTag: "Less posing. More living.",
      icon: Camera,
    },
    {
      num: "02",
      tag: "02 — TIMELESS & CINEMATIC",
      title: "Every frame, made to be felt.",
      desc1: "Natural skin tones, elegant colours and thoughtful storytelling come together to create photographs and films that feel as beautiful years from now as they do today.",
      desc2: "Masterfully color-graded with authentic film warmth.",
      bottomTag: "Beautiful today. Timeless tomorrow.",
      icon: Film,
    },
    {
      num: "03",
      tag: "03 — MADE TO LAST",
      title: "Memories worth passing on.",
      desc1: "From high-resolution photographs to cinematic 4K films, we preserve the moments you'll want to relive — and share with the generations that follow.",
      desc2: "Handcrafted heirlooms delivered in master resolution.",
      bottomTag: "Today's moments. Tomorrow's memories.",
      icon: Sparkles,
    },
  ];

  return (
    <section className="py-28 px-6 lg:px-12 bg-[#09090b] text-center relative border-y border-white/5" id="about">
      <div className="max-w-4xl mx-auto mb-16">
        <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-4 block font-semibold">
          {eyebrow}
        </span>
        <h2 className="font-serif text-3xl sm:text-5xl lg:text-6xl text-white font-light leading-tight mb-4">
          {heading}
        </h2>
        <div className="font-serif text-xl sm:text-2xl text-[#c4a472] italic font-normal mb-6">
          It deserves to be remembered exactly as it felt.
        </div>
        <p className="text-sm sm:text-base text-zinc-300 font-light leading-relaxed max-w-2xl mx-auto mb-2">
          The laughter. The tears. The quiet glances. The people you love.
        </p>
        <p className="text-xs sm:text-sm text-zinc-400 font-light leading-relaxed max-w-2xl mx-auto">
          At <strong className="text-white font-semibold">Pan Eventz</strong>, we turn these fleeting moments into photographs and films you'll want to relive for years to come.
        </p>
      </div>

      <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
        {pillars.map((p, idx) => {
          const Icon = p.icon;
          return (
            <div
              key={idx}
              className="bg-[#121214] border border-white/10 rounded-3xl p-8 lg:p-10 flex flex-col justify-between hover:border-[#c4a472]/40 transition-all duration-300 shadow-xl"
            >
              <div>
                <div className="flex items-center justify-between mb-6 pb-4 border-b border-[#c4a472]/20">
                  <span className="text-xs uppercase tracking-widest text-[#c4a472] font-semibold">
                    {p.tag}
                  </span>
                  <div className="w-10 h-10 rounded-xl bg-[#c4a472]/10 text-[#c4a472] flex items-center justify-center border border-[#c4a472]/20">
                    <Icon size={18} />
                  </div>
                </div>

                <h3 className="font-serif text-2xl text-white font-light mb-4 leading-snug">
                  {p.title}
                </h3>

                <p className="text-xs sm:text-sm text-zinc-300 leading-relaxed mb-3">
                  {p.desc1}
                </p>
                <p className="text-xs sm:text-sm text-zinc-400 leading-relaxed mb-6">
                  {p.desc2}
                </p>
              </div>

              <div className="pt-4 border-t border-white/5 text-xs font-semibold text-[#c4a472] tracking-wider">
                {p.bottomTag}
              </div>
            </div>
          );
        })}
      </div>
    </section>
  );
}