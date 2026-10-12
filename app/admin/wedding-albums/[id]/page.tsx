"use client";

import { useEffect, useState, useRef } from "react";
import Link from "next/link";
import * as faceapi from "face-api.js";
import {
  ArrowLeft,
  CheckCircle2,
  ImagePlus,
  Loader2,
  Trash2,
  Upload,
  FolderSync,
  ExternalLink,
  Sparkles,
  Layers,
  Link as LinkIcon,
  AlertCircle,
} from "lucide-react";
import MediaUploader from "@/components/admin/MediaUploader";
import { extractDriveId, getDriveThumbnailUrl } from "@/lib/drive";

const modelPath = "/models";
async function loadFaceModels() {
  await Promise.all([
    faceapi.nets.ssdMobilenetv1.isLoaded || faceapi.nets.ssdMobilenetv1.loadFromUri(modelPath),
    faceapi.nets.faceLandmark68Net.isLoaded || faceapi.nets.faceLandmark68Net.loadFromUri(modelPath),
    faceapi.nets.faceRecognitionNet.isLoaded || faceapi.nets.faceRecognitionNet.loadFromUri(modelPath),
  ]);
}

export default function AlbumMediaManager({ params }: { params: Promise<{ id: string }> }) {
  const [albumId, setAlbumId] = useState("");
  const [album, setAlbum] = useState<any>(null);
  const [mediaUrl, setMediaUrl] = useState("");
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState("");
  const [activeTab, setActiveTab] = useState<"bulk" | "drive_links" | "single">("bulk");

  // Bulk File Upload State
  const [bulkFiles, setBulkFiles] = useState<File[]>([]);
  const [bulkProcessing, setBulkProcessing] = useState(false);
  const [bulkProgress, setBulkProgress] = useState({ current: 0, total: 0, facesFound: 0 });
  const fileInputRef = useRef<HTMLInputElement | null>(null);

  // Drive Links State
  const [driveLinksText, setDriveLinksText] = useState("");
  const [linksProcessing, setLinksProcessing] = useState(false);
  const [linksProgress, setLinksProgress] = useState({ current: 0, total: 0, facesFound: 0 });

  const loadAlbum = async (id: string) => {
    try {
      const response = await fetch(`/api/admin/albums/${id}/photos`, { cache: "no-store" });
      const data = await response.json();
      if (response.ok) setAlbum(data.album);
      else setMessage(data.error || "Unable to load this album.");
    } catch {
      setMessage("Error loading album data.");
    }
  };

  useEffect(() => {
    params.then(({ id }) => {
      setAlbumId(id);
      loadAlbum(id);
    });
  }, [params]);

  // Optimize image via browser canvas before uploading/indexing
  const optimizeImage = async (file: File): Promise<{ blob: Blob; dataUrl: string }> => {
    return new Promise((resolve) => {
      const img = new Image();
      const objUrl = URL.createObjectURL(file);
      img.onload = () => {
        URL.revokeObjectURL(objUrl);
        const MAX_DIM = 1800;
        let { width, height } = img;
        if (width > MAX_DIM || height > MAX_DIM) {
          if (width > height) {
            height = Math.round((height * MAX_DIM) / width);
            width = MAX_DIM;
          } else {
            width = Math.round((width * MAX_DIM) / height);
            height = MAX_DIM;
          }
        }
        const canvas = document.createElement("canvas");
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext("2d");
        if (ctx) {
          ctx.imageSmoothingQuality = "high";
          ctx.drawImage(img, 0, 0, width, height);
          canvas.toBlob(
            (b) => {
              const finalBlob = b || file;
              resolve({ blob: finalBlob, dataUrl: canvas.toDataURL("image/jpeg", 0.88) });
            },
            "image/jpeg",
            0.88
          );
        } else {
          resolve({ blob: file, dataUrl: objUrl });
        }
      };
      img.onerror = () => resolve({ blob: file, dataUrl: objUrl });
      img.src = objUrl;
    });
  };

  // 1. Bulk File Process & Index
  const handleBulkFilesSelect = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files.length > 0) {
      setBulkFiles(Array.from(e.target.files));
      setMessage("");
    }
  };

  const processBulkFiles = async () => {
    if (!bulkFiles.length || !albumId) return;
    setBulkProcessing(true);
    setBulkProgress({ current: 0, total: bulkFiles.length, facesFound: 0 });
    setMessage("");

    try {
      await loadFaceModels();
      let totalFaces = 0;
      const batchPayload: any[] = [];

      for (let i = 0; i < bulkFiles.length; i++) {
        const file = bulkFiles[i];
        setBulkProgress({ current: i + 1, total: bulkFiles.length, facesFound: totalFaces });

        try {
          const { dataUrl } = await optimizeImage(file);
          const imgElem = await faceapi.bufferToImage(
            await (await fetch(dataUrl)).blob()
          );

          // Detect faces with face-api
          const detections = await faceapi
            .detectAllFaces(imgElem, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.52 }))
            .withFaceLandmarks()
            .withFaceDescriptors();

          totalFaces += detections.length;

          // Upload image payload to /api/admin/upload
          const uploadRes = await fetch("/api/admin/upload", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              fileName: file.name,
              fileType: file.type || "image/jpeg",
              base64Data: dataUrl,
            }),
          });
          const uploadData = await uploadRes.json();
          const finalUrl = uploadData.url || dataUrl;

          batchPayload.push({
            photo_url: finalUrl,
            file_name: file.name,
            face_descriptors: detections.map((d) => Array.from(d.descriptor)),
          });
        } catch (err) {
          console.error(`Error processing file ${file.name}:`, err);
        }
      }

      // Batch save all indexed photos to database
      if (batchPayload.length > 0) {
        const saveRes = await fetch(`/api/admin/albums/${albumId}/photos`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ photos: batchPayload }),
        });
        if (!saveRes.ok) throw new Error("Failed to save photos to database");
      }

      setMessage(`Successfully indexed & saved ${batchPayload.length} photos with ${totalFaces} detected faces!`);
      setBulkFiles([]);
      await loadAlbum(albumId);
    } catch (err) {
      setMessage(err instanceof Error ? err.message : "Error during bulk processing");
    } finally {
      setBulkProcessing(false);
    }
  };

  // 2. Google Drive Links / Image URLs Process & Index
  const processDriveLinks = async () => {
    const rawLinks = driveLinksText
      .split(/\r?\n|,/)
      .map((l) => l.trim())
      .filter(Boolean);

    if (!rawLinks.length || !albumId) return;
    setLinksProcessing(true);
    setLinksProgress({ current: 0, total: rawLinks.length, facesFound: 0 });
    setMessage("");

    try {
      await loadFaceModels();
      let totalFaces = 0;
      const batchPayload: any[] = [];

      for (let i = 0; i < rawLinks.length; i++) {
        const link = rawLinks[i];
        setLinksProgress({ current: i + 1, total: rawLinks.length, facesFound: totalFaces });

        try {
          // If it's a Google Drive link, get high-res thumbnail/direct view URL
          const driveInfo = extractDriveId(link);
          const imageUrl = driveInfo?.id ? getDriveThumbnailUrl(driveInfo.id) : link;

          // Fetch and detect face
          const imgBlob = await (await fetch(imageUrl)).blob();
          const imgElem = await faceapi.bufferToImage(imgBlob);

          const detections = await faceapi
            .detectAllFaces(imgElem, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.52 }))
            .withFaceLandmarks()
            .withFaceDescriptors();

          totalFaces += detections.length;

          batchPayload.push({
            photo_url: imageUrl,
            file_name: driveInfo?.id ? `Drive_${driveInfo.id}.jpg` : link.split("/").pop() || "Photo.jpg",
            face_descriptors: detections.map((d) => Array.from(d.descriptor)),
          });
        } catch (err) {
          console.error(`Error processing link ${link}:`, err);
        }
      }

      if (batchPayload.length > 0) {
        const saveRes = await fetch(`/api/admin/albums/${albumId}/photos`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ photos: batchPayload }),
        });
        if (!saveRes.ok) throw new Error("Failed to save photos to database");
      }

      setMessage(`Successfully indexed & saved ${batchPayload.length} photos from links with ${totalFaces} detected faces!`);
      setDriveLinksText("");
      await loadAlbum(albumId);
    } catch (err) {
      setMessage(err instanceof Error ? err.message : "Error during link processing");
    } finally {
      setLinksProcessing(false);
    }
  };

  // 3. Single Photo Add
  const addSingleMedia = async () => {
    if (!mediaUrl || !albumId) return;
    setSaving(true);
    setMessage("");
    try {
      await loadFaceModels();
      const imageResponse = await fetch(mediaUrl);
      if (!imageResponse.ok) throw new Error("The uploaded image could not be read.");
      const image = await faceapi.bufferToImage(await imageResponse.blob());
      const detections = await faceapi
        .detectAllFaces(image, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.55 }))
        .withFaceLandmarks()
        .withFaceDescriptors();

      const response = await fetch(`/api/admin/albums/${albumId}/photos`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          photo_url: mediaUrl,
          file_name: mediaUrl.split("/").pop(),
          face_descriptors: detections.map((detection) => Array.from(detection.descriptor)),
        }),
      });
      const data = await response.json();
      if (!response.ok) throw new Error(data.error || "Unable to save media.");
      setMediaUrl("");
      setMessage(
        detections.length
          ? `Saved and indexed ${detections.length} face${detections.length === 1 ? "" : "s"}.`
          : "Saved. No face was found to index in this image."
      );
      await loadAlbum(albumId);
    } catch (error) {
      setMessage(error instanceof Error ? error.message : "Unable to process this media.");
    } finally {
      setSaving(false);
    }
  };

  const removeMedia = async (photoId: string) => {
    if (!albumId) return;
    const response = await fetch(`/api/admin/albums/${albumId}/photos?photoId=${photoId}`, {
      method: "DELETE",
    });
    if (response.ok) await loadAlbum(albumId);
    else setMessage("Unable to remove media.");
  };

  return (
    <div className="max-w-6xl space-y-8">
      {/* Header */}
      <div>
        <Link
          href="/admin/wedding-albums"
          className="text-xs text-[#c4a472] hover:underline inline-flex items-center gap-1 mb-4"
        >
          <ArrowLeft size={14} /> Back to all client albums
        </Link>
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <span className="text-[10px] uppercase tracking-widest text-[#00f0ff] font-bold block mb-1">
              AI GUEST FACIAL RECOGNITION INDEXER
            </span>
            <h1 className="font-serif text-3xl text-white font-light">
              {album?.title || "Album Media & Face Index"}
            </h1>
            <p className="text-xs text-zinc-400 mt-1">
              Index wedding photos so guests can take a 1-second selfie and instantly reveal every photo they appear in.
            </p>
          </div>

          <div className="flex items-center gap-2">
            <Link
              href={`/gallery/${album?.slug}`}
              target="_blank"
              className="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-semibold flex items-center gap-1.5 transition-all"
            >
              Live Guest View <ExternalLink size={12} />
            </Link>
          </div>
        </div>

        {/* Current Drive Folders Info Banner */}
        {album?.guest_google_drive_folder_id && (
          <div className="mt-4 p-4 rounded-2xl bg-white/[0.03] border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div className="flex items-center gap-2.5">
              <FolderSync size={16} className="text-emerald-400 shrink-0" />
              <div>
                <span className="text-zinc-400 block text-[11px]">Configured Guest Google Drive Link:</span>
                <span className="text-white font-medium truncate block max-w-md">
                  {album.guest_google_drive_folder_id}
                </span>
              </div>
            </div>
            <a
              href={album.guest_google_drive_folder_id}
              target="_blank"
              rel="noopener noreferrer"
              className="px-3.5 py-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 font-semibold text-xs inline-flex items-center gap-1 shrink-0 self-start sm:self-auto"
            >
              Open Drive Folder <ExternalLink size={12} />
            </a>
          </div>
        )}
      </div>

      {/* Indexer Tools Container */}
      <section className="bg-[#121214] border border-white/10 rounded-3xl p-6 sm:p-8 space-y-6">
        {/* Navigation Tabs */}
        <div className="flex items-center gap-2 border-b border-white/10 pb-4">
          <button
            onClick={() => setActiveTab("bulk")}
            className={`px-4 py-2 rounded-xl text-xs font-semibold tracking-wider uppercase transition-all flex items-center gap-2 ${
              activeTab === "bulk"
                ? "bg-[#00f0ff] text-black"
                : "bg-white/5 text-zinc-400 hover:text-white"
            }`}
          >
            <Layers size={14} /> Bulk Multi-Photo Uploader & AI Indexer
          </button>
          <button
            onClick={() => setActiveTab("drive_links")}
            className={`px-4 py-2 rounded-xl text-xs font-semibold tracking-wider uppercase transition-all flex items-center gap-2 ${
              activeTab === "drive_links"
                ? "bg-[#00f0ff] text-black"
                : "bg-white/5 text-zinc-400 hover:text-white"
            }`}
          >
            <LinkIcon size={14} /> Index from Drive Links / URLs
          </button>
          <button
            onClick={() => setActiveTab("single")}
            className={`px-4 py-2 rounded-xl text-xs font-semibold tracking-wider uppercase transition-all flex items-center gap-2 ${
              activeTab === "single"
                ? "bg-[#00f0ff] text-black"
                : "bg-white/5 text-zinc-400 hover:text-white"
            }`}
          >
            <ImagePlus size={14} /> Single Upload
          </button>
        </div>

        {/* Tab 1: Bulk Uploader */}
        {activeTab === "bulk" && (
          <div className="space-y-4">
            <div className="p-8 border-2 border-dashed border-white/15 hover:border-[#00f0ff]/50 rounded-2xl text-center bg-[#09090b]/50 transition-colors">
              <input
                type="file"
                multiple
                accept="image/*"
                ref={fileInputRef}
                onChange={handleBulkFilesSelect}
                className="hidden"
                id="bulk-photos-input"
              />
              <label
                htmlFor="bulk-photos-input"
                className="cursor-pointer flex flex-col items-center justify-center gap-3"
              >
                <div className="w-14 h-14 rounded-full bg-[#00f0ff]/10 text-[#00f0ff] flex items-center justify-center">
                  <Upload size={24} />
                </div>
                <div>
                  <span className="text-sm font-semibold text-white block">
                    Choose multiple photos or drop them here
                  </span>
                  <span className="text-xs text-zinc-400 mt-1 block">
                    Select 10, 50, or 200+ wedding photos from your computer or downloaded Google Drive folder
                  </span>
                </div>
              </label>

              {bulkFiles.length > 0 && (
                <div className="mt-4 pt-4 border-t border-white/10 flex items-center justify-center gap-3">
                  <span className="text-xs text-emerald-400 font-semibold">
                    ✓ {bulkFiles.length} photos selected ready for AI indexing
                  </span>
                  <button
                    onClick={() => setBulkFiles([])}
                    className="text-xs text-red-400 hover:underline"
                  >
                    Clear selection
                  </button>
                </div>
              )}
            </div>

            {bulkFiles.length > 0 && (
              <button
                type="button"
                onClick={processBulkFiles}
                disabled={bulkProcessing}
                className="px-6 py-3.5 rounded-xl bg-gradient-to-r from-[#00b4d8] to-[#00f0ff] text-black text-xs font-bold uppercase tracking-wider disabled:opacity-50 inline-flex items-center gap-2 shadow-lg shadow-[#00f0ff]/20"
              >
                {bulkProcessing ? (
                  <>
                    <Loader2 size={16} className="animate-spin" />
                    Indexing {bulkProgress.current} of {bulkProgress.total} ({bulkProgress.facesFound} faces found)…
                  </>
                ) : (
                  <>
                    <Sparkles size={16} /> Start AI Face Indexing ({bulkFiles.length} Photos)
                  </>
                )}
              </button>
            )}
          </div>
        )}

        {/* Tab 2: Drive Links / URLs */}
        {activeTab === "drive_links" && (
          <div className="space-y-4">
            <div>
              <label className="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                Paste Google Drive File Links or Direct Image URLs (One per line)
              </label>
              <textarea
                rows={6}
                value={driveLinksText}
                onChange={(e) => setDriveLinksText(e.target.value)}
                placeholder="https://drive.google.com/file/d/1aBcDeFg.../view&#10;https://drive.google.com/file/d/2hIjKlMn.../view"
                className="w-full bg-[#18181b] border border-white/10 rounded-xl p-4 text-white text-xs font-mono focus:outline-none focus:border-[#00f0ff]"
              />
              <p className="text-[11px] text-zinc-500 mt-1">
                Tip: Paste shareable links from your Google Drive. The system extracts each file ID, loads the image, detects faces, and indexes them.
              </p>
            </div>

            <button
              type="button"
              onClick={processDriveLinks}
              disabled={linksProcessing || !driveLinksText.trim()}
              className="px-6 py-3.5 rounded-xl bg-[#00f0ff] text-black text-xs font-bold uppercase tracking-wider disabled:opacity-50 inline-flex items-center gap-2 shadow-lg"
            >
              {linksProcessing ? (
                <>
                  <Loader2 size={16} className="animate-spin" />
                  Indexing {linksProgress.current} of {linksProgress.total}…
                </>
              ) : (
                <>
                  <Sparkles size={16} /> Process & Index Links
                </>
              )}
            </button>
          </div>
        )}

        {/* Tab 3: Single Upload */}
        {activeTab === "single" && (
          <div className="space-y-4">
            <MediaUploader
              label="Single Guest Photo"
              value={mediaUrl}
              onChange={setMediaUrl}
              helperText="Upload a photo from your computer or enter an image URL."
            />
            <button
              type="button"
              onClick={addSingleMedia}
              disabled={!mediaUrl || saving}
              className="px-5 py-3 rounded-xl bg-[#00f0ff] text-black text-xs font-bold uppercase tracking-wider disabled:opacity-50 inline-flex items-center gap-2"
            >
              {saving ? (
                <>
                  <Loader2 size={15} className="animate-spin" /> Indexing photo…
                </>
              ) : (
                <>
                  <ImagePlus size={15} /> Save & Index Single Photo
                </>
              )}
            </button>
          </div>
        )}

        {/* Status Message */}
        {message && (
          <div className="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs flex items-center gap-2">
            <CheckCircle2 size={15} /> {message}
          </div>
        )}
      </section>

      {/* Indexed Media Grid */}
      <section className="space-y-4">
        <div className="flex items-center justify-between">
          <div>
            <h2 className="font-serif text-2xl text-white font-light">
              Indexed Gallery Media ({album?.photos?.length || 0})
            </h2>
            <p className="text-xs text-zinc-400">
              Photos currently available for guest facial matching and gallery viewing.
            </p>
          </div>
        </div>

        {album?.photos?.length === 0 ? (
          <div className="p-12 text-center text-zinc-500 border border-white/5 rounded-2xl bg-[#121214]">
            No photos indexed for this album yet. Use the bulk uploader above to add guest photos.
          </div>
        ) : (
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            {album?.photos?.map((photo: any) => (
              <div
                key={photo.id}
                className="bg-[#121214] border border-white/10 rounded-2xl overflow-hidden group relative"
              >
                <img
                  src={photo.thumbnail_url || photo.photo_url}
                  alt={photo.file_name || "Album media"}
                  className="w-full aspect-square object-cover"
                />
                <div className="p-3 flex items-center justify-between gap-2">
                  <span className="text-[10px] text-emerald-400 font-semibold">
                    {photo.faces_count || 0} faces
                  </span>
                  <button
                    onClick={() => removeMedia(String(photo.id))}
                    className="text-red-400 hover:text-red-300 p-1"
                    aria-label="Remove photo"
                  >
                    <Trash2 size={14} />
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </section>
    </div>
  );
}

