"use client";

import React, { useState, useEffect, useRef } from "react";
import * as faceapi from "face-api.js";
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
  Sparkles,
  ShieldCheck,
  FolderArchive,
  ExternalLink,
  ChevronLeft,
  ChevronRight,
  Maximize2,
  Circle,
  Check,
} from "lucide-react";

// A tighter threshold prevents visually similar guests from leaking into a
// person's private result set. The previous 0.54 value was too permissive for
// group photos and low-resolution Drive thumbnails.
const FACE_MATCH_THRESHOLD = 0.5;

export default function GalleryClient({ initialSlug }: { initialSlug: string }) {
  const [slug] = useState<string>(initialSlug);
  const [album, setAlbum] = useState<any>(null);
  const [photos, setPhotos] = useState<any[]>([]);
  const [activeTab, setActiveTab] = useState<"all" | "photos" | "videos">("all");
  const [pin, setPin] = useState("");
  const [isUnlocked, setIsUnlocked] = useState(false);
  const [pinError, setPinError] = useState("");
  const [isPinModalOpen, setIsPinModalOpen] = useState(false);

  // AI Face Finder & Guest Filter State
  const [isAiModalOpen, setIsAiModalOpen] = useState(false);
  const [cameraActive, setCameraActive] = useState(false);
  const [capturedSelfie, setCapturedSelfie] = useState<string | null>(null);
  const [scanning, setScanning] = useState(false);
  const [scanProgress, setScanProgress] = useState({ current: 0, total: 0 });
  const [guestMatchedPhotos, setGuestMatchedPhotos] = useState<any[] | null>(null);
  const [faceSearchError, setFaceSearchError] = useState("");
  const [previewIndex, setPreviewIndex] = useState<number | null>(null);
  const [selectedPhotoIds, setSelectedPhotoIds] = useState<(string | number)[]>([]);
  const [downloadProgress, setDownloadProgress] = useState<{
    active: boolean;
    current: number;
    total: number;
    filename: string;
  } | null>(null);
  const cancelBatchRef = useRef<boolean>(false);
  const lastScanProgressAtRef = useRef(0);

  const videoRef = useRef<HTMLVideoElement | null>(null);
  const streamRef = useRef<MediaStream | null>(null);

  useEffect(() => {
    if (slug) {
      fetchAlbumData(slug);
      // Preload AI models in the background so face recognition is instant
      loadFaceModels().catch((e) => console.log("AI Preload...", e));
    }
    return () => {
      stopCamera();
    };
  }, [slug]);

  const fetchAlbumData = async (albumSlug: string) => {
    try {
      const res = await fetch(`/api/gallery/${albumSlug}/photos?fresh=${Date.now()}`, {
        cache: "no-store",
      });
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
        await fetchAlbumData(slug);
      } else {
        setPinError("Incorrect passcode. Please verify with the couple.");
      }
    } catch {
      setPinError("Verification error. Please try again.");
    }
  };

  const startCamera = async () => {
    try {
      setCapturedSelfie(null);
      setCameraActive(true);
      const stream = await navigator.mediaDevices.getUserMedia({
        video: {
          facingMode: "user",
          width: { ideal: 1280, min: 640 },
          height: { ideal: 720, min: 480 },
          frameRate: { ideal: 24, min: 15 },
        },
      });
      streamRef.current = stream;
      const track = stream.getVideoTracks()[0];
      if (track) {
        try {
          await track.applyConstraints({ width: { ideal: 1280 }, height: { ideal: 720 } });
        } catch {
          // Some integrated webcams only expose their fixed VGA mode.
        }
      }
      if (videoRef.current) {
        videoRef.current.srcObject = stream;
      }
    } catch (err) {
      console.error("Camera access error:", err);
      setFaceSearchError("We could not access your camera. Please allow camera permission and try again.");
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

  const loadFaceModels = async () => {
    const modelPath = "/models";

    await Promise.all([
      faceapi.nets.ssdMobilenetv1.isLoaded || faceapi.nets.ssdMobilenetv1.loadFromUri(modelPath),
      faceapi.nets.faceLandmark68Net.isLoaded || faceapi.nets.faceLandmark68Net.loadFromUri(modelPath),
      faceapi.nets.faceRecognitionNet.isLoaded || faceapi.nets.faceRecognitionNet.loadFromUri(modelPath),
    ]);
  };

  // Always use our same-origin proxy for Drive media. Direct Drive URLs can
  // render in an <img>, but browser fetch() used by face recognition is
  // blocked by Drive CORS. The proxy also serves the smaller thumbnail for
  // scanning and mobile grids.
  const getMediaUrl = (photo: any, original = false, scan = false) =>
    `/api/gallery/${encodeURIComponent(slug)}/media?photoId=${encodeURIComponent(photo.id)}${original ? "&size=original" : scan ? "&size=scan" : ""}`;

  const getStoredDescriptors = (rawDescriptors: unknown): Float32Array[] => {
    if (!rawDescriptors) return [];

    const source = rawDescriptors as { descriptors?: unknown } | unknown[];
    const candidates = Array.isArray(source)
      ? (Array.isArray(source[0]) ? source : [source])
      : Array.isArray(source.descriptors)
        ? source.descriptors
        : [];

    return candidates
      .filter((candidate): candidate is number[] => Array.isArray(candidate) && candidate.length === 128 && candidate.every(Number.isFinite))
      .map((candidate) => new Float32Array(candidate));
  };

  const matchGuestDescriptor = async (guestDescriptor: Float32Array) => {
    const indexedPhotos = photos.filter((photo) => getStoredDescriptors(photo.face_descriptors).length > 0);
    const unindexedPhotos = photos.filter((photo) => !photo.is_video && getStoredDescriptors(photo.face_descriptors).length === 0);

    // Drive-synced photos may not have descriptors yet. Scan those thumbnails
    // in small parallel batches so a large album is still responsive, while
    // preserving the fast vector-only path for admin-indexed photos.
    if (unindexedPhotos.length > 0) {
      const dynamicallyMatched: any[] = [];
      lastScanProgressAtRef.current = 0;
      setScanProgress({ current: 0, total: unindexedPhotos.length });
      let completed = 0;
      const scanPhoto = async (photo: any) => {
        try {
          const imgUrl = getMediaUrl(photo, false, true);
          let imageBlob: Blob | null = null;
          // Drive/CDN thumbnails can occasionally return a transient 5xx or
          // timeout on mobile networks. Retry twice instead of silently
          // dropping that photo from the guest's results.
          for (let attempt = 0; attempt < 3 && !imageBlob; attempt += 1) {
            try {
              const response = await fetch(imgUrl, { signal: AbortSignal.timeout(20000), cache: "force-cache" });
              if (response.ok) imageBlob = await response.blob();
            } catch {
              if (attempt < 2) await new Promise((resolve) => window.setTimeout(resolve, 180 * (attempt + 1)));
            }
          }
          if (!imageBlob) return;
          const imgElem = await faceapi.bufferToImage(imageBlob);
          const maxDimension = 320;
          const scale = Math.min(maxDimension / imgElem.naturalWidth, maxDimension / imgElem.naturalHeight, 1);
          const scanCanvas = document.createElement("canvas");
          scanCanvas.width = Math.max(1, Math.round(imgElem.naturalWidth * scale));
          scanCanvas.height = Math.max(1, Math.round(imgElem.naturalHeight * scale));
          scanCanvas.getContext("2d")?.drawImage(imgElem, 0, 0, scanCanvas.width, scanCanvas.height);
          const detections = await faceapi
            .detectAllFaces(scanCanvas, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 }))
            .withFaceLandmarks()
            .withFaceDescriptors();
          if (detections.some((d) => faceapi.euclideanDistance(guestDescriptor, d.descriptor) <= FACE_MATCH_THRESHOLD)) {
            dynamicallyMatched.push(photo);
          }
        } catch {
          // Ignore an individual unavailable/corrupt image and continue.
        } finally {
          completed += 1;
          // Avoid a React render for every thumbnail. On large Drive albums
          // this used to make the camera preview stutter while face-api was
          // already CPU-bound. Keep the progress indicator responsive without
          // repainting the whole gallery hundreds of times.
          const now = performance.now();
          if (completed === unindexedPhotos.length || now - lastScanProgressAtRef.current >= 120) {
            lastScanProgressAtRef.current = now;
            setScanProgress({ current: completed, total: unindexedPhotos.length });
          }
        }
      };

      const isMobile = typeof window !== "undefined" && window.matchMedia("(max-width: 640px)").matches;
      const batchSize = isMobile ? 4 : 8;
      for (let i = 0; i < unindexedPhotos.length; i += batchSize) {
        await Promise.all(unindexedPhotos.slice(i, i + batchSize).map(scanPhoto));
      }

      const indexedMatches = indexedPhotos.filter((photo) =>
        getStoredDescriptors(photo.face_descriptors).some((descriptor) =>
          faceapi.euclideanDistance(guestDescriptor, descriptor) <= FACE_MATCH_THRESHOLD,
        ),
      );
      const matches = [...indexedMatches, ...dynamicallyMatched];

      if (matches.length > 0) {
        setGuestMatchedPhotos(matches);
        setIsAiModalOpen(false);
        stopCamera();
        return;
      }
    }

    if (indexedPhotos.length === 0 && photos.length === 0) {
      if (album?.guest_drive_browse_url || album?.guest_google_drive_folder_id) {
        setFaceSearchError(
          "The album is still being prepared. Please try again in a moment or contact the studio."
        );
        return;
      }
      setFaceSearchError("This collection has no photos available to search yet. Please contact the studio.");
      return;
    }

    // Fast vector euclidean distance matching in memory (runs in < 5ms)
    const matches = indexedPhotos.filter((photo) =>
      getStoredDescriptors(photo.face_descriptors).some(
        (descriptor) => faceapi.euclideanDistance(guestDescriptor, descriptor) <= FACE_MATCH_THRESHOLD,
      ),
    );

    if (matches.length === 0) {
      setFaceSearchError(
        "No matching photos were found for your selfie. Try again in better light with your face centered in the camera."
      );
      return;
    }

    setGuestMatchedPhotos(matches);
    setIsAiModalOpen(false);
    stopCamera();
  };

  const executeFaceScan = async () => {
    if (!videoRef.current) return;

    setScanning(true);
    setFaceSearchError("");

    try {
      await loadFaceModels();

      const video = videoRef.current;
      if (video.readyState < 2) {
        await new Promise<void>((resolve) => {
          const onReady = () => {
            video.removeEventListener("loadeddata", onReady);
            resolve();
          };
          video.addEventListener("loadeddata", onReady, { once: true });
          window.setTimeout(resolve, 700);
        });
      }
      // Downsample input frame to 480px on offscreen canvas for ultra-fast (sub-second) AI inference
      const canvas = document.createElement("canvas");
      const maxDim = 640;
      const vW = video.videoWidth || 640;
      const vH = video.videoHeight || 480;
      const scale = Math.min(maxDim / vW, maxDim / vH, 1);
      canvas.width = Math.round(vW * scale);
      canvas.height = Math.round(vH * scale);
      const ctx = canvas.getContext("2d");
      if (ctx) {
        ctx.filter = "brightness(1.08) contrast(1.08)";
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      }

      const inputSource = ctx ? canvas : video;
      if (ctx) {
        // Keep a still copy visible while the album is searched. The live
        // camera is stopped immediately below, so guests do not have to keep
        // staring at a moving preview during a large Drive scan.
        setCapturedSelfie(canvas.toDataURL("image/jpeg", 0.82));
      }

      // Freeze the live camera as soon as the selfie frame is captured. The
      // expensive album scan can continue in the background without making
      // the guest's face visibly wobble like a video during processing.
      stopCamera();

      // Laptop webcams are often only VGA quality. Start with the normal
      // confidence, then retry once at a safer threshold so a clear but
      // slightly dark/soft selfie is not rejected as “no face”.
      let detection = await faceapi
        .detectSingleFace(inputSource, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.45 }))
        .withFaceLandmarks()
        .withFaceDescriptor();
      if (!detection) {
        detection = await faceapi
          .detectSingleFace(inputSource, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.3 }))
          .withFaceLandmarks()
          .withFaceDescriptor();
      }

      if (!detection) {
        throw new Error("We could not find a clear face. Please look directly into the camera in good lighting and try again.");
      }

      await matchGuestDescriptor(detection.descriptor);
    } catch (error) {
      setFaceSearchError(error instanceof Error ? error.message : "Face search could not be completed. Please try again.");
    } finally {
      setScanning(false);
    }
  };

  const displayedPhotos = guestMatchedPhotos
    ? guestMatchedPhotos
    : photos.filter((p) => {
        if (activeTab === "photos") return !p.is_video;
        if (activeTab === "videos") return p.is_video;
        return true;
      });

  // Guest AI albums remain locked to the selfie flow until a match is found.
  // This prevents guests from browsing the complete celebration gallery.
  const requiresFaceMatch = Boolean(album?.enable_face_ai);
  const canShowGuestMedia = !requiresFaceMatch || Boolean(guestMatchedPhotos);

  // Keyboard navigation for Big Screen Lightbox
  useEffect(() => {
    if (previewIndex === null || displayedPhotos.length === 0) return;

    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "ArrowLeft") {
        setPreviewIndex((prev) => (prev !== null && prev > 0 ? prev - 1 : displayedPhotos.length - 1));
      } else if (e.key === "ArrowRight") {
        setPreviewIndex((prev) => (prev !== null && prev < displayedPhotos.length - 1 ? prev + 1 : 0));
      } else if (e.key === "Escape") {
        setPreviewIndex(null);
      }
    };

    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [previewIndex, displayedPhotos]);

  // Selection toggle helpers
  const togglePhotoSelection = (id: string | number, e?: React.MouseEvent) => {
    if (e) e.stopPropagation();
    setSelectedPhotoIds((prev) =>
      prev.includes(id) ? prev.filter((item) => item !== id) : [...prev, id]
    );
  };

  const selectAllPhotos = () => {
    if (!displayedPhotos) return;
    setSelectedPhotoIds(displayedPhotos.map((p: any) => p.id));
  };

  const deselectAllPhotos = () => {
    setSelectedPhotoIds([]);
  };

  // Download all or download selected photos in browser
  const handleBatchDownload = async (targetPhotos?: any[]) => {
    const list = targetPhotos || displayedPhotos;
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

      const downloadUrl = `/api/gallery/${slug}/download?type=single_photo&photoId=${photo.id}`;
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

  const handleDownloadAll = () => handleBatchDownload(displayedPhotos);

  const handleDownloadSelected = () => {
    if (!displayedPhotos || selectedPhotoIds.length === 0) return;
    const selected = displayedPhotos.filter((p: any) => selectedPhotoIds.includes(p.id));
    handleBatchDownload(selected);
  };

  const currentPreviewMedia =
    previewIndex !== null && displayedPhotos ? displayedPhotos[previewIndex] : null;

  return (
    <main className="min-h-screen bg-[#070708] text-[#d6d6d8]">
      <Navbar studioName="Paneventz" />

      {/* 1. Strictly Private Album PIN Gate */}
      {/* Face-AI guest galleries skip the client PIN entirely. The selfie is
          the gate and only matched media is revealed; client downloads keep
          their separate PIN-protected portal. */}
      {!isUnlocked && album?.has_pin && album?.is_public === false && !requiresFaceMatch ? (
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
                    onClick={() => {
                      setGuestMatchedPhotos(null);
                      setSelectedPhotoIds([]);
                      setPreviewIndex(null);
                    }}
                  className="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl font-medium transition-colors text-xs"
                >
                  Clear Matches
                </button>
              </div>
            )}

            {/* Action Bar: Filter Tabs + AI Finder + Client Master Download */}
            <div className="flex flex-wrap items-center justify-center gap-3">
              {!guestMatchedPhotos && !requiresFaceMatch && (
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

              {/* Select All / Deselect All Button */}
              {canShowGuestMedia && displayedPhotos.length > 0 && (
                <button
                  type="button"
                  onClick={selectedPhotoIds.length === displayedPhotos.length ? deselectAllPhotos : selectAllPhotos}
                  className="px-4 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold bg-white/5 hover:bg-white/10 border border-white/10 text-zinc-300 transition-colors"
                >
                  {selectedPhotoIds.length === displayedPhotos.length ? "Deselect All" : "Select All"}
                </button>
              )}

              {/* Guest AI Facial Finder Trigger */}
              <button
                onClick={() => setIsAiModalOpen(true)}
                disabled={album?.enable_face_ai === false}
                className="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider font-bold bg-gradient-to-r from-[#00b4d8] to-[#00f0ff] text-black flex items-center gap-2 hover:opacity-90 shadow-lg shadow-[#00f0ff]/20 transition-all"
              >
                <ScanFace size={16} /> Find My Photos (AI)
              </button>

              {/* Download All Photos Directly on Our Website */}
              {canShowGuestMedia && album?.allow_downloads && displayedPhotos.length > 0 && (
                <button
                  type="button"
                  onClick={handleDownloadAll}
                  className="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider font-bold bg-[#c4a472] hover:bg-[#b09060] text-black flex items-center gap-2 transition-all shadow-lg shadow-[#c4a472]/20"
                >
                  <Download size={15} /> Download All ({displayedPhotos.length})
                </button>
              )}

              {/* Guest Google Drive Folder Access */}
              {!requiresFaceMatch && (album?.guest_drive_browse_url || album?.guest_google_drive_folder_id) && (
                <a
                  href={album.guest_drive_browse_url || album.guest_google_drive_folder_id}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 flex items-center gap-2 transition-all shadow-md shadow-emerald-500/10"
                >
                  <FolderArchive size={16} /> Guest Celebration Drive ↗
                </a>
              )}

              {/* Client Password-Protected Master Archive Download */}
              {album?.allow_downloads && !requiresFaceMatch && (
                <button
                  type="button"
                  onClick={() => {
                    if (isUnlocked || !album?.has_pin) {
                      window.location.href = `/api/gallery/${slug}/download?type=client_master`;
                    } else {
                      setIsPinModalOpen(true);
                    }
                  }}
                  className="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold bg-white/10 hover:bg-white/15 border border-white/20 text-white flex items-center gap-2 transition-all cursor-pointer"
                >
                  <FolderArchive size={16} className="text-[#c4a472]" /> Master Cloud Archive
                </button>
              )}
            </div>
          </section>

          {/* 3. Media Grid (hidden until the guest selfie matches) */}
          {canShowGuestMedia ? <section className="pb-28 px-6 lg:px-12 max-w-7xl mx-auto">
            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
              {displayedPhotos.map((photo: any, idx: number) => {
                const mediaUrl = getMediaUrl(photo);
                const isSelected = selectedPhotoIds.includes(photo.id);

                return (
                  <div
                    key={photo.id || idx}
                    className={`group relative aspect-square rounded-2xl overflow-hidden bg-[#121214] transition-all duration-300 shadow-xl cursor-pointer ${
                      isSelected
                        ? "border-2 border-[#c4a472] ring-4 ring-[#c4a472]/20 scale-[0.98]"
                        : "border border-white/5 hover:border-[#c4a472]/40"
                    }`}
                    onClick={() => setPreviewIndex(idx)}
                  >
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

                    <div className="w-full h-full">
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
                          <span className="absolute top-3 right-3 bg-black/80 text-[#c4a472] text-[9px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full">
                            Cinema {photo.file_size ? `· ${photo.file_size}` : ""}
                          </span>
                        </div>
                      ) : (
                        <img
                          src={photo.thumbnail_url || mediaUrl}
                          alt={photo.file_name || `Photo #${idx + 1}`}
                          loading="lazy"
                          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                      )}
                    </div>

                    {/* Single Photo Direct Download Button */}
                    {album?.allow_downloads && (
                      <a
                        href={`/api/gallery/${slug}/download?type=single_photo&photoId=${photo.id}`}
                        download={photo.file_name || `photo_${idx + 1}.jpg`}
                        onClick={(e) => e.stopPropagation()}
                        className="absolute bottom-3 right-3 bg-black/80 backdrop-blur-md border border-white/20 text-white px-3 py-1 rounded-full text-[10px] uppercase font-bold flex items-center gap-1 hover:bg-[#c4a472] hover:text-black transition-colors shadow-lg z-10"
                        title="Download 4K"
                      >
                        <Download size={12} /> 4K
                      </a>
                    )}
                  </div>
                );
              })}
            </div>
          </section> : (
            <section className="pb-28 px-6 lg:px-12 max-w-2xl mx-auto">
              <div className="rounded-3xl border border-[#00f0ff]/25 bg-[#00f0ff]/5 p-8 sm:p-12 text-center shadow-2xl">
                <div className="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full border border-[#00f0ff]/40 bg-[#00f0ff]/10 text-[#00f0ff]">
                  <ScanFace size={30} />
                </div>
                <h2 className="font-serif text-3xl text-white font-light mb-3">Your private photos are waiting</h2>
                <p className="text-sm leading-relaxed text-zinc-400 mb-7">
                  Take a live selfie to securely find only the photos that feature you. The complete album stays hidden.
                </p>
                <button
                  type="button"
                  onClick={() => setIsAiModalOpen(true)}
                  className="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#00b4d8] to-[#00f0ff] px-7 py-3 text-xs font-bold uppercase tracking-wider text-black shadow-lg shadow-[#00f0ff]/20"
                >
                  <Camera size={16} /> Open Selfie Camera
                </button>
              </div>
            </section>
          )}
        </>
      )}

      {/* 4. AI Face Finder Modal for Guests */}
      {isAiModalOpen && (
        <div className="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-4">
          <div className="bg-[#121214] border border-[#00f0ff]/30 rounded-3xl p-8 max-w-lg w-full text-center relative shadow-2xl">
            <button
              onClick={() => {
                stopCamera();
                setCapturedSelfie(null);
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
              Use your live camera selfie. Guest uploads are disabled: only the studio can add media to this album.
            </p>

            {faceSearchError && (
              <div className="mb-5 rounded-2xl border border-red-400/30 bg-red-400/10 p-4 text-xs leading-relaxed text-red-200">
                <p>{faceSearchError}</p>
              </div>
            )}

            {cameraActive || capturedSelfie ? (
              <div className="mb-6 relative max-w-[260px] mx-auto aspect-[3/4] rounded-2xl overflow-hidden border-2 border-[#00f0ff]">
                {capturedSelfie ? (
                  <img src={capturedSelfie} alt="Captured selfie" className="w-full h-full object-cover scale-x-[-1]" />
                ) : (
                  <video ref={videoRef} autoPlay playsInline muted className="w-full h-full object-cover scale-x-[-1]" />
                )}
                {scanning && (
                  <div className="absolute inset-0 bg-black/45 flex flex-col items-center justify-center gap-3 backdrop-blur-[2px]">
                    <RefreshCw size={32} className="text-[#00f0ff] animate-spin" />
                    <span className="text-xs text-white font-bold uppercase tracking-widest">
                      Finding Your Photos...
                    </span>
                    <span className="text-[10px] text-zinc-200/80">
                      You can look away — this selfie is captured
                    </span>
                  </div>
                )}
              </div>
            ) : null}

            <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
              {!cameraActive && !scanning ? (
                <button
                  onClick={startCamera}
                  className="w-full sm:w-auto px-6 py-3 rounded-xl text-xs uppercase tracking-wider bg-gradient-to-r from-[#00b4d8] to-[#00f0ff] text-black font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-[#00f0ff]/20"
                >
                  <Camera size={16} /> Open Camera
                </button>
              ) : (
                <button
                  onClick={executeFaceScan}
                  disabled={scanning}
                  className="w-full px-8 py-3.5 rounded-xl text-xs uppercase tracking-wider bg-gradient-to-r from-[#00b4d8] to-[#00f0ff] text-black font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-all disabled:opacity-50"
                >
                  {scanning
                    ? `Scanning ${scanProgress.current}/${scanProgress.total || photos.length} photos…`
                    : "Capture & Match My Photos"}
                </button>
              )}
            </div>

            <div className="mt-6 pt-4 border-t border-white/5 flex items-center justify-center gap-1.5 text-[11px] text-zinc-500">
              <ShieldCheck size={12} className="text-emerald-400" />
              <span>Camera-only matching: no guest photo is uploaded, saved, or added to this gallery.</span>
            </div>
          </div>
        </div>
      )}

      {/* 5. Big Screen Lightbox Modal with Next / Prev Navigation */}
      {currentPreviewMedia && previewIndex !== null && (
        <div
          className="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex items-center justify-center p-2 sm:p-6"
          onClick={() => setPreviewIndex(null)}
        >
          {/* Header Bar */}
          <div className="absolute top-2 inset-x-2 sm:top-4 sm:inset-x-8 flex items-center justify-between gap-2 z-20">
            <div className="min-w-0 flex items-center gap-2 sm:gap-3 bg-black/60 backdrop-blur-md px-3 sm:px-4 py-2 rounded-full border border-white/10 text-[10px] sm:text-xs text-zinc-300">
              <span className="font-bold text-[#c4a472]">
                {previewIndex + 1}/{displayedPhotos.length}
              </span>
              <span className="text-zinc-500 hidden sm:inline">|</span>
              <span className="truncate max-w-[90px] sm:max-w-xs text-zinc-400 hidden sm:inline">
                {currentPreviewMedia.file_name || `Photo #${previewIndex + 1}`}
              </span>
            </div>

            <div className="flex items-center gap-1 sm:gap-2 shrink-0">
              {/* Select / Deselect toggle inside Lightbox */}
              <button
                type="button"
                onClick={(e) => {
                  e.stopPropagation();
                  togglePhotoSelection(currentPreviewMedia.id);
                }}
                className={`px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition-all ${
                  selectedPhotoIds.includes(currentPreviewMedia.id)
                    ? "bg-[#c4a472] text-black"
                    : "bg-white/10 hover:bg-white/20 text-white"
                }`}
              >
                {selectedPhotoIds.includes(currentPreviewMedia.id) ? (
                  <>
                    <Check size={14} className="stroke-[3]" /> Selected
                  </>
                ) : (
                  <>
                    <Circle size={14} /> Select
                  </>
                )}
              </button>

              {album?.allow_downloads && (
                <a
                  href={`/api/gallery/${slug}/download?type=single_photo&photoId=${currentPreviewMedia.id}`}
                  download={currentPreviewMedia.file_name}
                  onClick={(e) => e.stopPropagation()}
                  className="px-4 py-1.5 rounded-full bg-[#c4a472] text-black text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 shadow-lg hover:opacity-95 transition-opacity"
                >
                  <Download size={13} /> Download 4K
                </a>
              )}

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
          {displayedPhotos.length > 1 && (
            <button
              type="button"
              onClick={(e) => {
                e.stopPropagation();
                setPreviewIndex(previewIndex > 0 ? previewIndex - 1 : displayedPhotos.length - 1);
              }}
              className="absolute left-1 sm:left-6 z-20 p-2 sm:p-4 rounded-full bg-black/60 hover:bg-[#c4a472] text-white hover:text-black border border-white/10 transition-all shadow-2xl"
              title="Previous Photo (Left Arrow)"
            >
              <ChevronLeft size={24} />
            </button>
          )}

          {/* Main Media Container */}
          <div
            className="w-full max-w-6xl max-h-[calc(100dvh-7.5rem)] sm:max-h-[82vh] flex flex-col items-center justify-center relative select-none"
            onClick={(e) => e.stopPropagation()}
          >
            {currentPreviewMedia.is_video ? (
              <video
                src={getMediaUrl(currentPreviewMedia, true)}
                controls
                autoPlay
                className="max-h-[58vh] sm:max-h-[80vh] max-w-full w-auto rounded-2xl shadow-2xl"
              />
            ) : (
              <img
                src={getMediaUrl(currentPreviewMedia, true)}
                alt={currentPreviewMedia.file_name || "Big Screen Preview"}
                className="max-h-[58vh] sm:max-h-[80vh] w-auto max-w-full object-contain rounded-2xl shadow-2xl"
              />
            )}

            {/* Bottom Navigation & Download Bar */}
            <div className="mt-4 flex flex-wrap items-center justify-center gap-3">
              {album?.allow_downloads && (
                <a
                  href={`/api/gallery/${slug}/download?type=single_photo&photoId=${currentPreviewMedia.id}`}
                  download={currentPreviewMedia.file_name}
                  className="px-6 py-2.5 rounded-full text-xs uppercase tracking-wider font-bold bg-[#c4a472] text-black flex items-center gap-2 shadow-lg shadow-[#c4a472]/20 hover:scale-105 active:scale-95 transition-all"
                >
                  <Download size={14} /> Download Original 4K
                </a>
              )}
              {album?.allow_downloads && (
                <button
                  type="button"
                  onClick={handleDownloadAll}
                  className="px-5 py-2.5 rounded-full text-xs uppercase tracking-wider font-semibold bg-white/10 hover:bg-white/15 text-white border border-white/15 flex items-center gap-2 transition-all"
                >
                  <Download size={14} className="text-[#c4a472]" /> Download All ({displayedPhotos.length})
                </button>
              )}
              <span className="text-[11px] text-zinc-500 hidden sm:inline-block">
                Use &larr; / &rarr; arrow keys to navigate
              </span>
            </div>
          </div>

          {/* Next Button */}
          {displayedPhotos.length > 1 && (
            <button
              type="button"
              onClick={(e) => {
                e.stopPropagation();
                setPreviewIndex(previewIndex < displayedPhotos.length - 1 ? previewIndex + 1 : 0);
              }}
              className="absolute right-1 sm:right-6 z-20 p-2 sm:p-4 rounded-full bg-black/60 hover:bg-[#c4a472] text-white hover:text-black border border-white/10 transition-all shadow-2xl"
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

      {/* 5. Master Passcode Verification Modal */}
      {isPinModalOpen && (
        <div className="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4">
          <div className="bg-[#121214] border border-[#c4a472]/40 rounded-3xl p-8 max-w-md w-full text-center relative shadow-2xl">
            <button
              onClick={() => setIsPinModalOpen(false)}
              className="absolute top-6 right-6 text-zinc-400 hover:text-white"
            >
              <X size={20} />
            </button>
            <div className="w-14 h-14 rounded-full bg-[#c4a472]/15 text-[#c4a472] flex items-center justify-center mx-auto mb-4">
              <Lock size={24} />
            </div>
            <h3 className="font-serif text-2xl text-white font-light mb-2">
              Unlock Master 4K Archive
            </h3>
            <p className="text-xs text-zinc-400 mb-6">
              Enter the private client passcode provided by Paneventz Studio to access and download the master 4K archive.
            </p>
            <form onSubmit={handleVerifyPin} className="space-y-4">
              <input
                type="password"
                required
                placeholder="Enter Passcode"
                value={pin}
                onChange={(e) => setPin(e.target.value)}
                className="w-full bg-[#18181b] border border-white/15 rounded-xl px-4 py-3.5 text-center text-lg tracking-[0.3em] text-white focus:outline-none focus:border-[#c4a472]"
              />
              {pinError && <p className="text-xs text-red-400">{pinError}</p>}
              <button
                type="submit"
                className="w-full py-3.5 rounded-xl text-xs uppercase tracking-wider bg-[#c4a472] text-black font-bold hover:bg-[#b09060] transition-colors"
              >
                Verify &amp; Unlock Archive &rarr;
              </button>
            </form>
          </div>
        </div>
      )}

      <Footer setting={{ studio_name: "Paneventz" }} />
    </main>
  );
}
