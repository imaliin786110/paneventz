"use client";

import React, { useState } from "react";
import { Send, CheckCircle2 } from "lucide-react";

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
    <section id="enquire" className="py-28 px-6 lg:px-12 bg-[#09090b] border-t border-white/5 scroll-mt-20">
      <div className="max-w-4xl mx-auto">
        <div className="text-center mb-16">
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
            RESERVE YOUR CELEBRATION
          </span>
          <h2 className="font-serif text-4xl sm:text-6xl text-[#f5f5f7] font-light mb-4">
            Let's Tell Your Story
          </h2>
          <p className="text-[#a1a1aa] text-sm max-w-md mx-auto font-light">
            We accept a limited number of weddings each season to ensure masterclass attention to detail.
          </p>
        </div>

        {status === "success" ? (
          <div className="bg-[#121214] border border-[#c4a472]/40 rounded-3xl p-10 text-center flex flex-col items-center">
            <CheckCircle2 size={56} className="text-[#c4a472] mb-4" />
            <h3 className="font-serif text-3xl text-white font-light mb-2">Thank You</h3>
            <p className="text-[#a1a1aa] text-sm max-w-md mb-8 font-light">
              Your inquiry has been received. Our principal artist will review your date and respond within 24 hours.
            </p>
            {whatsappUrl && (
              <a
                href={whatsappUrl}
                target="_blank"
                rel="noreferrer"
                className="px-8 py-3.5 rounded-full text-xs uppercase tracking-widest bg-[#25D366] text-white font-bold hover:opacity-90 transition-opacity shadow-lg"
              >
                Connect on WhatsApp Now
              </a>
            )}
          </div>
        ) : (
          <form
            onSubmit={handleSubmit}
            className="bg-[#121214] border border-white/5 rounded-3xl p-8 sm:p-12 grid grid-cols-1 sm:grid-cols-2 gap-6 shadow-2xl"
          >
            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2 font-light">
                Couple Names *
              </label>
              <input
                type="text"
                required
                placeholder="e.g. Aditi & Kabir"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-white/20 focus:border-[#c4a472] focus:outline-none transition-colors"
              />
            </div>

            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2 font-light">
                Email Address *
              </label>
              <input
                type="email"
                required
                placeholder="you@domain.com"
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-white/20 focus:border-[#c4a472] focus:outline-none transition-colors"
              />
            </div>

            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2 font-light">
                Phone / WhatsApp Number *
              </label>
              <input
                type="tel"
                required
                placeholder="+91 98765 43210"
                value={formData.phone}
                onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-white/20 focus:border-[#c4a472] focus:outline-none transition-colors"
              />
            </div>

            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2 font-light">
                Wedding Date
              </label>
              <input
                type="date"
                value={formData.wedding_date}
                onChange={(e) => setFormData({ ...formData, wedding_date: e.target.value })}
                className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-white/20 focus:border-[#c4a472] focus:outline-none transition-colors"
              />
            </div>

            <div className="sm:col-span-2">
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2 font-light">
                Wedding Location / Venue *
              </label>
              <input
                type="text"
                required
                placeholder="e.g. The Taj Mahal Palace, Mumbai / Jagmandir Palace, Udaipur"
                value={formData.wedding_location}
                onChange={(e) => setFormData({ ...formData, wedding_location: e.target.value })}
                className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-white/20 focus:border-[#c4a472] focus:outline-none transition-colors"
              />
            </div>

            <div className="sm:col-span-2">
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2 font-light">
                Tell Us About Your Vision & Events
              </label>
              <textarea
                rows={4}
                placeholder="Tell us about your celebration, guest count, aesthetic, and how you envision your memories..."
                value={formData.message}
                onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-white/20 focus:border-[#c4a472] focus:outline-none transition-colors"
              />
            </div>

            <div className="sm:col-span-2 text-center pt-4">
              <button
                type="submit"
                disabled={status === "loading"}
                className="w-full sm:w-auto px-12 py-4 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b45309] text-[#0c0c0d] font-bold hover:scale-105 transition-all shadow-xl disabled:opacity-50"
              >
                {status === "loading" ? "Submitting Inquiry..." : "Submit Wedding Inquiry"}
              </button>
            </div>
          </form>
        )}
      </div>
    </section>
  );
}