import React from "react";
import Link from "next/link";
import { Phone, MessageSquare, Mail, Sparkles, MapPin, ArrowRight } from "lucide-react";

export default function Footer({ setting }: { setting: any }) {
  const year = new Date().getFullYear();

  const phone1 = setting?.phone || "+91 80820 24787";
  const phone2 = "+91 98213 37523";
  const whatsappNumber = setting?.whatsapp ? setting.whatsapp.replace(/[^0-9]/g, "") : "918082024787";
  const email = setting?.email || "imaliinmirza@gmail.com";
  const whatsappUrl = `https://wa.me/${whatsappNumber}?text=Hello%20Paneventz!%20I%20would%20like%20to%20inquire%20about%20your%20wedding%20photography%20%26%20films.`;

  return (
    <footer id="contact" className="bg-[#080809] border-t border-white/10 pt-28 pb-16 px-6 lg:px-12 text-center text-[#8e8e93]">
      <div className="max-w-6xl mx-auto">
        {/* EYEBROW */}
        <span className="text-xs uppercase tracking-[4px] text-[#c4a472] font-semibold block mb-4">
          LET&apos;S CONNECT
        </span>

        {/* HEADING */}
        <h2 className="font-serif text-4xl sm:text-5xl md:text-7xl font-light text-white tracking-tight leading-tight mb-6">
          {setting?.footer_heading || "Let's create something timeless."}
        </h2>

        {/* SUBTITLE */}
        <p className="max-w-2xl mx-auto text-sm sm:text-base text-[#cbd5e1] font-light leading-relaxed mb-8">
          {setting?.footer_description ||
            "Now reserving select dates for wedding celebrations across India and destinations worldwide."}
        </p>

        {/* PAN-INDIA & MUMBAI LOCATION BADGE */}
        <div className="inline-flex items-center gap-2 bg-[#c4a472]/10 border border-[#c4a472]/30 px-6 py-2 rounded-full mb-12 text-[11px] sm:text-xs tracking-[2px] uppercase text-[#c4a472] font-medium shadow-sm">
          <span>📍</span>
          <span>{setting?.footer_address || "Based in Mumbai · Services Available Pan-India & Worldwide"}</span>
        </div>

        {/* LUXURY CONTACT CARDS HUB (3 COLUMNS) */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 text-left mb-12">
          {/* CARD 1: DIRECT CALLS */}
          <div className="bg-gradient-to-b from-[#101622]/90 to-[#0a0e16]/95 border border-[#c4a472]/25 hover:border-[#c4a472]/60 rounded-xl p-7 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#c4a472]/10">
            <div>
              <div className="text-[11px] tracking-[2.5px] uppercase text-[#c4a472] font-bold flex items-center gap-2 mb-4">
                <span>📞</span> Direct Studio Lines
              </div>
              <div className="flex flex-col gap-2.5">
                <a
                  href={`tel:${phone1.replace(/\s+/g, "")}`}
                  className="text-white hover:text-[#fce7b2] text-base font-semibold tracking-wider flex items-center gap-3 py-1 transition-colors"
                >
                  <span className="w-8 h-8 rounded-full bg-[#c4a472]/15 border border-[#c4a472]/40 flex items-center justify-center text-[#c4a472] text-xs flex-shrink-0">
                    ✦
                  </span>
                  <span>{phone1}</span>
                </a>
                <a
                  href={`tel:${phone2.replace(/\s+/g, "")}`}
                  className="text-white hover:text-[#fce7b2] text-base font-semibold tracking-wider flex items-center gap-3 py-1 transition-colors"
                >
                  <span className="w-8 h-8 rounded-full bg-[#c4a472]/15 border border-[#c4a472]/40 flex items-center justify-center text-[#c4a472] text-xs flex-shrink-0">
                    ✦
                  </span>
                  <span>{phone2}</span>
                </a>
              </div>
            </div>
            <div className="text-[11.5px] tracking-wide text-[#94a3b8] mt-5 pt-3.5 border-t border-white/5">
              Available 9:00 AM – 9:00 PM IST · Direct Concierge
            </div>
          </div>

          {/* CARD 2: WHATSAPP */}
          <div className="bg-gradient-to-b from-[#101622]/90 to-[#0a0e16]/95 border border-[#25D366]/25 hover:border-[#25D366]/60 rounded-xl p-7 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#25D366]/10">
            <div>
              <div className="text-[11px] tracking-[2.5px] uppercase text-[#25D366] font-bold flex items-center gap-2 mb-4">
                <span>💬</span> WhatsApp Concierge
              </div>
              <div className="mt-2">
                <a
                  href={whatsappUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center gap-2.5 bg-[#25D366]/15 hover:bg-[#25D366]/25 border border-[#25D366]/40 text-[#25D366] px-5 py-3 rounded-lg font-semibold text-sm tracking-wide transition-all duration-300"
                >
                  <MessageSquare size={16} />
                  <span>Chat On WhatsApp ↗</span>
                </a>
              </div>
            </div>
            <div className="text-[11.5px] tracking-wide text-[#94a3b8] mt-5 pt-3.5 border-t border-white/5">
              Fastest Response · Dates &amp; Brochure Inquiries
            </div>
          </div>

          {/* CARD 3: STUDIO CORRESPONDENCE / EMAIL */}
          <div className="bg-gradient-to-b from-[#101622]/90 to-[#0a0e16]/95 border border-[#38bdf8]/25 hover:border-[#38bdf8]/60 rounded-xl p-7 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#38bdf8]/10">
            <div>
              <div className="text-[11px] tracking-[2.5px] uppercase text-[#38bdf8] font-bold flex items-center gap-2 mb-4">
                <span>✉</span> Studio Correspondence
              </div>
              <div className="mt-2">
                <a
                  href={`mailto:${email}`}
                  className="text-white hover:text-[#c4a472] text-sm sm:text-base font-semibold tracking-wide inline-flex items-center gap-3 py-1 transition-colors"
                >
                  <span className="w-8 h-8 rounded-full bg-[#38bdf8]/15 border border-[#38bdf8]/40 flex items-center justify-center text-[#38bdf8] text-xs flex-shrink-0">
                    <Mail size={14} />
                  </span>
                  <span className="break-all">{email}</span>
                </a>
              </div>
            </div>
            <div className="text-[11.5px] tracking-wide text-[#94a3b8] mt-5 pt-3.5 border-t border-white/5">
              Formal Wedding Proposals &amp; Commissions
            </div>
          </div>
        </div>

        {/* CORPORATE & PRIVATE EVENTS NOTICE */}
        <div className="mb-12">
          <a
            href="https://www.paneventz.com"
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-2 text-[#00f0ff] hover:text-white bg-[#00f0ff]/10 hover:bg-[#00f0ff]/20 border border-[#00f0ff]/30 px-6 py-2.5 rounded-full text-xs sm:text-sm tracking-wider uppercase transition-all duration-300 shadow-sm"
          >
            <span>✨</span>
            <span>
              For Corporate Events, Concerts &amp; Event Management Visit:{" "}
              <strong className="underline decoration-[#00f0ff] underline-offset-4">www.paneventz.com ↗</strong>
            </span>
          </a>
        </div>

        {/* ACTION BUTTONS */}
        <div className="flex flex-wrap items-center justify-center gap-4 mb-16">
          <a
            href="#enquire"
            className="inline-flex items-center gap-2 bg-[#c4a472] hover:bg-[#d8b886] text-[#080809] px-8 py-4 text-xs tracking-[3px] uppercase font-bold transition-all duration-300 shadow-lg hover:shadow-[#c4a472]/20"
          >
            <span>Start A Wedding Conversation</span>
            <ArrowRight size={14} />
          </a>
          <a
            href={whatsappUrl}
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-2 border border-[#c4a472]/50 hover:border-[#c4a472] text-[#c4a472] hover:bg-[#c4a472]/10 px-8 py-4 text-xs tracking-[3px] uppercase font-bold transition-all duration-300"
          >
            <span>WhatsApp ↗</span>
          </a>
        </div>

        {/* FOOTER NAVIGATION LINKS */}
        <div className="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 pb-10 mb-10 border-b border-white/10 text-xs uppercase tracking-[2px] text-[#888]">
          <Link href="#stories" className="hover:text-white transition-colors">
            Stories
          </Link>
          <Link href="#films" className="hover:text-white transition-colors">
            Films
          </Link>
          <Link href="/services" className="hover:text-white transition-colors">
            Services
          </Link>
          <Link href="/terms" className="hover:text-white transition-colors">
            Terms &amp; Conditions
          </Link>
          <Link
            href="/client-portal"
            className="text-[#00f0ff] hover:text-white font-semibold transition-colors flex items-center gap-1"
          >
            <span>Download Story</span> 🔒
          </Link>
          <Link
            href="/galleries"
            className="text-[#c4a472] hover:text-white font-semibold transition-colors flex items-center gap-1"
          >
            <span>Guest Photos AI</span> 📸
          </Link>
          <Link href="#about" className="hover:text-white transition-colors">
            About
          </Link>
          <Link href="/blog" className="hover:text-white transition-colors">
            Journal
          </Link>
          <Link href="#testimonials" className="hover:text-white transition-colors">
            Reviews
          </Link>
          <Link href="#faqs" className="hover:text-white transition-colors">
            FAQs
          </Link>
        </div>

        {/* DESTINATIONS LINKS */}
        <div className="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 mb-8 text-[11px] tracking-[1.5px] uppercase text-[#71717a]">
          <Link href="/wedding-photographer-mumbai" className="hover:text-[#c4a472] transition-colors">
            Mumbai Weddings
          </Link>
          <span>·</span>
          <Link href="/wedding-photographer-udaipur" className="hover:text-[#c4a472] transition-colors">
            Udaipur Royal Palaces
          </Link>
          <span>·</span>
          <Link href="/wedding-photographer-goa" className="hover:text-[#c4a472] transition-colors">
            Goa Beach Celebrations
          </Link>
          <span>·</span>
          <Link href="/wedding-photographer-delhi" className="hover:text-[#c4a472] transition-colors">
            Delhi Grand Weddings
          </Link>
          <span>·</span>
          <Link href="/wedding-photographer-jaipur" className="hover:text-[#c4a472] transition-colors">
            Jaipur Heritage
          </Link>
        </div>

        {/* COPYRIGHT */}
        <div className="text-[11px] tracking-[1.5px] text-[#555] uppercase font-light">
          {setting?.footer_copyright ||
            `© ${year} Paneventz Studio. Mumbai · Pan-India & Worldwide Destination Celebrations.`}
        </div>
      </div>
    </footer>
  );
}