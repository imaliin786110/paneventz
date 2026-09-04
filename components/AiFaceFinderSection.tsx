import React from "react";
import Link from "next/link";
import { Sparkles, ScanFace, Camera, ShieldCheck, Download } from "lucide-react";

export default function AiFaceFinderSection() {
  return (
    <section className="py-28 px-6 lg:px-12 bg-gradient-to-b from-[#09090b] via-[#10141f] to-[#09090b] border-y border-white/5 relative overflow-hidden">
      {/* Subtle background glow */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#00f0ff]/5 rounded-full blur-3xl pointer-events-none" />

      <div className="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        {/* Left Column: Description */}
        <div>
          <span className="text-xs uppercase tracking-[0.3em] text-[#00f0ff] mb-4 inline-flex items-center gap-2 font-semibold">
            <Sparkles size={14} className="animate-pulse" />
            SIGNATURE AI TECHNOLOGY
          </span>
          <h2 className="font-serif text-4xl sm:text-6xl text-[#f5f5f7] font-light leading-tight mb-6">
            AI Facial Recognition for Your Wedding Guests
          </h2>
          <p className="text-base sm:text-lg text-[#a1a1aa] font-light leading-relaxed mb-8">
            No more scrolling through 2,000+ photos to find where you or your family appear. Guests take a 1-second selfie or upload a portrait, and our private on-device neural network instantly finds every high-resolution photo they are in.
          </p>

          <div className="space-y-4 mb-10">
            <div className="flex items-start gap-4">
              <div className="w-10 h-10 rounded-xl bg-[#00f0ff]/10 text-[#00f0ff] flex items-center justify-center shrink-0 border border-[#00f0ff]/30">
                <ScanFace size={20} />
              </div>
              <div>
                <h4 className="text-white font-medium text-sm mb-1">Instant 1-Second Selfie Search</h4>
                <p className="text-xs text-[#a1a1aa] font-light leading-relaxed">
                  Guests scan their face in real-time with their smartphone camera or upload an existing selfie.
                </p>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <div className="w-10 h-10 rounded-xl bg-[#c4a472]/10 text-[#c4a472] flex items-center justify-center shrink-0 border border-[#c4a472]/30">
                <Download size={20} />
              </div>
              <div>
                <h4 className="text-white font-medium text-sm mb-1">1-Click Full Master Downloads</h4>
                <p className="text-xs text-[#a1a1aa] font-light leading-relaxed">
                  Direct full-resolution downloads without watermarks or compression.
                </p>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <div className="w-10 h-10 rounded-xl bg-green-500/10 text-green-400 flex items-center justify-center shrink-0 border border-green-500/30">
                <ShieldCheck size={20} />
              </div>
              <div>
                <h4 className="text-white font-medium text-sm mb-1">100% Privacy & Passcode Protected</h4>
                <p className="text-xs text-[#a1a1aa] font-light leading-relaxed">
                  Every wedding collection is protected with PIN security so only authorized guests can view.
                </p>
              </div>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-4">
            <Link
              href="/galleries"
              className="px-8 py-4 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#0099cc] to-[#00f0ff] text-[#070a12] font-bold hover:opacity-90 transition-all shadow-xl shadow-[#00f0ff]/10"
            >
              Try Guest Photos AI 📸
            </Link>
            <Link
              href="/client-portal"
              className="px-8 py-4 rounded-full text-xs uppercase tracking-widest border border-white/20 text-[#f5f5f7] hover:border-[#00f0ff] hover:text-[#00f0ff] transition-all"
            >
              VIP Client Portal 🔒
            </Link>
          </div>
        </div>

        {/* Right Column: Visual Mockup */}
        <div className="relative">
          <div className="bg-[#121724] border border-[#00f0ff]/30 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
            <div className="flex items-center justify-between pb-6 border-b border-white/10 mb-6">
              <div className="flex items-center gap-3">
                <div className="w-3 h-3 rounded-full bg-red-500" />
                <div className="w-3 h-3 rounded-full bg-yellow-500" />
                <div className="w-3 h-3 rounded-full bg-green-500" />
              </div>
              <span className="text-[11px] uppercase tracking-widest text-[#00f0ff] font-semibold">
                AI Face Finder Active
              </span>
            </div>

            {/* Gallery Grid Preview */}
            <div className="grid grid-cols-2 gap-4 mb-6">
              <div className="relative aspect-square rounded-2xl overflow-hidden border-2 border-[#00f0ff] shadow-lg">
                <img src="/images/1.jpg" alt="" className="w-full h-full object-cover" />
                <span className="absolute bottom-2 left-2 bg-[#00f0ff] text-[#070a12] text-[9px] uppercase font-bold px-2 py-0.5 rounded">
                  Match 99.4%
                </span>
              </div>
              <div className="relative aspect-square rounded-2xl overflow-hidden border-2 border-[#00f0ff] shadow-lg">
                <img src="/images/2.jpg" alt="" className="w-full h-full object-cover" />
                <span className="absolute bottom-2 left-2 bg-[#00f0ff] text-[#070a12] text-[9px] uppercase font-bold px-2 py-0.5 rounded">
                  Match 98.7%
                </span>
              </div>
            </div>

            <div className="bg-[#090b10] rounded-2xl p-4 flex items-center justify-between text-xs">
              <div className="flex items-center gap-3">
                <div className="w-8 h-8 rounded-full bg-[#00f0ff]/20 text-[#00f0ff] flex items-center justify-center font-bold">
                  ✓
                </div>
                <span className="text-white font-medium">Found 24 photos matching your face</span>
              </div>
              <span className="text-[#00f0ff] font-semibold">0.4s scan</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}