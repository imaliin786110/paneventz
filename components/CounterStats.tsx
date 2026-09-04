import React from "react";

export default function CounterStats({ setting }: { setting: any }) {
  const stats = [
    {
      number: setting?.stat_1_number ?? 250,
      suffix: setting?.stat_1_suffix ?? "+",
      label: setting?.stat_1_label ?? "Weddings Documented",
    },
    {
      number: setting?.stat_2_number ?? 10,
      suffix: setting?.stat_2_suffix ?? "+",
      label: setting?.stat_2_label ?? "Years Experience",
    },
    {
      number: setting?.stat_3_number ?? 35,
      suffix: setting?.stat_3_suffix ?? "+",
      label: setting?.stat_3_label ?? "Royal Palaces & Destinations",
    },
    {
      number: setting?.stat_4_number ?? 100,
      suffix: setting?.stat_4_suffix ?? "%",
      label: setting?.stat_4_label ?? "Handcrafted Heirlooms",
    },
  ];

  return (
    <section className="py-20 px-6 lg:px-12 bg-[#09090b] border-b border-white/5">
      <div className="max-w-7xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 text-center">
        {stats.map((item, idx) => (
          <div key={idx} className="flex flex-col items-center">
            <span className="font-serif text-4xl sm:text-6xl lg:text-7xl text-[#c4a472] font-light tracking-tight mb-2">
              {item.number}
              <span className="text-2xl sm:text-4xl text-white/50">{item.suffix}</span>
            </span>
            <span className="text-xs uppercase tracking-widest text-[#a1a1aa] font-light max-w-[180px]">
              {item.label}
            </span>
          </div>
        ))}
      </div>
    </section>
  );
}