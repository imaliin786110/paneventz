import React from "react";
import Link from "next/link";
import { Phone, Sparkles, ArrowUpRight, ArrowRight, ShieldCheck, Camera } from "lucide-react";

export default function Footer({ setting }: { setting: any }) {
  const year = new Date().getFullYear();

  const rawPhones = setting?.phone
    ? setting.phone.split(/[,/|]+/).map((p: string) => p.trim()).filter(Boolean)
    : [];
  const phoneList = rawPhones.length > 0 ? rawPhones : ["+91 80820 24787", "+91 98213 37523"];
  if (phoneList.length === 1 && !phoneList[0].includes("98213")) {
    phoneList.push("+91 98213 37523");
  }

  return (
    <footer id="contact" className="relative bg-[#060608] border-t border-white/[0.08] pt-32 pb-16 px-6 lg:px-12 text-center text-[#94a3b8] overflow-hidden">
      {/* AMBIENT LUXURY GLOW BACKDROP */}
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-[radial-gradient(ellipse_60%_50%_at_50%_0%,rgba(196,164,114,0.12),transparent_70%)] pointer-events-none" />

      <div className="relative max-w-6xl mx-auto">
        {/* EYEBROW WITH BESPOKE FLANKING HAIRLINES */}
        <div className="flex items-center justify-center gap-4 mb-5">
          <span className="h-[1px] w-10 bg-gradient-to-r from-transparent to-[#c4a472]/60" />
          <span className="text-[11px] uppercase tracking-[0.35em] text-[#c4a472] font-semibold">
            LET&apos;S CONNECT
          </span>
          <span className="h-[1px] w-10 bg-gradient-to-l from-transparent to-[#c4a472]/60" />
        </div>

        {/* EDITORIAL HEADING */}
        <h2 className="font-serif text-4xl sm:text-6xl md:text-7xl font-light text-white tracking-tight leading-[1.08] mb-6 max-w-4xl mx-auto">
          {setting?.footer_heading ? (
            setting.footer_heading
          ) : (
            <>
              Let&apos;s create something <span className="italic font-serif font-normal text-[#f5ebd7]">timeless.</span>
            </>
          )}
        </h2>

        {/* SUBTITLE */}
        <p className="max-w-xl mx-auto text-sm sm:text-base text-[#cbd5e1] font-light leading-relaxed mb-12">
          {setting?.footer_description ||
            "Now reserving select dates for wedding celebrations across India and destinations worldwide."}
        </p>

        {/* DIRECT STUDIO LINES LUXURY HUB (SINGLE FOCUSED CENTER) */}
        <div className="max-w-xl mx-auto mb-14">
          <div className="group relative bg-gradient-to-b from-[#0e121a]/90 via-[#0a0d14]/95 to-[#06080d]/98 border border-white/[0.08] hover:border-[#c4a472]/50 rounded-2xl p-8 transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,0,0,0.8),0_0_30px_rgba(196,164,114,0.12)] text-left">
            <div className="absolute top-0 right-0 w-32 h-32 bg-[radial-gradient(circle_at_top_right,rgba(196,164,114,0.08),transparent_70%)] pointer-events-none rounded-tr-2xl" />
            <div className="flex items-center justify-between mb-5">
              <div className="text-[11px] tracking-[2.5px] uppercase text-[#c4a472] font-bold flex items-center gap-2">
                <Phone size={14} className="text-[#c4a472]" />
                <span>Direct Studio Lines</span>
              </div>
              <span className="text-[10px] uppercase tracking-wider text-[#c4a472]/90 bg-[#c4a472]/10 px-2.5 py-0.5 rounded border border-[#c4a472]/20">
                Direct Studio Concierge
              </span>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
              {phoneList.map((phoneNum: string, idx: number) => (
                <a
                  key={idx}
                  href={`tel:${phoneNum.replace(/[^0-9+]/g, "")}`}
                  className="text-white hover:text-[#fce7b2] text-base font-semibold tracking-wider flex items-center justify-between group/link py-3 px-4 rounded-xl bg-white/[0.02] hover:bg-white/[0.06] border border-white/[0.05] hover:border-[#c4a472]/30 transition-all duration-300"
                >
                  <div className="flex items-center gap-3">
                    <span className="w-7 h-7 rounded-full bg-[#c4a472]/15 border border-[#c4a472]/40 flex items-center justify-center text-[#c4a472] text-xs group-hover/link:bg-[#c4a472] group-hover/link:text-[#060608] transition-colors">
                      ✦
                    </span>
                    <span>{phoneNum}</span>
                  </div>
                  <ArrowUpRight size={14} className="text-white/40 group-hover/link:text-[#c4a472] group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-all" />
                </a>
              ))}
            </div>

            <div className="text-[11.5px] tracking-wide text-[#94a3b8] pt-4 border-t border-white/[0.06] flex items-center justify-between">
              <span>Available 9:00 AM – 9:00 PM IST</span>
              <span className="text-white/40">Instant Call Inquiries</span>
            </div>
          </div>
        </div>

        {/* CORPORATE & COMMERCIAL EVENTS VIP BANNER */}
        <div className="mb-14">
          <a
            href="https://www.paneventz.com"
            target="_blank"
            rel="noopener noreferrer"
            className="group inline-flex items-center gap-3 text-white bg-gradient-to-r from-[#00f0ff]/10 via-[#00f0ff]/5 to-[#00f0ff]/10 hover:from-[#00f0ff]/20 hover:to-[#00f0ff]/20 border border-[#00f0ff]/30 hover:border-[#00f0ff]/60 px-7 py-3.5 rounded-full text-xs sm:text-sm tracking-wider uppercase transition-all duration-300 shadow-lg shadow-[#00f0ff]/5 hover:shadow-[#00f0ff]/20"
          >
            <Sparkles size={16} className="text-[#00f0ff] animate-pulse" />
            <span>
              For Corporate Galas, Concerts &amp; Event Management Visit:{" "}
              <strong className="text-[#00f0ff] underline underline-offset-4 font-semibold">www.paneventz.com</strong>
            </span>
            <ArrowUpRight size={16} className="text-[#00f0ff] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
          </a>
        </div>

        {/* PRIMARY CALL TO ACTION BUTTON */}
        <div className="flex items-center justify-center mb-20">
          <a
            href="#enquire"
            className="group inline-flex items-center gap-3 bg-[#c4a472] hover:bg-[#d8b886] text-[#080809] px-10 py-4 text-xs tracking-[3px] uppercase font-bold transition-all duration-300 shadow-[0_10px_30px_rgba(196,164,114,0.2)] hover:shadow-[0_15px_40px_rgba(196,164,114,0.35)] hover:-translate-y-0.5"
          >
            <span>Start A Wedding Conversation</span>
            <ArrowRight size={14} className="group-hover:translate-x-1 transition-transform" />
          </a>
        </div>

        {/* FOOTER DIRECTORY & DESTINATIONS */}
        <div className="pt-12 border-t border-white/[0.08]">
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-8 text-left mb-12">
            <div>
              <h4 className="font-serif text-sm tracking-[2px] uppercase text-white font-medium mb-4">
                Portfolios
              </h4>
              <ul className="space-y-2.5 text-xs uppercase tracking-wider text-[#94a3b8]">
                <li>
                  <Link href="#stories" className="hover:text-[#c4a472] transition-colors">
                    Wedding Stories
                  </Link>
                </li>
                <li>
                  <Link href="#films" className="hover:text-[#c4a472] transition-colors">
                    Cinematic Films (4K)
                  </Link>
                </li>
                <li>
                  <Link href="/services" className="hover:text-[#c4a472] transition-colors">
                    Investment &amp; Packages
                  </Link>
                </li>
                <li>
                  <Link href="/blog" className="hover:text-[#c4a472] transition-colors">
                    The Journal
                  </Link>
                </li>
              </ul>
            </div>

            <div>
              <h4 className="font-serif text-sm tracking-[2px] uppercase text-white font-medium mb-4">
                Client Portal
              </h4>
              <ul className="space-y-2.5 text-xs uppercase tracking-wider text-[#94a3b8]">
                <li>
                  <Link href="/client-portal" className="text-[#00f0ff] hover:text-white font-semibold transition-colors flex items-center gap-1.5">
                    <ShieldCheck size={13} />
                    <span>Download Story 🔒</span>
                  </Link>
                </li>
                <li>
                  <Link href="/galleries" className="text-[#c4a472] hover:text-white font-semibold transition-colors flex items-center gap-1.5">
                    <Camera size={13} />
                    <span>Guest Photos AI 📸</span>
                  </Link>
                </li>
                <li>
                  <Link href="/terms" className="hover:text-[#c4a472] transition-colors">
                    Terms &amp; Policies
                  </Link>
                </li>
                <li>
                  <Link href="#faqs" className="hover:text-[#c4a472] transition-colors">
                    Client FAQs
                  </Link>
                </li>
              </ul>
            </div>

            <div>
              <h4 className="font-serif text-sm tracking-[2px] uppercase text-white font-medium mb-4">
                Destinations
              </h4>
              <ul className="space-y-2.5 text-xs uppercase tracking-wider text-[#94a3b8]">
                <li>
                  <Link href="/wedding-photographer-mumbai" className="hover:text-[#c4a472] transition-colors">
                    Mumbai Luxury
                  </Link>
                </li>
                <li>
                  <Link href="/wedding-photographer-udaipur" className="hover:text-[#c4a472] transition-colors">
                    Udaipur Palaces
                  </Link>
                </li>
                <li>
                  <Link href="/wedding-photographer-goa" className="hover:text-[#c4a472] transition-colors">
                    Goa Beachfront
                  </Link>
                </li>
                <li>
                  <Link href="/wedding-photographer-delhi" className="hover:text-[#c4a472] transition-colors">
                    Delhi Grand
                  </Link>
                </li>
              </ul>
            </div>

            <div>
              <h4 className="font-serif text-sm tracking-[2px] uppercase text-white font-medium mb-4">
                Studio
              </h4>
              <ul className="space-y-2.5 text-xs uppercase tracking-wider text-[#94a3b8]">
                <li>
                  <Link href="#about" className="hover:text-[#c4a472] transition-colors">
                    The Approach
                  </Link>
                </li>
                <li>
                  <Link href="#testimonials" className="hover:text-[#c4a472] transition-colors">
                    Client Trust
                  </Link>
                </li>
                <li>
                  <Link href="/admin" className="text-white/40 hover:text-white transition-colors">
                    Executive Suite ↗
                  </Link>
                </li>
              </ul>
            </div>
          </div>

          {/* COPYRIGHT & BRANDING BAR */}
          <div className="pt-8 border-t border-white/[0.05] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#71717a] font-light">
            <p>
              {setting?.footer_copyright ||
                `© ${year} Paneventz Studio. Handcrafted for unforgettable love stories.`}
            </p>
            <p className="flex items-center gap-2">
              <span>Mumbai</span>
              <span>·</span>
              <span>Pan-India</span>
              <span>·</span>
              <span>Worldwide</span>
            </p>
          </div>
        </div>
      </div>
    </footer>
  );
}