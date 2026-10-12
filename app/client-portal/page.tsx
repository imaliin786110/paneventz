"use client";

import React, { useState, useEffect, useRef } from "react";
import Link from "next/link";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import {
  Lock,
  KeyRound,
  Download,
  CheckCircle2,
  FolderArchive,
  Image as ImageIcon,
  Film,
  Sparkles,
  ArrowRight,
  LogOut,
  Maximize2,
  X,
  ChevronLeft,
  ChevronRight,
  Loader2,
  Circle,
  Check,
} from "lucide-react";

export default function ClientPortalPage() {
  const [pin, setPin] = useState("");
  const [status, setStatus] = useState<"idle" | "loading" | "error" | "unlocked">("idle");
  const [albumData, setAlbumData] = useState<any>(null);
  const [previewIndex, setPreviewIndex] = useState<number | null>(null);
  const [selectedPhotoIds, setSelectedPhotoIds] = useState<(string | number)[]>([]);
  const [activeMediaType, setActiveMediaType] = useState<"photos" | "videos">("photos");
  const [downloadProgress, setDownloadProgress] = useState<{
    active: boolean;
    current: number;
    total: number;
    filename: string;
  } | null>(null);
  const cancelBatchRef = useRef<boolean>(false);

  const visibleMedia = albumData?.photos?.filter((media: any) =>
    activeMediaType === "videos" ? media.is_video : !media.is_video
  ) || [];

  // Keyboard navigation for Big Screen Lightbox
  useEffect(() => {
    if (previewIndex === null || !visibleMedia.length) return;

    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "ArrowLeft") {
        setPreviewIndex((prev) => (prev !== null && prev > 0 ? prev - 1 : visibleMedia.length - 1));
      } else if (e.key === "ArrowRight") {
        setPreviewIndex((prev) => (prev !== null && prev < visibleMedia.length - 1 ? prev + 1 : 0));
      } else if (e.key === "Escape") {
        setPreviewIndex(null);
      }
    };

    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [previewIndex, visibleMedia]);

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
    setPreviewIndex(null);
    setSelectedPhotoIds([]);
    setActiveMediaType("photos");
  };

  // Selection toggle helpers
  const togglePhotoSelection = (id: string | number, e?: React.MouseEvent) => {
    if (e) e.stopPropagation();
    setSelectedPhotoIds((prev) =>
      prev.includes(id) ? prev.filter((item) => item !== id) : [...prev, id]
    );
  };

  const selectAllPhotos = () => {
    setSelectedPhotoIds(visibleMedia.map((p: any) => p.id));
  };

  const deselectAllPhotos = () => {
    setSelectedPhotoIds([]);
  };

  // Download all or download selected photos in browser
  const handleBatchDownload = async (targetPhotos?: any[]) => {
    const list = targetPhotos || albumData?.photos;
    if (!list || list.length === 0) return;
    cancelBatchRef.current = false;
    setDownloadProgress({
      active: true,
      current: 0,
      total: list.length,
      filename: "Initializing download queue...",
    });

    for (let i = 0; i < list.length; i++) {
      if (cancelBatchRef.current) break;
      const photo = list[i];
      const name = photo.file_name || `Paneventz_${i + 1}.jpg`;
      setDownloadProgress({
        active: true,
        current: i + 1,
        total: list.length,
        filename: name,
      });

      const downloadUrl = `/api/gallery/${albumData.slug}/download?type=single_photo&photoId=${photo.id}`;
      const link = document.createElement("a");
      link.href = downloadUrl;
      link.download = name;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      // Brief delay to prevent browser throttling
      await new Promise((r) => setTimeout(r, 650));
    }

    setDownloadProgress(null);
  };

  const handleDownloadAll = () => handleBatchDownload(visibleMedia);

  const handleDownloadSelected = () => {
    if (!albumData?.photos || selectedPhotoIds.length === 0) return;
    const selected = albumData.photos.filter((p: any) => selectedPhotoIds.includes(p.id));
    handleBatchDownload(selected);
  };

  const currentPreviewPhoto =
    previewIndex !== null ? visibleMedia[previewIndex] : null;

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
                <button
                  type="button"
                  onClick={handleDownloadAll}
                  className="px-6 py-3 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#d8b886] via-[#c4a472] to-[#b38a4c] text-[#0c0c0d] font-bold hover:scale-105 active:scale-95 transition-all shadow-lg shadow-[#c4a472]/30 flex items-center gap-2"
                >
                  <Download size={15} /> Download {activeMediaType === "videos" ? "Films" : "Photos"} ({visibleMedia.length})
                </button>

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

          {/* 1. Photo Gallery Grid in Our Website Panel (PRIMARY SHOWCASE) */}
          {albumData.photos && albumData.photos.length > 0 ? (
            <div className="mb-14">
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                  <h2 className="font-serif text-2xl sm:text-3xl text-white font-light">
                    Your Wedding Heirlooms
                  </h2>
                  <p className="text-xs text-zinc-400 mt-1">
                    Select media using the circles, or click to preview in big screen ({visibleMedia.length} {activeMediaType === "videos" ? "Cinema Films" : "High-Resolution Photographs"})
                  </p>
                </div>

                <div className="flex flex-wrap items-center gap-2">
                  <button
                    type="button"
                    onClick={selectedPhotoIds.length === visibleMedia.length ? deselectAllPhotos : selectAllPhotos}
                    className="px-3.5 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-zinc-300 text-xs font-semibold uppercase tracking-wider border border-white/10 transition-colors"
                  >
                    {selectedPhotoIds.length === visibleMedia.length ? "Deselect All" : "Select All"}
                  </button>
                  <button
                    type="button"
                    onClick={handleDownloadAll}
                    className="px-4 py-2 rounded-xl bg-[#c4a472] text-black text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 shadow-md hover:opacity-90 transition-opacity"
                  >
                    <Download size={13} /> Download All {activeMediaType === "videos" ? "Films" : "Photos"}
                  </button>
                </div>
              </div>

              <div className="flex flex-wrap items-center gap-2 mb-6">
                <button
                  type="button"
                  onClick={() => { setActiveMediaType("photos"); setPreviewIndex(null); setSelectedPhotoIds([]); }}
                  className={`px-4 py-2 rounded-full text-xs uppercase tracking-wider font-semibold border transition-all flex items-center gap-2 ${activeMediaType === "photos" ? "bg-[#c4a472] text-black border-[#c4a472]" : "bg-white/5 text-zinc-300 border-white/10 hover:bg-white/10"}`}
                >
                  <ImageIcon size={14} /> Photos ({albumData.photos.filter((p: any) => !p.is_video).length})
                </button>
                <button
                  type="button"
                  onClick={() => { setActiveMediaType("videos"); setPreviewIndex(null); setSelectedPhotoIds([]); }}
                  className={`px-4 py-2 rounded-full text-xs uppercase tracking-wider font-semibold border transition-all flex items-center gap-2 ${activeMediaType === "videos" ? "bg-[#c4a472] text-black border-[#c4a472]" : "bg-white/5 text-zinc-300 border-white/10 hover:bg-white/10"}`}
                >
                  <Film size={14} /> Cinema Films ({albumData.photos.filter((p: any) => p.is_video).length})
                </button>
              </div>

              <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
                {visibleMedia.map((photo: any, idx: number) => {
                  const mediaUrl = photo.photo_url?.startsWith("http") || photo.photo_url?.startsWith("/")
                    ? photo.photo_url
                    : `/${photo.photo_url}`;
                  const isSelected = selectedPhotoIds.includes(photo.id);

                  return (
                    <div
                      key={photo.id || idx}
                      className={`group relative aspect-square rounded-2xl overflow-hidden bg-[#121214] transition-all shadow-xl cursor-pointer ${
                        isSelected
                          ? "border-2 border-[#c4a472] ring-4 ring-[#c4a472]/20 scale-[0.98]"
                          : "border border-white/5 hover:border-[#c4a472]/40"
                      }`}
                      onClick={() => setPreviewIndex(idx)}
                    >
                      {photo.is_video ? (
                        <div className="relative w-full h-full">
                          <img src={photo.thumbnail_url || mediaUrl} alt={photo.file_name || `Film ${idx + 1}`} loading="lazy" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                          <span className="absolute bottom-3 left-3 inline-flex items-center gap-1 rounded-full bg-black/75 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white"><Film size={11} /> Cinema</span>
                        </div>
                      ) : (
                        <img src={photo.thumbnail_url || mediaUrl} alt={photo.file_name || `Photo ${idx + 1}`} loading="lazy" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                      )}

                      {/* Select / Deselect Checkbox Button */}
                      <button
                        type="button"
                        onClick={(e) => togglePhotoSelection(photo.id, e)}
                        className={`absolute top-3 left-3 z-20 w-7 h-7 rounded-full flex items-center justify-center transition-all ${
                          isSelected
                            ? "bg-[#c4a472] text-black shadow-lg shadow-[#c4a472]/50 scale-110"
                            : "bg-black/60 text-white/70 hover:text-white hover:bg-black/90 border border-white/20"
                        }`}
                        title={isSelected ? "Deselect this photo" : "Select this photo"}
                      >
                        {isSelected ? <Check size={16} className="stroke-[3]" /> : <Circle size={15} />}
                      </button>

                      {/* Single Photo Download Button Badge */}
                      <a
                        href={`/api/gallery/${albumData.slug}/download?type=single_photo&photoId=${photo.id}`}
                        onClick={(e) => e.stopPropagation()}
                        className="absolute top-3 right-3 p-2 rounded-xl bg-black/70 hover:bg-[#c4a472] text-white hover:text-black border border-white/10 transition-all opacity-80 group-hover:opacity-100 shadow-lg z-10 flex items-center gap-1 text-[10px] font-bold"
                        title={photo.is_video ? "Download Cinema Film" : "Download 4K Photo"}
                      >
                        <Download size={12} />
                      </a>

                      {/* Hover Overlay */}
                      <div className="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-4 flex flex-col justify-end">
                        <span className="text-[11px] text-white truncate font-medium">{photo.file_name || `Photo #${idx + 1}`}</span>
                        <div className="flex items-center justify-between gap-2 mt-2">
                          <span className="text-[10px] text-zinc-300 flex items-center gap-1">
                            <Maximize2 size={10} /> Preview Big Screen
                          </span>
                          <a
                            href={`/api/gallery/${albumData.slug}/download?type=single_photo&photoId=${photo.id}`}
                            onClick={(e) => e.stopPropagation()}
                            className="px-2.5 py-1.5 rounded-lg bg-[#c4a472] text-black hover:opacity-90 flex items-center gap-1 text-[10px] font-bold shadow-md"
                            title={photo.is_video ? "Download Cinema Film" : "Download Original 4K"}
                          >
                            <Download size={11} /> {photo.is_video ? "Download Film" : "Download 4K"}
                          </a>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          ) : (
            /* If photos not yet populated in database */
            <div className="bg-[#121214] border border-white/10 rounded-3xl p-8 sm:p-12 mb-12 text-center">
              <FolderArchive size={48} className="text-[#c4a472] mx-auto mb-4" />
              <h2 className="font-serif text-2xl sm:text-3xl text-white font-light mb-2">
                4K Master Cloud Archive Ready
              </h2>
              <p className="text-xs text-zinc-400 max-w-lg mx-auto mb-8">
                Your high-resolution photographs are securely available here. Preview, select, and download each photo directly from this private portal.
              </p>
              <div className="flex flex-wrap items-center justify-center gap-3">
                <span className="inline-flex items-center gap-2 rounded-full border border-[#c4a472]/30 bg-[#c4a472]/10 px-6 py-3.5 text-xs uppercase tracking-widest text-[#c4a472]">
                  <CheckCircle2 size={16} /> Website gallery ready
                </span>
              </div>
            </div>
          )}

          {/* Big Screen Lightbox Modal with Next / Prev Navigation */}
          {currentPreviewPhoto && previewIndex !== null && (
            <div
              className="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex items-center justify-center p-3 sm:p-6"
              onClick={() => setPreviewIndex(null)}
            >
              {/* Header Bar */}
              <div className="absolute top-4 inset-x-4 sm:inset-x-8 flex items-center justify-between z-20">
                <div className="flex items-center gap-3 bg-black/60 backdrop-blur-md px-4 py-2 rounded-full border border-white/10 text-xs text-zinc-300">
                  <span className="font-bold text-[#c4a472]">
                    {activeMediaType === "videos" ? "Film" : "Photo"} {previewIndex + 1} of {visibleMedia.length}
                  </span>
                  <span className="text-zinc-500">|</span>
                  <span className="truncate max-w-[160px] sm:max-w-xs text-zinc-400">
                    {currentPreviewPhoto.file_name || `Photo #${previewIndex + 1}`}
                  </span>
                </div>

                <div className="flex items-center gap-2">
                  {/* Select / Deselect toggle inside Lightbox */}
                  <button
                    type="button"
                    onClick={(e) => {
                      e.stopPropagation();
                      togglePhotoSelection(currentPreviewPhoto.id);
                    }}
                    className={`px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition-all ${
                      selectedPhotoIds.includes(currentPreviewPhoto.id)
                        ? "bg-[#c4a472] text-black"
                        : "bg-white/10 hover:bg-white/20 text-white"
                    }`}
                  >
                    {selectedPhotoIds.includes(currentPreviewPhoto.id) ? (
                      <>
                        <Check size={14} className="stroke-[3]" /> Selected
                      </>
                    ) : (
                      <>
                        <Circle size={14} /> Select
                      </>
                    )}
                  </button>

                  <a
                    href={`/api/gallery/${albumData.slug}/download?type=single_photo&photoId=${currentPreviewPhoto.id}`}
                    onClick={(e) => e.stopPropagation()}
                    className="px-4 py-1.5 rounded-full bg-[#c4a472] text-black text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 shadow-lg hover:opacity-95 transition-opacity"
                  >
                    <Download size={13} /> {currentPreviewPhoto.is_video ? "Download Film" : "Download 4K"}
                  </a>

                  <button
                    onClick={() => setPreviewIndex(null)}
                    className="text-zinc-400 hover:text-white p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors"
                    title="Close (Esc)"
                  >
                    <X size={20} />
                  </button>
                </div>
              </div>

              {/* Previous Button */}
              {visibleMedia.length > 1 && (
                <button
                  type="button"
                  onClick={(e) => {
                    e.stopPropagation();
                    setPreviewIndex(previewIndex > 0 ? previewIndex - 1 : albumData.photos.length - 1);
                  }}
                  className="absolute left-3 sm:left-6 z-20 p-3 sm:p-4 rounded-full bg-black/60 hover:bg-[#c4a472] text-white hover:text-black border border-white/10 transition-all shadow-2xl"
                  title="Previous Photo (Left Arrow)"
                >
                  <ChevronLeft size={24} />
                </button>
              )}

              {/* Main Photo Container */}
              <div
                className="max-w-6xl max-h-[82vh] flex flex-col items-center justify-center relative select-none"
                onClick={(e) => e.stopPropagation()}
              >
                {currentPreviewPhoto.is_video ? (
                  <video
                    src={`/api/gallery/${albumData.slug}/media?photoId=${currentPreviewPhoto.id}`}
                    poster={currentPreviewPhoto.thumbnail_url || undefined}
                    controls
                    playsInline
                    className="max-h-[75vh] sm:max-h-[80vh] w-auto max-w-full rounded-2xl shadow-2xl"
                  />
                ) : (
                  <img
                    src={
                      currentPreviewPhoto.photo_url?.startsWith("http") || currentPreviewPhoto.photo_url?.startsWith("/")
                        ? currentPreviewPhoto.photo_url
                        : `/${currentPreviewPhoto.photo_url}`
                    }
                    alt={currentPreviewPhoto.file_name || "Big Screen Preview"}
                    className="max-h-[75vh] sm:max-h-[80vh] w-auto max-w-full object-contain rounded-2xl shadow-2xl"
                  />
                )}

                {/* Bottom Navigation & Download Bar */}
                <div className="mt-4 flex flex-wrap items-center justify-center gap-3">
                  <a
                    href={`/api/gallery/${albumData.slug}/download?type=single_photo&photoId=${currentPreviewPhoto.id}`}
                    className="px-6 py-2.5 rounded-full text-xs uppercase tracking-wider font-bold bg-[#c4a472] text-black flex items-center gap-2 shadow-lg shadow-[#c4a472]/20 hover:scale-105 active:scale-95 transition-all"
                  >
                    <Download size={14} /> {currentPreviewPhoto.is_video ? "Download Cinema Film" : "Download Original 4K"}
                  </a>
                  <button
                    type="button"
                    onClick={handleDownloadAll}
                    className="px-5 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold bg-white/10 hover:bg-white/15 text-white border border-white/15 flex items-center gap-2 transition-all"
                  >
                    <Download size={14} className="text-[#c4a472]" /> Download {activeMediaType === "videos" ? "Films" : "Photos"} ({visibleMedia.length})
                  </button>
                  <span className="text-[11px] text-zinc-500 hidden sm:inline-block">
                    Use &larr; / &rarr; arrow keys to navigate
                  </span>
                </div>
              </div>

              {/* Next Button */}
              {visibleMedia.length > 1 && (
                <button
                  type="button"
                  onClick={(e) => {
                    e.stopPropagation();
                    setPreviewIndex(previewIndex < albumData.photos.length - 1 ? previewIndex + 1 : 0);
                  }}
                  className="absolute right-3 sm:right-6 z-20 p-3 sm:p-4 rounded-full bg-black/60 hover:bg-[#c4a472] text-white hover:text-black border border-white/10 transition-all shadow-2xl"
                  title="Next Photo (Right Arrow)"
                >
                  <ChevronRight size={24} />
                </button>
              )}
            </div>
          )}

          {/* Floating Selection Bar (appears when 1 or more photos selected) */}
          {selectedPhotoIds.length > 0 && (
            <div className="fixed bottom-6 inset-x-4 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 max-w-xl z-40 bg-[#121214]/95 border border-[#c4a472]/60 backdrop-blur-xl px-5 py-3.5 rounded-2xl sm:rounded-full shadow-2xl flex flex-wrap items-center justify-between gap-3 animate-in fade-in slide-in-from-bottom-4 duration-300">
              <div className="flex items-center gap-2.5 text-xs">
                <span className="w-6 h-6 rounded-full bg-[#c4a472] text-black font-bold flex items-center justify-center text-[11px]">
                  {selectedPhotoIds.length}
                </span>
                <span className="text-white font-medium">Selected to Download</span>
              </div>

              <div className="flex items-center gap-2">
                <button
                  type="button"
                  onClick={deselectAllPhotos}
                  className="text-[11px] text-zinc-400 hover:text-white px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors"
                >
                  Deselect All
                </button>
                <button
                  type="button"
                  onClick={handleDownloadSelected}
                  className="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider bg-[#c4a472] hover:bg-[#b09060] text-black flex items-center gap-1.5 shadow-lg shadow-[#c4a472]/30 transition-all"
                >
                  <Download size={13} /> Download Selected ({selectedPhotoIds.length})
                </button>
              </div>
            </div>
          )}

          {/* In-Browser Batch Download Progress Modal */}
          {downloadProgress && downloadProgress.active && (
            <div className="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-4">
              <div className="bg-[#121214] border border-[#c4a472]/40 rounded-3xl p-8 max-w-md w-full text-center shadow-2xl relative">
                <div className="w-14 h-14 rounded-full bg-[#c4a472]/15 text-[#c4a472] flex items-center justify-center mx-auto mb-4 animate-pulse">
                  <Download size={26} />
                </div>
                <h3 className="font-serif text-2xl text-white font-light mb-1">
                  Downloading Your 4K Photos
                </h3>
                <p className="text-xs text-zinc-400 mb-6">
                  Saving photo files directly to your device from our website.
                </p>

                {/* Progress Bar */}
                <div className="w-full bg-white/10 rounded-full h-3 mb-3 overflow-hidden">
                  <div
                    className="bg-gradient-to-r from-[#d8b886] via-[#c4a472] to-[#b38a4c] h-full transition-all duration-300"
                    style={{
                      width: `${(downloadProgress.current / downloadProgress.total) * 100}%`,
                    }}
                  />
                </div>

                <div className="flex items-center justify-between text-xs text-zinc-300 mb-4">
                  <span className="font-bold text-[#c4a472]">
                    Photo {downloadProgress.current} of {downloadProgress.total}
                  </span>
                  <span>{Math.round((downloadProgress.current / downloadProgress.total) * 100)}%</span>
                </div>

                <p className="text-[11px] text-zinc-500 truncate mb-6">
                  {downloadProgress.filename}
                </p>

                <button
                  type="button"
                  onClick={() => {
                    cancelBatchRef.current = true;
                    setDownloadProgress(null);
                  }}
                  className="px-6 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold bg-white/5 hover:bg-white/10 text-zinc-400 hover:text-white border border-white/10 transition-colors"
                >
                  Cancel Download
                </button>
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
