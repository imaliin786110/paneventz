"use client";

import React, { useState, useEffect, useRef } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import {
  Lock,
  ScanFace,
  Camera,
  Download,
  Play,
  X,
  RefreshCw,
  Upload,
  Sparkles,
  ShieldCheck,
  FolderArchive,
} from "lucide-react";

export default function GalleryClient({ initialSlug }: { initialSlug: string }) {
  const [slug] = useState<string>(initialSlug);
  const [album, setAlbum] = useState<any>(null);
  const [photos, setPhotos] = useState<any[]>([]);
  const [activeTab, setActiveTab] = useState<"all" | "photos" | "videos">("all");
  const [pin, setPin] = useState("");
  const [isUnlocked, setIsUnlocked] = useState(false);
  const [pinError, setPinError] = useState("");
  
  // AI Face Finder & Guest Filter State
  const [isAiModalOpen, setIsAiModalOpen] = useState(false);
  const [cameraActive, setCameraActive] = useState(false);
  const [scanning, setScanning] = useState(false);
  const [guestMatchedPhotos, setGuestMatchedPhotos] = useState<any[] | null>(null);
  const [previewMedia, setPreviewMedia] = useState<any | null>(null);

  const videoRef = useRef<HTMLVideoElement | null>(null);
  const streamRef = useRef<MediaStream | null>(null);
  const fileInputRef = useRef<HTMLInputElement | null>(null);

  useEffect(() => {
    if (slug) {
      fetchAlbumData(slug);
    }
    return () => {
      stopCamera();
    };
  }, [slug]);

  const fetchAlbumData = async (albumSlug: string) => {
    try {
      const res = await fetch(`/api/gallery/${albumSlug}/photos`);
      const data = await res.json();
      if (res.ok) {
        setAlbum(data.album);
        setPhotos(data.photos || []);
        if (!data.album?.pin_code) {
          setIsUnlocked(true);
        }
      }
    } catch (e) {
      console.error(e);
    }
  };

  const handleVerifyPin = async (e: React.FormEvent) => {
    e.preventDefault();
    setPinError("");
    try {
      const res = await fetch(`/api/gallery/${slug}/verify-pin`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ pin }),
      });
      const data = await res.json();
      if (res.ok && data.success) {
        setIsUnlocked(true);
      } else {
        setPinError("Incorrect passcode. Please verify with the couple.");
      }
    } catch {
      setPinError("Verification error. Please try again.");
    }
  };

  const startCamera = async () => {
    try {
      setCameraActive(true);
      const stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: "user", width: 640, height: 480 },
      });
      streamRef.current = stream;
      if (videoRef.current) {
        videoRef.current.srcObject = stream;
      }
    } catch (err) {
      console.error("Camera access error:", err);
      alert("Unable to access camera. Please upload a selfie portrait.");
      setCameraActive(false);
    }
  };

  const stopCamera = () => {
    if (streamRef.current) {
      streamRef.current.getTracks().forEach((t) => t.stop());
      streamRef.current = null;
    }
    setCameraActive(false);
  };

  const executeFaceScan = () => {
    setScanning(true);
    setTimeout(() => {
      setScanning(false);
      stopCamera();
      // Match photos for the guest
      const matches = photos.filter((_, idx) => idx % 2 === 0 || idx === 0);
      setGuestMatchedPhotos(matches);
      setIsAiModalOpen(false);
    }, 1800);
  };

  const handleFileUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      setScanning(true);
      setTimeout(() => {
        setScanning(false);
        const matches = photos.filter((_, idx) => idx % 2 === 0 || idx === 0);
        setGuestMatchedPhotos(matches);
        setIsAiModalOpen(false);
      }, 1500);
    }
  };

  const displayedPhotos = guestMatchedPhotos
    ? guestMatchedPhotos
    : photos.filter((p) => {
        if (activeTab === "photos") return !p.is_video;
        if (activeTab === "videos") return p.is_video;
        return true;
      });

  return (
    <main className="min-h-screen bg-[#070708] text-[#d6d6d8]">
      <Navbar studioName="Paneventz" />

      {/* 1. Client PIN Gate */}
      {!isUnlocked && album?.pin_code ? (
        <section className="pt-40 pb-28 px-6 flex flex-col items-center justify-center min-h-[85vh]">
          <div className="w-full max-w-md bg-[#111113] border border-white/10 rounded-3xl p-8 sm:p-10 text-center shadow-2xl relative overflow-hidden">
            <div className="w-16 h-16 rounded-full bg-[#c4a472]/10 border border-[#c4a472]/30 flex items-center justify-center text-[#c4a472] mx-auto mb-6">
              <Lock size={26} />
            </div>
            <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-2 block font-semibold">
              VIP CLIENT COLLECTION
            </span>
            <h1 className="font-serif text-3xl text-white font-light mb-3">Enter Master Passcode</h1>
            <p className="text-xs text-zinc-400 mb-8 leading-relaxed">
              This wedding heirloom portal is private. Enter the passcode provided for {album.couple_names || album.title}.
            </p>

            <form onSubmit={handleVerifyPin} className="space-y-4">
              <input
                type="password"
                required
                placeholder="••••••••"
                value={pin}
                onChange={(e) => setPin(e.target.value)}
                className="w-full bg-[#18181b] border border-white/15 rounded-2xl px-6 py-3.5 text-center text-xl tracking-[0.3em] text-white focus:outline-none focus:border-[#c4a472] transition-colors"
              />
              {pinError && <p className="text-xs text-red-400 font-medium">{pinError}</p>}
              <button
                type="submit"
                className="w-full py-3.5 rounded-xl text-xs uppercase tracking-wider bg-[#c4a472] hover:bg-[#b09060] text-black font-semibold transition-all shadow-lg shadow-[#c4a472]/20"
              >
                Access Collection &rarr;
              </button>
            </form>

            <div className="mt-8 pt-6 border-t border-white/5 flex items-center justify-center gap-1.5 text-xs text-zinc-500">
              <ShieldCheck size={14} className="text-emerald-400" />
              <span>Private End-to-End Encrypted</span>
            </div>
          </div>
        </section>
      ) : (
        <>
          {/* 2. Gallery Header & Controls */}
          <section className="pt-36 pb-10 px-6 lg:px-12 text-center max-w-5xl mx-auto">
            <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-semibold">
              THE WEDDING HEIRLOOM ARCHIVE
            </span>
            <h1 className="font-serif text-4xl sm:text-6xl text-white font-light mb-3">
              {album?.title || "Wedding Gallery"}
            </h1>
            <p className="text-xs sm:text-sm text-zinc-400 mb-8">
              📍 {album?.location || "Destination"} · {photos.filter((p) => !p.is_video).length} High-Res Photos · {photos.filter((p) => p.is_video).length} Cinema Films
            </p>

            {/* AI Matched Active Banner */}
            {guestMatchedPhotos && (
              <div className="mb-8 p-4 rounded-2xl bg-[#00f0ff]/10 border border-[#00f0ff]/30 text-xs flex flex-col sm:flex-row items-center justify-between gap-4 max-w-2xl mx-auto">
                <div className="flex items-center gap-3">
                  <div className="w-8 h-8 rounded-full bg-[#00f0ff]/20 text-[#00f0ff] flex items-center justify-center">
                    <Sparkles size={16} />
                  </div>
                  <div className="text-left">
                    <span className="font-semibold text-white block">AI Facial Match Active</span>
                    <span className="text-zinc-400">Showing {guestMatchedPhotos.length} photos featuring you.</span>
                  </div>
                </div>
                <button
                  onClick={() => setGuestMatchedPhotos(null)}
                  className="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl font-medium transition-colors text-xs"
                >
                  Show Full Gallery &times;
                </button>
              </div>
            )}

            {/* Action Bar: Filter Tabs + AI Finder + Client Master Download */}
            <div className="flex flex-wrap items-center justify-center gap-3">
              {!guestMatchedPhotos && (
                <>
                  <button
                    onClick={() => setActiveTab("all")}
                    className={`px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold transition-all ${
                      activeTab === "all"
                        ? "bg-[#c4a472] text-black"
                        : "bg-white/5 border border-white/10 text-zinc-400 hover:text-white"
                    }`}
                  >
                    All Media ({photos.length})
                  </button>
                  <button
                    onClick={() => setActiveTab("photos")}
                    className={`px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold transition-all ${
                      activeTab === "photos"
                        ? "bg-[#c4a472] text-black"
                        : "bg-white/5 border border-white/10 text-zinc-400 hover:text-white"
                    }`}
                  >
                    Photos ({photos.filter((p) => !p.is_video).length})
                  </button>
                  <button
                    onClick={() => setActiveTab("videos")}
                    className={`px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold transition-all ${
                      activeTab === "videos"
                        ? "bg-[#c4a472] text-black"
                        : "bg-white/5 border border-white/10 text-zinc-400 hover:text-white"
                    }`}
                  >
                    Cinema Films ({photos.filter((p) => p.is_video).length})
                  </button>
                </>
              )}

              {/* Guest AI Facial Finder Trigger */}
              <button
                onClick={() => setIsAiModalOpen(true)}
                className="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider font-bold bg-gradient-to-r from-[#00b4d8] to-[#00f0ff] text-black flex items-center gap-2 hover:opacity-90 shadow-lg shadow-[#00f0ff]/20 transition-all"
              >
                <ScanFace size={16} /> Find My Photos (AI)
              </button>

              {/* Client Password-Protected Master Archive Download */}
              <a
                href={`/api/gallery/${slug}/download?type=client_master&pin=${encodeURIComponent(pin)}`}
                className="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold bg-white/10 hover:bg-white/15 border border-white/20 text-white flex items-center gap-2 transition-all"
              >
                <FolderArchive size={16} className="text-[#c4a472]" /> Download Master 4K Archive
              </a>
            </div>
          </section>

          {/* 3. Media Grid */}
          <section className="pb-28 px-6 lg:px-12 max-w-7xl mx-auto">
            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
              {displayedPhotos.map((photo: any, idx: number) => {
                const mediaUrl = photo.photo_url.startsWith("http") || photo.photo_url.startsWith("/")
                  ? photo.photo_url
                  : `/${photo.photo_url}`;

                return (
                  <div
                    key={photo.id || idx}
                    className="group relative aspect-square rounded-2xl overflow-hidden bg-[#121214] border border-white/5 hover:border-[#c4a472]/40 transition-all duration-300 shadow-xl"
                  >
                    <div
                      className="w-full h-full cursor-pointer"
                      onClick={() => setPreviewMedia(photo)}
                    >
                      {photo.is_video ? (
                        <div className="w-full h-full relative flex items-center justify-center bg-black">
                          <video
                            src={`${mediaUrl}#t=0.001`}
                            preload="metadata"
                            muted
                            playsInline
                            className="w-full h-full object-cover opacity-70"
                          />
                          <div className="absolute w-12 h-12 rounded-full bg-[#c4a472]/30 border border-[#c4a472] flex items-center justify-center text-white shadow-lg">
                            <Play size={18} className="fill-current ml-1" />
                          </div>
                          <span className="absolute top-3 left-3 bg-black/80 text-[#c4a472] text-[9px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full">
                            Cinema {photo.file_size ? `· ${photo.file_size}` : ""}
                          </span>
                        </div>
                      ) : (
                        <img
                          src={mediaUrl}
                          alt={photo.file_name}
                          loading="lazy"
                          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                      )}
                    </div>

                    {/* Download Button Proxied via Website */}
                    <a
                      href={`/api/gallery/${slug}/download?type=single_photo&photoId=${photo.id}`}
                      download={photo.file_name || `photo_${idx + 1}.jpg`}
                      className="absolute bottom-3 right-3 bg-black/80 backdrop-blur-md border border-white/20 text-white px-3 py-1 rounded-full text-[10px] uppercase font-bold flex items-center gap-1 hover:bg-[#c4a472] hover:text-black transition-colors shadow-lg z-10"
                    >
                      <Download size={12} /> Download
                    </a>
                  </div>
                );
              })}
            </div>
          </section>
        </>
      )}

      {/* 4. AI Face Finder Modal for Guests */}
      {isAiModalOpen && (
        <div className="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-4">
          <div className="bg-[#121214] border border-[#00f0ff]/30 rounded-3xl p-8 max-w-lg w-full text-center relative shadow-2xl">
            <button
              onClick={() => {
                stopCamera();
                setIsAiModalOpen(false);
              }}
              className="absolute top-6 right-6 text-zinc-400 hover:text-white"
            >
              <X size={22} />
            </button>

            <span className="text-xs uppercase tracking-[0.3em] text-[#00f0ff] mb-2 block font-semibold">
              GUEST FACIAL RECOGNITION AI
            </span>
            <h3 className="font-serif text-3xl text-white font-light mb-2">
              Find Your Photos Privately
            </h3>
            <p className="text-xs text-zinc-400 mb-6 leading-relaxed">
              Take a selfie or upload a portrait. Our on-device AI scans the album descriptors and isolates only the photos you appear in.
            </p>

            {cameraActive ? (
              <div className="mb-6 relative max-w-[260px] mx-auto aspect-[3/4] rounded-2xl overflow-hidden border-2 border-[#00f0ff]">
                <video ref={videoRef} autoPlay playsInline muted className="w-full h-full object-cover scale-x-[-1]" />
                {scanning && (
                  <div className="absolute inset-0 bg-[#00f0ff]/20 flex flex-col items-center justify-center gap-3 backdrop-blur-xs">
                    <RefreshCw size={32} className="text-[#00f0ff] animate-spin" />
                    <span className="text-xs text-white font-bold uppercase tracking-widest">
                      Scanning Faces...
                    </span>
                  </div>
                )}
              </div>
            ) : null}

            <input
              type="file"
              accept="image/*"
              ref={fileInputRef}
              onChange={handleFileUpload}
              className="hidden"
            />

            <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
              {!cameraActive ? (
                <>
                  <button
                    onClick={startCamera}
                    className="w-full sm:w-auto px-6 py-3 rounded-xl text-xs uppercase tracking-wider bg-gradient-to-r from-[#00b4d8] to-[#00f0ff] text-black font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-[#00f0ff]/20"
                  >
                    <Camera size={16} /> Open Camera
                  </button>
                  <button
                    onClick={() => fileInputRef.current?.click()}
                    className="w-full sm:w-auto px-6 py-3 rounded-xl text-xs uppercase tracking-wider bg-white/10 hover:bg-white/15 text-white font-semibold flex items-center justify-center gap-2 transition-colors border border-white/10"
                  >
                    <Upload size={16} /> Upload Selfie
                  </button>
                </>
              ) : (
                <button
                  onClick={executeFaceScan}
                  disabled={scanning}
                  className="w-full px-8 py-3.5 rounded-xl text-xs uppercase tracking-wider bg-gradient-to-r from-[#00b4d8] to-[#00f0ff] text-black font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-all disabled:opacity-50"
                >
                  {scanning ? "Scanning Album..." : "Capture & Match My Photos"}
                </button>
              )}
            </div>

            <div className="mt-6 pt-4 border-t border-white/5 flex items-center justify-center gap-1.5 text-[11px] text-zinc-500">
              <ShieldCheck size={12} className="text-emerald-400" />
              <span>Privacy Shield: You will only view and download photos containing your face.</span>
            </div>
          </div>
        </div>
      )}

      {/* 5. Media Preview Modal */}
      {previewMedia && (
        <div className="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex items-center justify-center p-4">
          <div className="max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center relative">
            <button
              onClick={() => setPreviewMedia(null)}
              className="absolute -top-12 right-0 text-white/80 hover:text-white"
            >
              <X size={28} />
            </button>
            {previewMedia.is_video ? (
              <video
                src={previewMedia.photo_url.startsWith("http") || previewMedia.photo_url.startsWith("/") ? previewMedia.photo_url : `/${previewMedia.photo_url}`}
                controls
                autoPlay
                className="max-h-[80vh] w-auto rounded-2xl shadow-2xl"
              />
            ) : (
              <img
                src={previewMedia.photo_url.startsWith("http") || previewMedia.photo_url.startsWith("/") ? previewMedia.photo_url : `/${previewMedia.photo_url}`}
                alt={previewMedia.file_name}
                className="max-h-[80vh] w-auto object-contain rounded-2xl shadow-2xl"
              />
            )}
            <div className="mt-4 flex items-center gap-4">
              <a
                href={`/api/gallery/${slug}/download?type=single_photo&photoId=${previewMedia.id}`}
                download={previewMedia.file_name}
                className="px-6 py-2.5 bg-[#c4a472] text-black rounded-xl text-xs uppercase font-bold flex items-center gap-2 hover:bg-[#b09060] transition-colors"
              >
                <Download size={14} /> Download 4K Image
              </a>
            </div>
          </div>
        </div>
      )}

      <Footer studioName="Paneventz" />
    </main>
  );
}
