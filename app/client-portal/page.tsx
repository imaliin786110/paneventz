"use client";

import React, { useState } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Lock, KeyRound, Download, CheckCircle2 } from "lucide-react";

export default function ClientPortalPage() {
  const [pin, setPin] = useState("");
  const [status, setStatus] = useState<"idle" | "loading" | "error" | "unlocked">("idle");
  const [albumData, setAlbumData] = useState<any>(null);

  const handleUnlock = async (e: React.FormEvent) => {
    e.preventDefault();
    setStatus("loading");

    try {
      const res = await fetch("/api/client-portal/unlock", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ pin }),
      });

      const data = await res.json();
      if (res.ok && data.success) {
        setAlbumData(data.album);
        setStatus("unlocked");
      } else {
        setStatus("error");
      }
    } catch (err) {
      console.error(err);
      setStatus("error");
    }
  };

  return (
    <main className="min-h-screen bg-[#0c0c0d] text-[#d6d6d8]">
      <Navbar studioName="Paneventz" />

      <section className="pt-40 pb-28 px-6 lg:px-12 flex flex-col items-center justify-center min-h-[80vh]">
        <div className="w-full max-w-md mx-auto text-center">
          <div className="w-16 h-16 rounded-full bg-[#c4a472]/10 border border-[#c4a472]/30 flex items-center justify-center text-[#c4a472] mx-auto mb-6">
            <Lock size={28} />
          </div>

          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
            VIP CLIENT PORTAL
          </span>
          <h1 className="font-serif text-4xl sm:text-5xl text-[#f5f5f7] font-light mb-4">
            Unlock Your Wedding Story
          </h1>
          <p className="text-xs text-[#a1a1aa] font-light mb-8">
            Enter the 4 to 6 digit private access PIN provided by our studio to view and download your wedding gallery.
          </p>

          {status === "unlocked" && albumData ? (
            <div className="bg-[#121214] border border-[#c4a472] rounded-3xl p-8 text-center">
              <CheckCircle2 size={48} className="text-[#c4a472] mx-auto mb-4" />
              <h3 className="font-serif text-2xl text-white font-light mb-1">
                {albumData.couple_names || albumData.title}
              </h3>
              <p className="text-xs text-[#c4a472] uppercase tracking-wider mb-6">
                {albumData.location}
              </p>
              <a
                href={albumData.google_drive_folder_id ? `https://drive.google.com/drive/folders/${albumData.google_drive_folder_id}` : "#"}
                target="_blank"
                rel="noreferrer"
                className="inline-flex items-center justify-center gap-2 w-full py-4 rounded-full text-xs uppercase tracking-widest bg-[#c4a472] text-[#0c0c0d] font-bold"
              >
                <Download size={16} /> Download Full Master Gallery
              </a>
            </div>
          ) : (
            <form onSubmit={handleUnlock} className="bg-[#121214] border border-white/5 rounded-3xl p-8 shadow-2xl">
              <div className="mb-6">
                <input
                  type="password"
                  maxLength={8}
                  required
                  placeholder="Enter Secret PIN"
                  value={pin}
                  onChange={(e) => setPin(e.target.value)}
                  className="w-full bg-[#0c0c0d] border border-white/10 rounded-2xl px-6 py-4 text-center text-xl tracking-[0.5em] text-white focus:border-[#c4a472] focus:outline-none transition-colors"
                />
              </div>

              {status === "error" && (
                <p className="text-xs text-red-400 mb-4">Invalid PIN code. Please check with the studio.</p>
              )}

              <button
                type="submit"
                disabled={status === "loading"}
                className="w-full py-4 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b45309] text-[#0c0c0d] font-bold hover:opacity-90 transition-opacity disabled:opacity-50"
              >
                {status === "loading" ? "Verifying PIN..." : "Access Private Gallery"}
              </button>
            </form>
          )}
        </div>
      </section>

      <Footer setting={null} />
    </main>
  );
}