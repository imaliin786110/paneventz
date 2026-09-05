"use client";

import React, { useState } from "react";
import { Send, CheckCircle2, ShieldCheck } from "lucide-react";

export default function EnquiryForm() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    wedding_date: "",
    wedding_location: "",
    service: "Full Wedding Photography & Cinema",
    message: "",
  });

  const [status, setStatus] = useState<"idle" | "loading" | "success" | "error">("idle");
  const [whatsappUrl, setWhatsappUrl] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setStatus("loading");

    try {
      const res = await fetch("/api/enquire", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });

      const data = await res.json();
      if (res.ok) {
        setStatus("success");
        if (data.whatsapp_url) {
          setWhatsappUrl(data.whatsapp_url);
        }
      } else {
        setStatus("error");
      }
    } catch (err) {
      console.error(err);
      setStatus("error");
    }
  };

  return (
    <section
      id="enquire"
      className="relative py-24 sm:py-32 pb-32 sm:pb-36 px-4 sm:px-6 lg:px-12 bg-[#08080a] border-t border-white/[0.08] scroll-mt-20 overflow-hidden"
    >
      {/* AMBIENT LUXURY WARM GLOW BACKDROP */}
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-5xl h-96 bg-[radial-gradient(ellipse_60%_50%_at_50%_0%,rgba(196,164,114,0.14),transparent_70%)] pointer-events-none" />

      <div className="relative max-w-4xl mx-auto w-full">
        {/* EMOTIONAL INTRODUCTION ABOVE FORM */}
        <div className="text-center mb-10 sm:mb-14">
          <div className="flex items-center justify-center gap-3 mb-4">
            <span className="h-[1px] w-8 sm:w-12 bg-gradient-to-r from-transparent to-[#c4a472]/60" />
            <span className="text-[11px] sm:text-xs uppercase tracking-[0.35em] text-[#c4a472] font-semibold">
              YOUR STORY BEGINS HERE
            </span>
            <span className="h-[1px] w-8 sm:w-12 bg-gradient-to-l from-transparent to-[#c4a472]/60" />
          </div>

          <h2 className="font-serif text-3xl sm:text-5xl md:text-6xl text-white font-light tracking-tight leading-[1.12] mb-4 max-w-2xl mx-auto">
            Let&apos;s Create Something <span className="italic font-serif font-normal text-[#f5ebd7]">Timeless.</span>
          </h2>

          <p className="text-sm sm:text-base text-[#cbd5e1] font-light leading-relaxed max-w-xl mx-auto">
            Tell us a little about your celebration, and let&apos;s begin planning how your story should be captured.
          </p>
        </div>

        {status === "success" ? (
          <div className="relative bg-gradient-to-b from-[#151720]/98 via-[#101218]/98 to-[#0b0c10]/98 border border-[#c4a472]/40 rounded-2xl sm:rounded-3xl p-8 sm:p-14 text-center flex flex-col items-center shadow-[0_20px_60px_-15px_rgba(0,0,0,0.85),0_0_35px_rgba(196,164,114,0.08)]">
            <div className="w-16 h-16 rounded-full bg-[#c4a472]/15 border border-[#c4a472]/40 flex items-center justify-center text-[#c4a472] mb-6 shadow-lg shadow-[#c4a472]/10">
              <CheckCircle2 size={36} className="text-[#c4a472]" />
            </div>
            <span className="text-[11px] uppercase tracking-[0.3em] text-[#c4a472] font-semibold mb-2">
              INQUIRY RECEIVED
            </span>
            <h3 className="font-serif text-3xl sm:text-4xl text-white font-light mb-3">Thank You</h3>
            <p className="text-[#cbd5e1] text-sm max-w-md mb-8 font-light leading-relaxed">
              Your celebration inquiry has been received. Our principal artist will review your dates and connect with you within 24 hours.
            </p>
            {whatsappUrl && (
              <a
                href={whatsappUrl}
                target="_blank"
                rel="noreferrer"
                className="inline-flex items-center gap-2 px-8 py-3.5 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#20ba5a] to-[#25D366] text-white font-bold hover:brightness-110 transition-all shadow-[0_10px_25px_rgba(37,211,102,0.3)] hover:-translate-y-0.5"
              >
                <span>Connect on WhatsApp Now</span>
                <span>↗</span>
              </a>
            )}
          </div>
        ) : (
          <form
            onSubmit={handleSubmit}
            className="group relative bg-gradient-to-b from-[#151720]/98 via-[#101218]/98 to-[#0b0c10]/98 border border-[#c4a472]/30 hover:border-[#c4a472]/45 rounded-2xl sm:rounded-3xl p-5 sm:p-10 md:p-12 grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.9),0_0_35px_rgba(196,164,114,0.08)] backdrop-blur-xl transition-all duration-500 w-full max-w-full overflow-hidden"
          >
            {/* CORNER ACCENT GLOWS */}
            <div className="absolute top-0 right-0 w-44 h-44 bg-[radial-gradient(circle_at_top_right,rgba(196,164,114,0.1),transparent_70%)] pointer-events-none rounded-tr-2xl sm:rounded-tr-3xl" />
            <div className="absolute bottom-0 left-0 w-44 h-44 bg-[radial-gradient(circle_at_bottom_left,rgba(196,164,114,0.06),transparent_70%)] pointer-events-none rounded-bl-2xl sm:rounded-bl-3xl" />

            {/* FIELD 1: COUPLE NAMES */}
            <div className="w-full min-w-0 max-w-full">
              <label className="block text-[11px] sm:text-xs uppercase tracking-[0.2em] text-[#e2d7c5] mb-2 font-medium">
                Couple Names <span className="text-[#c4a472]">*</span>
              </label>
              <input
                type="text"
                required
                placeholder="e.g. Aditi & Kabir"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                className="w-full max-w-full min-w-0 box-border block bg-[#181a22]/90 hover:bg-[#1b1e27] focus:bg-[#1d202a] border border-white/[0.14] hover:border-[#c4a472]/40 focus:border-[#c4a472] focus:ring-1 focus:ring-[#c4a472]/40 rounded-xl px-4 py-3.5 text-sm text-white placeholder-zinc-400 focus:outline-none transition-all duration-200"
              />
            </div>

            {/* FIELD 2: EMAIL ADDRESS */}
            <div className="w-full min-w-0 max-w-full">
              <label className="block text-[11px] sm:text-xs uppercase tracking-[0.2em] text-[#e2d7c5] mb-2 font-medium">
                Email Address <span className="text-[#c4a472]">*</span>
              </label>
              <input
                type="email"
                required
                placeholder="you@domain.com"
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                className="w-full max-w-full min-w-0 box-border block bg-[#181a22]/90 hover:bg-[#1b1e27] focus:bg-[#1d202a] border border-white/[0.14] hover:border-[#c4a472]/40 focus:border-[#c4a472] focus:ring-1 focus:ring-[#c4a472]/40 rounded-xl px-4 py-3.5 text-sm text-white placeholder-zinc-400 focus:outline-none transition-all duration-200"
              />
            </div>

            {/* FIELD 3: PHONE / WHATSAPP NUMBER */}
            <div className="w-full min-w-0 max-w-full">
              <label className="block text-[11px] sm:text-xs uppercase tracking-[0.2em] text-[#e2d7c5] mb-2 font-medium">
                Phone / WhatsApp Number <span className="text-[#c4a472]">*</span>
              </label>
              <input
                type="tel"
                required
                placeholder="+91 98765 43210"
                value={formData.phone}
                onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                className="w-full max-w-full min-w-0 box-border block bg-[#181a22]/90 hover:bg-[#1b1e27] focus:bg-[#1d202a] border border-white/[0.14] hover:border-[#c4a472]/40 focus:border-[#c4a472] focus:ring-1 focus:ring-[#c4a472]/40 rounded-xl px-4 py-3.5 text-sm text-white placeholder-zinc-400 focus:outline-none transition-all duration-200"
              />
            </div>

            {/* FIELD 4: WEDDING DATE (FLUSH RESPONSIVE ALIGNMENT) */}
            <div className="w-full min-w-0 max-w-full">
              <label className="block text-[11px] sm:text-xs uppercase tracking-[0.2em] text-[#e2d7c5] mb-2 font-medium">
                Wedding Date
              </label>
              <input
                type="date"
                value={formData.wedding_date}
                onChange={(e) => setFormData({ ...formData, wedding_date: e.target.value })}
                className="w-full max-w-full min-w-0 box-border block bg-[#181a22]/90 hover:bg-[#1b1e27] focus:bg-[#1d202a] border border-white/[0.14] hover:border-[#c4a472]/40 focus:border-[#c4a472] focus:ring-1 focus:ring-[#c4a472]/40 rounded-xl px-4 py-3.5 text-sm text-white placeholder-zinc-400 focus:outline-none transition-all duration-200 [color-scheme:dark] [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:opacity-80 [&::-webkit-calendar-picker-indicator]:hover:opacity-100"
              />
            </div>

            {/* FIELD 5: WEDDING LOCATION / VENUE */}
            <div className="sm:col-span-2 w-full min-w-0 max-w-full">
              <label className="block text-[11px] sm:text-xs uppercase tracking-[0.2em] text-[#e2d7c5] mb-2 font-medium">
                Wedding Location / Venue <span className="text-[#c4a472]">*</span>
              </label>
              <input
                type="text"
                required
                placeholder="e.g. The Taj Mahal Palace, Mumbai / Jagmandir Palace, Udaipur"
                value={formData.wedding_location}
                onChange={(e) => setFormData({ ...formData, wedding_location: e.target.value })}
                className="w-full max-w-full min-w-0 box-border block bg-[#181a22]/90 hover:bg-[#1b1e27] focus:bg-[#1d202a] border border-white/[0.14] hover:border-[#c4a472]/40 focus:border-[#c4a472] focus:ring-1 focus:ring-[#c4a472]/40 rounded-xl px-4 py-3.5 text-sm text-white placeholder-zinc-400 focus:outline-none transition-all duration-200"
              />
            </div>

            {/* FIELD 6: VISION & EVENTS */}
            <div className="sm:col-span-2 w-full min-w-0 max-w-full">
              <label className="block text-[11px] sm:text-xs uppercase tracking-[0.2em] text-[#e2d7c5] mb-2 font-medium">
                Tell Us About Your Vision & Events
              </label>
              <textarea
                rows={4}
                placeholder="Tell us about your celebration, guest count, aesthetic, and how you envision your memories..."
                value={formData.message}
                onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                className="w-full max-w-full min-w-0 box-border block bg-[#181a22]/90 hover:bg-[#1b1e27] focus:bg-[#1d202a] border border-white/[0.14] hover:border-[#c4a472]/40 focus:border-[#c4a472] focus:ring-1 focus:ring-[#c4a472]/40 rounded-xl px-4 py-3.5 text-sm text-white placeholder-zinc-400 focus:outline-none transition-all duration-200 resize-none"
              />
            </div>

            {/* ERROR NOTICE */}
            {status === "error" && (
              <div className="sm:col-span-2 p-3.5 bg-red-950/40 border border-red-500/30 rounded-xl text-center text-xs text-red-300">
                An error occurred while submitting your inquiry. Please try again or message us directly on WhatsApp.
              </div>
            )}

            {/* SUBMIT BUTTON & REASSURANCE */}
            <div className="sm:col-span-2 text-center pt-2 sm:pt-4 w-full min-w-0 max-w-full">
              <button
                type="submit"
                disabled={status === "loading"}
                className="group w-full sm:w-auto inline-flex items-center justify-center gap-3 px-10 sm:px-14 py-4 rounded-full text-xs uppercase tracking-[0.25em] bg-gradient-to-r from-[#d8b886] via-[#c4a472] to-[#b38a4c] hover:from-[#e2c79b] hover:via-[#d8b886] hover:to-[#c4a472] text-[#09090b] font-bold shadow-[0_10px_30px_rgba(196,164,114,0.3)] hover:shadow-[0_15px_40px_rgba(196,164,114,0.45)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 disabled:opacity-50"
              >
                <span>{status === "loading" ? "Submitting Inquiry..." : "Submit Wedding Inquiry"}</span>
                <Send size={14} className="group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </button>

              <p className="text-[11.5px] sm:text-xs text-[#94a3b8] font-light tracking-wide mt-4 flex items-center justify-center gap-1.5">
                <ShieldCheck size={14} className="text-[#c4a472]/80 shrink-0" />
                <span>Your details are kept private and used only to plan your consultation.</span>
              </p>
            </div>
          </form>
        )}
      </div>
    </section>
  );
}