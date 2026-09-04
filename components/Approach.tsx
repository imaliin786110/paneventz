import React from "react";
import { Camera, Film, HeartHandshake } from "lucide-react";

export default function Approach({ setting }: { setting: any }) {
  const eyebrow = setting?.about_eyebrow || "THE PAN EVENTZ APPROACH";
  const heading = setting?.about_heading || "Your wedding deserves more than photographs.";
  const description =
    setting?.about_description ||
    "It deserves to be remembered exactly as it felt. The laughter. The tears. The quiet glances. The people you love.";

  const pillars = [
    {
      num: "01",
      title: "Natural & Candid",
      subtitle: "Unscripted Emotion",
      desc: "No forced smiles or awkward poses. We blend seamlessly into your celebration so you stay present with your guests while we document authentic moments as they unfold.",
      icon: Camera,
    },
    {
      num: "02",
      title: "Editorial & Cinematic",
      subtitle: "Masterclass Aesthetics",
      desc: "Every frame is treated with delicate fine-art color grading, authentic 35mm film emulation, and rich dynamic lighting that looks magnificent today and 50 years from now.",
      icon: Film,
    },
    {
      num: "03",
      title: "Effortless Experience",
      subtitle: "Full Peace of Mind",
      desc: "From your initial design consultation to private VIP gallery delivery, our crew provides meticulous coordination, timeline planning, and rapid highlight previews.",
      icon: HeartHandshake,
    },
  ];

  return (
    <section className="py-28 px-6 lg:px-12 bg-[#0c0c0d] text-center relative border-y border-white/5" id="about">
      <div className="max-w-4xl mx-auto mb-20">
        <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-4 block font-light">
          {eyebrow}
        </span>
        <h2 className="font-serif text-3xl sm:text-5xl lg:text-6xl text-[#f5f5f7] font-light leading-tight mb-6">
          {heading}
        </h2>
        <div className="font-serif text-xl sm:text-2xl text-[#c4a472] italic font-normal mb-8">
          It deserves to be remembered exactly as it felt.
        </div>
        <p className="text-base sm:text-lg text-[#a1a1aa] font-light leading-relaxed max-w-2xl mx-auto">
          {description}
        </p>
      </div>

      <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
        {pillars.map((p, idx) => {
          const Icon = p.icon;
          return (
            <div
              key={idx}
              className="bg-[#121214]/80 border border-white/5 rounded-3xl p-8 lg:p-10 flex flex-col justify-between hover:border-[#c4a472]/30 transition-all duration-300 shadow-xl"
            >
              <div>
                <div className="flex items-center justify-between mb-8">
                  <span className="font-serif text-4xl text-[#c4a472]/40 font-light">{p.num}</span>
                  <div className="w-12 h-12 rounded-2xl bg-[#c4a472]/10 text-[#c4a472] flex items-center justify-center border border-[#c4a472]/20">
                    <Icon size={20} />
                  </div>
                </div>
                <h3 className="font-serif text-2xl sm:text-3xl text-[#f5f5f7] font-light mb-1">
                  {p.title}
                </h3>
                <span className="text-xs uppercase tracking-widest text-[#c4a472] font-semibold block mb-4">
                  {p.subtitle}
                </span>
                <p className="text-xs sm:text-sm text-[#a1a1aa] font-light leading-relaxed">
                  {p.desc}
                </p>
              </div>
            </div>
          );
        })}
      </div>
    </section>
  );
}