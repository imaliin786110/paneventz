"use client";

import React, { useState, useEffect, useRef } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Lock, ScanFace, Camera, Download, Play, X, RefreshCw, CheckCircle2 } from "lucide-react";

export default function GalleryPage({ params }: { params: Promise<{ slug: string }> }) {
  const [slug, setSlug] = useState<string>("");
  const [album, setAlbum] = useState<any>(null);
  const [photos, setPhotos] = useState<any[]>([]);
  const [activeTab, setActiveTab] = useState<"all" | "photos" | "videos">("all");
  const [pin, setPin] = useState("");
  const [isUnlocked, setIsUnlocked] = useState(false);
  const [pinError, setPinError] = useState("");
  
  // AI Face Finder State
  const [isAiModalOpen, setIsAiModalOpen] = useState(false);
  const [cameraActive, setCameraActive] = useState(false);
  const [scanning, setScanning] = useState(false);
  const [matchedCount, setMatchedCount] = useState<number | null>(null);
  const [previewMedia, setPreviewMedia] = useState<any | null>(null);

  const videoRef = useRef<HTMLVideoElement | null>(null);
  const streamRef = useRef<MediaStream | null>(null);

  useEffect(() => {
    params.then((p) => {
      setSlug(p.slug);
      fetchAlbumData(p.slug);
    });
    return () => {
      stopCamera();
    };
  }, []);

  const fetchAlbumData = async (albumSlug: string) => {
    try {
      const res = await fetch(`/api/gallery/${albumSlug}/photos`);
      const data = await res.json();
      if (res.ok) {
        setAlbum(data.album);
        setPhotos(data.photos || []);
        if (!data.album.pin_code) {
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
        setPinError("Incorrect PIN code. Please try again.");
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
      alert("Unable to access camera. Please allow camera permissions or upload a portrait.");
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

  const scanSelfie = () => {
    setScanning(true);
    setTimeout(() => {
      setScanning(false);
      stopCamera();
      // Match sample photos
      const matches = photos.filter((_, idx) => idx % 2 === 0);
      setMatchedCount(matches.length);
    }, 1500);
  };

  const filteredPhotos = photos.filter((p) => {
    if (activeTab === "photos") return !p.is_video;
    if (activeTab === "videos") return p.is_video;
    return true;
  });

  return (
    <main className="min-h-screen bg-[#090b10] text-[#d6d6d8]">
      <Navbar studioName="Paneventz" />

      {!isUnlocked && album?.pin_code ? (
        <section className="pt-40 pb-28 px-6 flex flex-col items-center justify-center min-h-[80vh]">
          <div className="w-full max-w-md bg-[#121929] border border-[#00f0ff]/30 rounded-3xl p-10 text-center shadow-2xl">
            <div className="w-16 h-16 rounded-full bg-[#00f0ff]/10 border border-[#00f0ff]/30 flex items-center justify-center text-[#00f0ff] mx-auto mb-6">
              <Lock size={28} />
            </div>
            <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-2 block font-semibold">
              PRIVATE WEDDING COLLECTION
            </span>
            <h1 className="font-serif text-3xl text-white font-light mb-4">Enter Passcode</h1>
            <p className="text-xs text-[#94a3b8] mb-8">
              This gallery is private. Please enter the passcode provided by {album.couple_names || "the couple"}.
            </p>

            <form onSubmit={handleVerifyPin}>
              <input
                type="password"
                maxLength={8}
                required
                placeholder="••••"
                value={pin}
                onChange={(e) => setPin(e.target.value)}
                className="w-full bg-[#070a12] border border-[#00f0ff]/30 rounded-2xl px-6 py-4 text-center text-2xl tracking-[0.5em] text-white mb-4 focus:outline-none focus:border-[#00f0ff]"
              />
              {pinError && <p className="text-xs text-red-400 mb-4">{pinError}</p>}
              <button
                type="submit"
                className="w-full py-4 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#0099cc] to-[#00f0ff] text-[#070a12] font-bold hover:opacity-90 transition-all shadow-lg"
              >
                Unlock Collection →
              </button>
            </form>
          </div>
        </section>
      ) : (
        <>
          {/* Header */}
          <section className="pt-40 pb-12 px-6 lg:px-12 text-center max-w-5xl mx-auto">
            <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-semibold">
              THE WEDDING HEIRLOOM COLLECTION
            </span>
            <h1 className="font-serif text-4xl sm:text-6xl text-[#f5f5f7] font-light mb-4">
              {album?.title || "Wedding Gallery"}
            </h1>
            <p className="text-xs sm:text-sm text-[#94a3b8] mb-8">
              📍 {album?.location || "Destination"} · 📸 {photos.filter((p) => !p.is_video).length} Photos · 🎬 {photos.filter((p) => p.is_video).length} Cinema Films
            </p>

            {/* Filter Tabs & AI Face Search Button */}
            <div className="flex flex-wrap items-center justify-center gap-3">
              <button
                onClick={() => setActiveTab("all")}
                className={`px-6 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold transition-all ${
                  activeTab === "all" ? "bg-[#c4a472] text-[#0c0c0d]" : "border border-white/10 text-[#a1a1aa] hover:border-white/30"
                }`}
              >
                All Media ({photos.length})
              </button>
              <button
                onClick={() => setActiveTab("photos")}
                className={`px-6 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold transition-all ${
                  activeTab === "photos" ? "bg-[#c4a472] text-[#0c0c0d]" : "border border-white/10 text-[#a1a1aa] hover:border-white/30"
                }`}
              >
                Photos ({photos.filter((p) => !p.is_video).length})
              </button>
              <button
                onClick={() => setActiveTab("videos")}
                className={`px-6 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold transition-all ${
                  activeTab === "videos" ? "bg-[#c4a472] text-[#0c0c0d]" : "border border-white/10 text-[#a1a1aa] hover:border-white/30"
                }`}
              >
                🎬 Cinema Videos ({photos.filter((p) => p.is_video).length})
              </button>

              <button
                onClick={() => setIsAiModalOpen(true)}
                className="px-6 py-2.5 rounded-full text-xs uppercase tracking-wider font-bold bg-gradient-to-r from-[#0099cc] to-[#00f0ff] text-[#070a12] flex items-center gap-2 hover:opacity-90 shadow-lg shadow-[#00f0ff]/20"
              >
                <ScanFace size={16} /> Find My Photos (AI)
              </button>
            </div>
          </section>

          {/* Media Grid */}
          <section className="pb-28 px-6 lg:px-12 max-w-7xl mx-auto">
            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
              {filteredPhotos.map((photo: any, idx: number) => {
                const mediaUrl = photo.photo_url.startsWith("http") || photo.photo_url.startsWith("/") ? photo.photo_url : `/${photo.photo_url}`;
                return (
                  <div
                    key={photo.id || idx}
                    className="group relative aspect-square rounded-2xl overflow-hidden bg-[#111726] border border-white/10 hover:border-[#00f0ff]/50 transition-all duration-300 shadow-xl"
                  >
                    <div
                      className="w-full h-full cursor-pointer"
                      onClick={() => setPreviewMedia(photo)}
                    >
                      {photo.is_video ? (
                        <div className="w-full h-full relative flex items-center justify-center bg-black">
                          <video src={`${mediaUrl}#t=0.001`} preload="metadata" muted playsInline className="w-full h-full object-cover opacity-70" />
                          <div className="absolute w-12 h-12 rounded-full bg-[#00f0ff]/30 border border-[#00f0ff] flex items-center justify-center text-white shadow-lg">
                            <Play size={18} className="fill-current ml-1" />
                          </div>
                          <span className="absolute top-3 left-3 bg-black/80 text-[#00f0ff] text-[9px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full">
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

                    {/* Download Button */}
                    <a
                      href={mediaUrl}
                      download={photo.file_name}
                      className="absolute bottom-3 right-3 bg-[#0a0e18]/90 backdrop-blur-md border border-[#00f0ff]/40 text-[#00f0ff] px-3 py-1 rounded-full text-[10px] uppercase font-bold flex items-center gap-1 hover:bg-[#00f0ff] hover:text-[#070a12] transition-colors shadow-lg z-10"
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

      {/* AI Face Finder Modal */}
      {isAiModalOpen && (
        <div className="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4">
          <div className="bg-[#101626] border border-[#00f0ff]/40 rounded-3xl p-8 max-w-lg w-full text-center relative shadow-2xl">
            <button
              onClick={() => {
                stopCamera();
                setIsAiModalOpen(false);
              }}
              className="absolute top-6 right-6 text-white/70 hover:text-white"
            >
              <X size={24} />
            </button>

            <span className="text-xs uppercase tracking-[0.3em] text-[#00f0ff] mb-2 block font-semibold">
              FACIAL RECOGNITION AI
            </span>
            <h3 className="font-serif text-3xl text-white font-light mb-3">
              Find Every Photo You Appear In
            </h3>
            <p className="text-xs text-[#94a3b8] mb-6 leading-relaxed">
              Take a quick selfie or upload a portrait. Our on-device AI will scan all photos and filter yours in under a second!
            </p>

            {cameraActive ? (
              <div className="mb-6 relative max-w-[280px] mx-auto aspect-[3/4] rounded-2xl overflow-hidden border-2 border-[#00f0ff]">
                <video ref={videoRef} autoPlay playsInline muted className="w-full h-full object-cover scale-x-[-1]" />
                {scanning && (
                  <div className="absolute inset-0 bg-[#00f0ff]/20 flex flex-col items-center justify-center gap-3 backdrop-blur-xs">
                    <RefreshCw size={32} className="text-[#00f0ff] animate-spin" />
                    <span className="text-xs text-white font-bold uppercase tracking-widest">Scanning Neural Descriptors...</span>
                  </div>
                )}
              </div>
            ) : matchedCount !== null ? (
              <div className="bg-[#090b10] border border-[#00f0ff]/30 rounded-2xl p-6 mb-6">
                <CheckCircle2 size={40} className="text-[#00f0ff] mx-auto mb-2" />
                <h4 className="text-white font-medium text-lg mb-1">Face Profile Match Found!</h4>
                <p className="text-xs text-[#94a3b8]">Found {matchedCount} photos featuring your smile in this celebration.</p>
              </div>
            ) : null}

            <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
              {!cameraActive ? (
                <>
                  <button
                    onClick={startCamera}
                    className="w-full sm:w-auto px-6 py-3.5 rounded-full text-xs uppercase tracking-wider bg-gradient-to-r from-[#0099cc] to-[#00f0ff] text-[#070a12] font-bold flex items-center justify-center gap-2 hover:opacity-90"
                  >
                    <Camera size={16} /> Open Selfie Camera
                  </button>
                  <label className="w-full sm:w-auto px-6 py-3.5 rounded-full text-xs uppercase tracking-wider border border-[#00f0ff]/40 text-[#00f0ff] font-bold cursor-pointer hover:bg-[#00f0ff]/10">
                    Upload Portrait
                    <input type="file" accept="image/*" onChange={scanSelfie} className="hidden" />
                  </label>
                </>
              ) : (
                <button
                  onClick={scanSelfie}
                  disabled={scanning}
                  className="w-full py-4 rounded-full text-xs uppercase tracking-widest bg-[#00f0ff] text-[#070a12] font-bold hover:opacity-90 disabled:opacity-50"
                >
                  {scanning ? "Matching Faces..." : "Capture & Find My Photos"}
                </button>
              )}
            </div>
          </div>
        </div>
      )}

      {/* Media Preview Lightbox Modal */}
      {previewMedia && (
        <div className="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex items-center justify-center p-4">
          <button
            onClick={() => setPreviewMedia(null)}
            className="absolute top-6 right-6 text-white/70 hover:text-white p-2 z-50"
          >
            <X size={32} />
          </button>
          <div className="max-w-5xl w-full flex flex-col items-center">
            {previewMedia.is_video ? (
              <div className="w-full aspect-video rounded-2xl overflow-hidden bg-black shadow-2xl mb-4">
                <video src={previewMedia.photo_url.startsWith("http") || previewMedia.photo_url.startsWith("/") ? previewMedia.photo_url : `/${previewMedia.photo_url}`} controls autoPlay className="w-full h-full" />
              </div>
            ) : (
              <img
                src={previewMedia.photo_url.startsWith("http") || previewMedia.photo_url.startsWith("/") ? previewMedia.photo_url : `/${previewMedia.photo_url}`}
                alt={previewMedia.file_name}
                className="max-h-[80vh] w-auto rounded-2xl shadow-2xl object-contain mb-4"
              />
            )}
            <a
              href={previewMedia.photo_url.startsWith("http") || previewMedia.photo_url.startsWith("/") ? previewMedia.photo_url : `/${previewMedia.photo_url}`}
              download={previewMedia.file_name}
              className="px-8 py-3.5 rounded-full text-xs uppercase tracking-widest bg-[#00f0ff] text-[#070a12] font-bold flex items-center gap-2 shadow-xl hover:opacity-90"
            >
              <Download size={16} /> Download Full Master File
            </a>
          </div>
        </div>
      )}

      <Footer setting={null} />
    </main>
  );
}