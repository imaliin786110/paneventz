"use client";

import React, { useState, useEffect } from "react";
import { Film, Plus, Edit, Trash2, CheckCircle2, X, Play } from "lucide-react";

export default function AdminFilmsPage() {
  const [films, setFilms] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [modalOpen, setModalOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [notification, setNotification] = useState<string | null>(null);

  const [editingId, setEditingId] = useState<string | null>(null);
  const [title, setTitle] = useState("");
  const [coupleName, setCoupleName] = useState("");
  const [location, setLocation] = useState("");
  const [videoUrl, setVideoUrl] = useState("");
  const [thumbnail, setThumbnail] = useState("/images/hero.jpg");
  const [description, setDescription] = useState("");
  const [isFeatured, setIsFeatured] = useState(false);

  useEffect(() => {
    fetchFilms();
  }, []);

  const fetchFilms = async () => {
    try {
      setLoading(true);
      const res = await fetch("/api/admin/content/films");
      const data = await res.json();
      if (res.ok) setFilms(data.data || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const openCreateModal = () => {
    setEditingId(null);
    setTitle("");
    setCoupleName("");
    setLocation("");
    setVideoUrl("");
    setThumbnail("/images/hero.jpg");
    setDescription("");
    setIsFeatured(false);
    setModalOpen(true);
  };

  const openEditModal = (film: any) => {
    setEditingId(String(film.id));
    setTitle(film.title || "");
    setCoupleName(film.couple_name || "");
    setLocation(film.location || "");
    setVideoUrl(film.video_url || "");
    setThumbnail(film.thumbnail || "/images/hero.jpg");
    setDescription(film.description || "");
    setIsFeatured(Boolean(film.is_featured));
    setModalOpen(true);
  };

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch("/api/admin/content/films", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id: editingId,
          title,
          couple_name: coupleName,
          location,
          video_url: videoUrl,
          thumbnail,
          description,
          is_featured: isFeatured,
        }),
      });

      if (res.ok) {
        setNotification(editingId ? "Film updated successfully!" : "New Cinema Film added!");
        setTimeout(() => setNotification(null), 3000);
        setModalOpen(false);
        fetchFilms();
      }
    } catch (err) {
      console.error("Save film error:", err);
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id: string) => {
    if (!confirm("Are you sure you want to delete this film?")) return;
    try {
      const res = await fetch(`/api/admin/content/films?id=${id}`, { method: "DELETE" });
      if (res.ok) {
        setNotification("Film deleted.");
        setTimeout(() => setNotification(null), 3000);
        fetchFilms();
      }
    } catch (err) {
      console.error("Delete error:", err);
    }
  };

  return (
    <div className="space-y-8">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
            CINEMATOGRAPHY CMS
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Cinema Films & 4K Teasers</h1>
          <p className="text-xs text-zinc-400 mt-1">
            Manage your wedding films, video streaming URLs, thumbnails, and featured highlights.
          </p>
        </div>
        <button
          onClick={openCreateModal}
          className="bg-[#c4a472] hover:bg-[#b09060] text-black text-xs font-semibold uppercase tracking-wider py-3 px-5 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-[#c4a472]/20 shrink-0 self-start sm:self-auto"
        >
          <Plus size={16} /> Add New Film
        </button>
      </div>

      {notification && (
        <div className="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs flex items-center gap-2">
          <CheckCircle2 size={16} /> {notification}
        </div>
      )}

      {loading ? (
        <div className="p-12 text-center text-zinc-500 text-sm">Loading cinema films...</div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {films.map((film) => (
            <div
              key={film.id}
              className="bg-[#121214] border border-white/5 rounded-3xl overflow-hidden flex flex-col justify-between hover:border-white/10 transition-all"
            >
              <div className="relative aspect-video bg-black">
                <img
                  src={film.thumbnail || "/images/hero.jpg"}
                  alt={film.title}
                  className="w-full h-full object-cover opacity-80"
                />
                <div className="absolute inset-0 flex items-center justify-center">
                  <div className="w-12 h-12 rounded-full bg-[#c4a472]/30 border border-[#c4a472] flex items-center justify-center text-white">
                    <Play size={18} className="fill-current ml-0.5" />
                  </div>
                </div>
                {film.is_featured && (
                  <span className="absolute top-3 left-3 bg-[#c4a472] text-black text-[9px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                    Featured 4K
                  </span>
                )}
              </div>

              <div className="p-6">
                <span className="text-xs uppercase text-[#c4a472] font-semibold tracking-wider block mb-1">
                  {film.couple_name || "Wedding Film"}
                </span>
                <h3 className="font-serif text-xl text-white font-light mb-2">{film.title}</h3>
                <p className="text-xs text-zinc-400 line-clamp-2 mb-4">
                  {film.description || "Cinematic wedding heirloom film."}
                </p>
                <div className="text-[11px] text-zinc-500 truncate mb-4">
                  <strong>Video URL:</strong> {film.video_url}
                </div>

                <div className="pt-4 border-t border-white/5 flex items-center justify-between">
                  <button
                    onClick={() => openEditModal(film)}
                    className="text-xs text-zinc-300 hover:text-white flex items-center gap-1"
                  >
                    <Edit size={14} /> Edit
                  </button>
                  <button
                    onClick={() => handleDelete(String(film.id))}
                    className="text-xs text-red-400 hover:text-red-300 flex items-center gap-1"
                  >
                    <Trash2 size={14} /> Delete
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}

      {modalOpen && (
        <div className="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
          <div className="bg-[#121214] border border-white/10 rounded-3xl p-6 sm:p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto relative shadow-2xl">
            <button
              onClick={() => setModalOpen(false)}
              className="absolute top-6 right-6 text-zinc-400 hover:text-white"
            >
              <X size={20} />
            </button>

            <h2 className="font-serif text-2xl text-white font-light mb-4">
              {editingId ? "Edit Cinema Film" : "Add New Cinema Film"}
            </h2>

            <form onSubmit={handleSave} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Film Title *
                </label>
                <input
                  type="text"
                  value={title}
                  onChange={(e) => setTitle(e.target.value)}
                  required
                  placeholder="e.g. Royal Symphony - Aditi & Kabir"
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                    Couple Name
                  </label>
                  <input
                    type="text"
                    value={coupleName}
                    onChange={(e) => setCoupleName(e.target.value)}
                    placeholder="Aditi & Kabir"
                    className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                    Location
                  </label>
                  <input
                    type="text"
                    value={location}
                    onChange={(e) => setLocation(e.target.value)}
                    placeholder="Udaipur"
                    className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Video URL (MP4, YouTube, or Vimeo) *
                </label>
                <input
                  type="text"
                  value={videoUrl}
                  onChange={(e) => setVideoUrl(e.target.value)}
                  required
                  placeholder="/storage/stories/01M0Z2H4038Y6XPC4KQHA2VN63.mp4 or YouTube link"
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Thumbnail Image URL
                </label>
                <input
                  type="text"
                  value={thumbnail}
                  onChange={(e) => setThumbnail(e.target.value)}
                  placeholder="/images/hero.jpg"
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Description
                </label>
                <textarea
                  value={description}
                  onChange={(e) => setDescription(e.target.value)}
                  rows={2}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <label className="flex items-center gap-2 cursor-pointer text-xs text-zinc-300">
                <input
                  type="checkbox"
                  checked={isFeatured}
                  onChange={(e) => setIsFeatured(e.target.checked)}
                  className="accent-[#c4a472]"
                />
                <span>Feature as Homepage Cinema Highlight</span>
              </label>

              <div className="pt-4 flex items-center justify-end gap-3">
                <button
                  type="button"
                  onClick={() => setModalOpen(false)}
                  className="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-white text-xs rounded-xl"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={saving}
                  className="px-6 py-2.5 bg-[#c4a472] hover:bg-[#b09060] text-black font-semibold text-xs uppercase tracking-wider rounded-xl transition-all"
                >
                  {saving ? "Saving..." : "Save Film"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
