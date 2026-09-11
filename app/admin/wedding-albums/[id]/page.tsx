"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import * as faceapi from "face-api.js";
import { ArrowLeft, CheckCircle2, ImagePlus, Loader2, Trash2 } from "lucide-react";
import MediaUploader from "@/components/admin/MediaUploader";

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
  const loadAlbum = async (id: string) => {
    const response = await fetch(`/api/admin/albums/${id}/photos`);
    const data = await response.json();
    if (response.ok) setAlbum(data.album); else setMessage(data.error || "Unable to load this album.");
  };
  useEffect(() => { params.then(({ id }) => { setAlbumId(id); loadAlbum(id); }); }, [params]);
  const addMedia = async () => {
    if (!mediaUrl || !albumId) return;
    setSaving(true); setMessage("");
    try {
      await loadFaceModels();
      const imageResponse = await fetch(mediaUrl);
      if (!imageResponse.ok) throw new Error("The uploaded image could not be read.");
      const image = await faceapi.bufferToImage(await imageResponse.blob());
      const detections = await faceapi.detectAllFaces(image, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.55 })).withFaceLandmarks().withFaceDescriptors();
      const response = await fetch(`/api/admin/albums/${albumId}/photos`, { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ photo_url: mediaUrl, file_name: mediaUrl.split("/").pop(), face_descriptors: detections.map((detection) => Array.from(detection.descriptor)) }) });
      const data = await response.json();
      if (!response.ok) throw new Error(data.error || "Unable to save media.");
      setMediaUrl("");
      setMessage(detections.length ? `Saved and indexed ${detections.length} face${detections.length === 1 ? "" : "s"}.` : "Saved. No face was found to index in this image.");
      await loadAlbum(albumId);
    } catch (error) { setMessage(error instanceof Error ? error.message : "Unable to process this media."); }
    finally { setSaving(false); }
  };
  const removeMedia = async (photoId: string) => { if (!albumId) return; const response = await fetch(`/api/admin/albums/${albumId}/photos?photoId=${photoId}`, { method: "DELETE" }); if (response.ok) await loadAlbum(albumId); else setMessage("Unable to remove media."); };
  return <div className="max-w-6xl space-y-8">
    <div><Link href="/admin/wedding-albums" className="text-xs text-[#c4a472] hover:underline inline-flex items-center gap-1 mb-4"><ArrowLeft size={14} /> All client albums</Link><h1 className="font-serif text-3xl text-white font-light">{album?.title || "Album media"}</h1><p className="text-xs text-zinc-400 mt-2">Upload guest photos here. Each uploaded image is indexed in this browser; the guest selfie is never uploaded or stored.</p></div>
    <section className="bg-[#121214] border border-white/10 rounded-3xl p-6 sm:p-8 space-y-5"><div className="flex items-center gap-2 text-[#00f0ff]"><ImagePlus size={18} /><h2 className="text-sm font-semibold">Add and index a guest photo</h2></div><MediaUploader label="Guest photo" value={mediaUrl} onChange={setMediaUrl} helperText="Use images uploaded here or a CORS-enabled URL. Face indexing runs locally before the image record is saved." /><button type="button" onClick={addMedia} disabled={!mediaUrl || saving} className="px-5 py-3 rounded-xl bg-[#00f0ff] text-black text-xs font-bold uppercase tracking-wider disabled:opacity-50 inline-flex items-center gap-2">{saving ? <><Loader2 size={15} className="animate-spin" /> Indexing photo…</> : <><ImagePlus size={15} /> Save & index photo</>}</button>{message && <p className="text-xs text-zinc-300 flex items-center gap-2"><CheckCircle2 size={14} className="text-emerald-400" /> {message}</p>}</section>
    <section className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">{album?.photos?.map((photo: any) => <div key={photo.id} className="bg-[#121214] border border-white/10 rounded-2xl overflow-hidden group"><img src={photo.thumbnail_url || photo.photo_url} alt={photo.file_name || "Album media"} className="w-full aspect-square object-cover" /><div className="p-3 flex items-center justify-between gap-2"><span className="text-[10px] text-emerald-400">{photo.faces_count || 0} faces indexed</span><button onClick={() => removeMedia(String(photo.id))} className="text-red-400 hover:text-red-300" aria-label="Remove photo"><Trash2 size={15} /></button></div></div>)}</section>
  </div>;
}
