"use client";

import React, { useState, useEffect } from "react";
import Link from "next/link";
import {
  Image,
  KeyRound,
  ExternalLink,
  Plus,
  Edit,
  Sparkles,
  ShieldCheck,
  FolderSync,
  CheckCircle2,
  X,
  Lock,
} from "lucide-react";
import MediaUploader from "@/components/admin/MediaUploader";


export default function AdminAlbumsPage() {
  const [albums, setAlbums] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [modalOpen, setModalOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [notification, setNotification] = useState<string | null>(null);

  // Form State
  const [editingId, setEditingId] = useState<string | null>(null);
  const [title, setTitle] = useState("");
  const [coupleNames, setCoupleNames] = useState("");
  const [location, setLocation] = useState("");
  const [pinCode, setPinCode] = useState("");
  const [googleDriveFolderId, setGoogleDriveFolderId] = useState("");
  const [guestGoogleDriveFolderId, setGuestGoogleDriveFolderId] = useState("");
  const [enableFaceAi, setEnableFaceAi] = useState(true);
  const [isPublic, setIsPublic] = useState(false);
  const [coverImage, setCoverImage] = useState("/images/1.jpg");

  useEffect(() => {
    fetchAlbums();
  }, []);

  const fetchAlbums = async () => {
    try {
      setLoading(true);
      const res = await fetch("/api/admin/albums");
      const data = await res.json();
      if (res.ok) {
        setAlbums(data.albums || []);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const openCreateModal = () => {
    setEditingId(null);
    setTitle("");
    setCoupleNames("");
    setLocation("");
    setPinCode("");
    setGoogleDriveFolderId("");
    setGuestGoogleDriveFolderId("");
    setEnableFaceAi(true);
    setIsPublic(false);
    setCoverImage("/images/1.jpg");
    setModalOpen(true);
  };

  const openEditModal = (album: any) => {
    setEditingId(String(album.id));
    setTitle(album.title || "");
    setCoupleNames(album.couple_names || "");
    setLocation(album.location || "");
    setPinCode(album.pin_code || "");
    setGoogleDriveFolderId(album.google_drive_folder_id || "");
    setGuestGoogleDriveFolderId(album.guest_google_drive_folder_id || "");
    setEnableFaceAi(Boolean(album.enable_face_ai));
    setIsPublic(Boolean(album.is_public));
    setCoverImage(album.cover_image || "/images/1.jpg");
    setModalOpen(true);
  };

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch("/api/admin/albums", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id: editingId,
          title,
          couple_names: coupleNames,
          location,
          pin_code: pinCode,
          google_drive_folder_id: googleDriveFolderId,
          guest_google_drive_folder_id: guestGoogleDriveFolderId,
          enable_face_ai: enableFaceAi,
          is_public: isPublic,
          cover_image: coverImage,
        }),
      });

      if (res.ok) {
        setNotification(editingId ? "Album updated successfully!" : "New VIP Portal created!");
        setTimeout(() => setNotification(null), 3000);
        setModalOpen(false);
        fetchAlbums();
      }
    } catch (err) {
      console.error("Save error:", err);
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
            VIP CLIENT PORTALS & CLOUDS
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Client Albums & AI Face Recognition</h1>
          <p className="text-xs text-zinc-400 mt-1">
            Configure Google Drive links, client master passcodes, and privacy-shielded guest facial AI.
          </p>
        </div>
        <button
          onClick={openCreateModal}
          className="bg-[#c4a472] hover:bg-[#b09060] text-black text-xs font-semibold uppercase tracking-wider py-3 px-5 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-[#c4a472]/20 shrink-0 self-start sm:self-auto"
        >
          <Plus size={16} /> Create VIP Portal
        </button>
      </div>

      {notification && (
        <div className="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs flex items-center gap-2">
          <CheckCircle2 size={16} /> {notification}
        </div>
      )}

      {/* Grid of Albums */}
      {loading ? (
        <div className="p-12 text-center text-zinc-500 text-sm">Loading client albums...</div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {albums.map((album) => (
            <div
              key={album.id}
              className="bg-[#121214] border border-white/5 rounded-3xl p-6 sm:p-8 flex flex-col justify-between hover:border-white/10 transition-all"
            >
              <div>
                <div className="flex items-center justify-between mb-4">
                  <span className="text-xs uppercase text-[#c4a472] font-semibold tracking-wider">
                    {album.couple_names || "VIP Wedding"}
                  </span>
                  <div className="flex items-center gap-2">
                    {album.pin_code ? (
                      <span className="bg-[#c4a472]/15 text-[#c4a472] border border-[#c4a472]/30 px-3 py-1 rounded-full text-[10px] font-bold uppercase flex items-center gap-1">
                        <KeyRound size={12} /> PIN: {album.pin_code}
                      </span>
                    ) : (
                      <span className="bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                        Open Access
                      </span>
                    )}
                  </div>
                </div>

                <h2 className="font-serif text-2xl text-white font-light mb-2">{album.title}</h2>
                <p className="text-xs text-zinc-400 mb-6">
                  📍 {album.location || "Destination"} · {album._count?.photos || 0} Photos & Videos
                </p>

                {/* Google Drive Statuses */}
                <div className="space-y-2 mb-6 text-xs">
                  <div className="bg-[#18181b] p-3 rounded-xl border border-white/5">
                    <span className="text-zinc-400 font-semibold block mb-0.5">Client Master Drive Link:</span>
                    <span className="text-zinc-300 truncate block">
                      {album.google_drive_folder_id || "Not configured yet"}
                    </span>
                  </div>

                  <div className="bg-[#18181b] p-3 rounded-xl border border-white/5 flex items-center justify-between">
                    <div>
                      <span className="text-zinc-400 font-semibold block mb-0.5">Guest Facial AI Pool:</span>
                      <span className="text-zinc-300 truncate block">
                        {album.guest_google_drive_folder_id || "Default album media"}
                      </span>
                    </div>
                    {album.enable_face_ai ? (
                      <span className="bg-[#00f0ff]/10 text-[#00f0ff] px-2.5 py-1 rounded-full text-[10px] font-bold uppercase shrink-0">
                        AI Active
                      </span>
                    ) : (
                      <span className="bg-zinc-800 text-zinc-400 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase shrink-0">
                        AI Off
                      </span>
                    )}
                  </div>
                </div>
              </div>

              <div className="pt-4 border-t border-white/5 flex items-center justify-between text-xs">
                <Link
                  href={`/gallery/${album.slug}`}
                  target="_blank"
                  className="text-[#00f0ff] hover:underline flex items-center gap-1 font-semibold"
                >
                  View Client Portal <ExternalLink size={12} />
                </Link>

                <button
                  onClick={() => openEditModal(album)}
                  className="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-xl text-white font-medium flex items-center gap-1.5 transition-colors"
                >
                  <Edit size={14} /> Configure & Drive Links
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Modal: Create / Edit */}
      {modalOpen && (
        <div className="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
          <div className="bg-[#121214] border border-white/10 rounded-3xl p-6 sm:p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto relative shadow-2xl">
            <button
              onClick={() => setModalOpen(false)}
              className="absolute top-6 right-6 text-zinc-400 hover:text-white"
            >
              <X size={20} />
            </button>

            <h2 className="font-serif text-2xl text-white font-light mb-1">
              {editingId ? "Configure VIP Portal & Drive Links" : "Create New VIP Wedding Portal"}
            </h2>
            <p className="text-xs text-zinc-400 mb-6">
              Paste Google Drive links. All downloads will be proxied directly through your domain without exposing Google Drive.
            </p>

            <form onSubmit={handleSave} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1">
                  Album Title *
                </label>
                <input
                  type="text"
                  value={title}
                  onChange={(e) => setTitle(e.target.value)}
                  required
                  placeholder="e.g. Aditi & Kabir - Royal Udaipur Wedding"
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1">
                    Couple Names
                  </label>
                  <input
                    type="text"
                    value={coupleNames}
                    onChange={(e) => setCoupleNames(e.target.value)}
                    placeholder="Aditi & Kabir"
                    className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1">
                    Location / Destination
                  </label>
                  <input
                    type="text"
                    value={location}
                    onChange={(e) => setLocation(e.target.value)}
                    placeholder="Udaipur, Rajasthan"
                    className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                  />
                </div>
              </div>

              {/* Master Password / PIN */}
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-[#c4a472] mb-1 flex items-center gap-1">
                  <Lock size={12} /> Client Master Download PIN / Password
                </label>
                <input
                  type="text"
                  value={pinCode}
                  onChange={(e) => setPinCode(e.target.value)}
                  placeholder="e.g. 2026 or KABIRADITI"
                  className="w-full bg-[#18181b] border border-[#c4a472]/40 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
                <p className="text-[10px] text-zinc-500 mt-1">
                  The client uses this password to unlock & download the complete 4K master archive.
                </p>
              </div>

              {/* Client Master Google Drive Link */}
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1 flex items-center gap-1">
                  <FolderSync size={12} className="text-[#00f0ff]" /> Client Master Google Drive Link
                </label>
                <input
                  type="text"
                  value={googleDriveFolderId}
                  onChange={(e) => setGoogleDriveFolderId(e.target.value)}
                  placeholder="https://drive.google.com/drive/folders/... or Drive File Link"
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#00f0ff]"
                />
                <p className="text-[10px] text-zinc-500 mt-1">
                  Raw Google Drive link is NEVER shown to users. Downloads will be proxied via paneventz.in.
                </p>
              </div>

              {/* Guest Google Drive Link */}
              <div>
                <label className="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1 flex items-center gap-1">
                  <Sparkles size={12} className="text-emerald-400" /> Guest AI Facial Photo Drive Link
                </label>
                <input
                  type="text"
                  value={guestGoogleDriveFolderId}
                  onChange={(e) => setGuestGoogleDriveFolderId(e.target.value)}
                  placeholder="https://drive.google.com/drive/folders/..."
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-emerald-400"
                />
                <p className="text-[10px] text-zinc-500 mt-1">
                  Photos here are scanned by AI. Guests can only view and download their own matched images.
                </p>
              </div>

              {/* Cover Image Uploader */}
              <MediaUploader
                label="Album Cover Photo"
                value={coverImage}
                onChange={(url) => setCoverImage(url)}
                helperText="Upload a high-res cover photo for the wedding heirloom portal."
              />

              {/* Toggles */}
              <div className="pt-2 flex items-center justify-between border-t border-white/5 text-xs">
                <label className="flex items-center gap-2 cursor-pointer text-zinc-300">
                  <input
                    type="checkbox"
                    checked={enableFaceAi}
                    onChange={(e) => setEnableFaceAi(e.target.checked)}
                    className="accent-[#00f0ff]"
                  />
                  <span>Enable Guest AI Facial Recognition</span>
                </label>
              </div>

              <div className="pt-4 flex items-center justify-end gap-3">
                <button
                  type="button"
                  onClick={() => setModalOpen(false)}
                  className="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-white text-xs rounded-xl transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={saving}
                  className="px-6 py-2.5 bg-[#c4a472] hover:bg-[#b09060] text-black font-semibold text-xs uppercase tracking-wider rounded-xl transition-all disabled:opacity-50"
                >
                  {saving ? "Saving..." : "Save Configuration"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}