"use client";

import React, { useState } from "react";
import Link from "next/link";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import {
  Lock,
  KeyRound,
  Download,
  CheckCircle2,
  ExternalLink,
  FolderArchive,
  Image as ImageIcon,
  Sparkles,
  ArrowRight,
  LogOut,
  Maximize2,
  X,
} from "lucide-react";

export default function ClientPortalPage() {
  const [pin, setPin] = useState("");
  const [status, setStatus] = useState<"idle" | "loading" | "error" | "unlocked">("idle");
  const [albumData, setAlbumData] = useState<any>(null);
  const [previewPhoto, setPreviewPhoto] = useState<any | null>(null);

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

  const handleLock = () => {
    setStatus("idle");
    setAlbumData(null);
    setPin("");
  };

  return (
    <main className="min-h-screen bg-[#0c0c0d] text-[#d6d6d8]">
      <Navbar studioName="Paneventz" />

      {status === "unlocked" && albumData ? (
        /* Unlocked VIP Master Hub */
        <section className="pt-36 pb-28 px-4 sm:px-6 lg:px-12 max-w-7xl mx-auto">
          {/* VIP Header */}
          <div className="bg-[#121214] border border-[#c4a472]/40 rounded-3xl p-6 sm:p-10 mb-8 relative overflow-hidden shadow-2xl">
            <div className="absolute top-0 right-0 w-80 h-80 bg-[radial-gradient(circle_at_top_right,rgba(196,164,114,0.15),transparent_70%)] pointer-events-none" />

            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
              <div>
                <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#c4a472]/15 border border-[#c4a472]/30 text-[#c4a472] text-[10px] font-bold uppercase tracking-wider mb-3">
                  <CheckCircle2 size={13} /> VIP CLIENT ARCHIVE UNLOCKED
                </div>
                <h1 className="font-serif text-3xl sm:text-5xl text-white font-light mb-2">
                  {albumData.couple_names || albumData.title}
                </h1>
                <p className="text-xs text-zinc-400">
                  📍 {albumData.location || "Private Destination"} · 4K Master Cinema & Photographic Heirlooms
                </p>
              </div>

              {/* Action Buttons */}
              <div className="flex flex-wrap items-center gap-3">
                <a
                  href={`/api/gallery/${albumData.slug}/download?type=client_master`}
                  className="px-6 py-3 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#d8b886] via-[#c4a472] to-[#b38a4c] text-[#0c0c0d] font-bold hover:scale-105 active:scale-95 transition-all shadow-lg shadow-[#c4a472]/30 flex items-center gap-2"
                >
                  <Download size={15} /> Download Full Master 4K Gallery
                </a>

                <Link
                  href={`/gallery/${albumData.slug}`}
                  className="px-5 py-3 rounded-full text-xs uppercase tracking-widest bg-white/5 hover:bg-white/10 border border-white/15 text-white font-semibold transition-all flex items-center gap-2"
                >
                  <span>Celebration Story</span> <ArrowRight size={14} />
                </Link>

                <button
                  onClick={handleLock}
                  className="px-4 py-3 rounded-full text-xs uppercase tracking-wider bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 font-semibold transition-all flex items-center gap-1.5"
                  title="Lock Session"
                >
                  <LogOut size={14} /> Lock
                </button>
              </div>
            </div>
          </div>

          {/* Master Google Drive Cloud Access (Directly In Our System) */}
          {albumData.drive_embed_url ? (
            <div className="bg-[#121214] border border-white/10 rounded-3xl p-6 sm:p-8 mb-12 shadow-2xl">
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-4 border-b border-white/5">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-full bg-[#c4a472]/15 text-[#c4a472] flex items-center justify-center">
                    <FolderArchive size={20} />
                  </div>
                  <div>
                    <h2 className="font-serif text-xl sm:text-2xl text-white font-light">
                      4K Master Cloud Drive — All Files & Folders
                    </h2>
                    <p className="text-xs text-zinc-400">
                      Explore all folders and master photos directly within your Paneventz portal.
                    </p>
                  </div>
                </div>

                {albumData.drive_browse_url && (
                  <a
                    href={albumData.drive_browse_url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-xs text-[#c4a472] border border-[#c4a472]/30 font-semibold transition-all"
                  >
                    Open in Google Drive <ExternalLink size={13} />
                  </a>
                )}
              </div>

              {/* Embedded Google Drive Explorer */}
              <div className="relative w-full h-[650px] sm:h-[720px] rounded-2xl overflow-hidden border border-white/10 bg-[#08080a] shadow-inner">
                <iframe
                  src={albumData.drive_embed_url}
                  className="w-full h-full border-0"
                  title="Master Google Drive Gallery"
                  allow="fullscreen"
                  loading="lazy"
                />
              </div>
            </div>
          ) : (
            albumData.google_drive_folder_id && (
              <div className="bg-[#121214] border border-white/10 rounded-3xl p-8 mb-12 text-center">
                <FolderArchive size={40} className="text-[#c4a472] mx-auto mb-4" />
                <h3 className="font-serif text-2xl text-white font-light mb-2">Master Cloud Archive</h3>
                <p className="text-xs text-zinc-400 max-w-md mx-auto mb-6">
                  Your master archive is configured and ready. Access your complete 4K collection via high-speed cloud link.
                </p>
                <a
                  href={`/api/gallery/${albumData.slug}/download?type=client_master`}
                  className="inline-flex items-center gap-2 px-8 py-3.5 rounded-full text-xs uppercase tracking-widest bg-[#c4a472] text-black font-bold shadow-lg"
                >
                  <Download size={16} /> Access 4K Master Cloud
                </a>
              </div>
            )
          )}

          {/* Photo Gallery Grid (if photos are stored in album) */}
          {albumData.photos && albumData.photos.length > 0 && (
            <div>
              <div className="flex items-center justify-between mb-6">
                <div>
                  <h3 className="font-serif text-2xl text-white font-light">Master Wedding Highlights</h3>
                  <p className="text-xs text-zinc-400">High-resolution heirloom collection ({albumData.photos.length} photos)</p>
                </div>
              </div>

              <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
                {albumData.photos.map((photo: any, idx: number) => {
                  const mediaUrl = photo.photo_url?.startsWith("http") || photo.photo_url?.startsWith("/")
                    ? photo.photo_url
                    : `/${photo.photo_url}`;

                  return (
                    <div
                      key={photo.id || idx}
                      className="group relative aspect-square rounded-2xl overflow-hidden bg-[#121214] border border-white/5 hover:border-[#c4a472]/40 transition-all shadow-xl cursor-pointer"
                      onClick={() => setPreviewPhoto(photo)}
                    >
                      <img
                        src={photo.thumbnail_url || mediaUrl}
                        alt={photo.file_name || `Photo ${idx + 1}`}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-4 flex flex-col justify-end">
                        <span className="text-[11px] text-white truncate font-medium">{photo.file_name || "Wedding Photo"}</span>
                        <div className="flex items-center gap-2 mt-2">
                          <a
                            href={`/api/gallery/${albumData.slug}/download?type=single_photo&photoId=${photo.id}`}
                            onClick={(e) => e.stopPropagation()}
                            className="p-1.5 rounded-lg bg-[#c4a472] text-black hover:opacity-90"
                            title="Download Original"
                          >
                            <Download size={12} />
                          </a>
                          <span className="text-[10px] text-zinc-300">Click to preview</span>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          )}

          {/* Lightbox Modal */}
          {previewPhoto && (
            <div
              className="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex items-center justify-center p-4"
              onClick={() => setPreviewPhoto(null)}
            >
              <button
                onClick={() => setPreviewPhoto(null)}
                className="absolute top-6 right-6 text-zinc-400 hover:text-white p-2 rounded-full bg-white/10"
              >
                <X size={24} />
              </button>
              <div className="max-w-5xl max-h-[90vh] flex flex-col items-center" onClick={(e) => e.stopPropagation()}>
                <img
                  src={previewPhoto.photo_url?.startsWith("http") || previewPhoto.photo_url?.startsWith("/") ? previewPhoto.photo_url : `/${previewPhoto.photo_url}`}
                  alt={previewPhoto.file_name || "Preview"}
                  className="max-h-[80vh] w-auto object-contain rounded-2xl shadow-2xl"
                />
                <div className="mt-4 flex items-center gap-4">
                  <a
                    href={`/api/gallery/${albumData.slug}/download?type=single_photo&photoId=${previewPhoto.id}`}
                    className="px-6 py-2.5 rounded-full text-xs uppercase tracking-wider font-bold bg-[#c4a472] text-black flex items-center gap-2"
                  >
                    <Download size={14} /> Download Original 4K
                  </a>
                </div>
              </div>
            </div>
          )}
        </section>
      ) : (
        /* Locked Gate (Standard Form) */
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
              Enter the private access password or PIN provided by our studio to access your 4K Google Drive photos and heirloom gallery.
            </p>

            <form onSubmit={handleUnlock} className="bg-[#121214] border border-white/5 rounded-3xl p-8 shadow-2xl">
              <div className="mb-6">
                <input
                  type="password"
                  required
                  placeholder="Enter Secret Password / PIN"
                  value={pin}
                  onChange={(e) => setPin(e.target.value)}
                  className="w-full bg-[#0c0c0d] border border-white/10 rounded-2xl px-6 py-4 text-center text-xl tracking-[0.3em] text-white focus:border-[#c4a472] focus:outline-none transition-colors"
                />
              </div>

              {status === "error" && (
                <p className="text-xs text-red-400 mb-4">Invalid PIN code. Please check with Paneventz Studio.</p>
              )}

              <button
                type="submit"
                disabled={status === "loading"}
                className="w-full py-4 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#c4a472] to-[#b45309] text-[#0c0c0d] font-bold hover:opacity-90 transition-opacity disabled:opacity-50"
              >
                {status === "loading" ? "Unlocking Gallery..." : "Access Private Gallery"}
              </button>
            </form>
          </div>
        </section>
      )}

      <Footer setting={null} />
    </main>
  );
}

