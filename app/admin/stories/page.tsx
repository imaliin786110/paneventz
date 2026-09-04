"use client";

import React, { useState, useEffect } from "react";
import { Camera, Plus, Edit, Trash2, CheckCircle2, X } from "lucide-react";
import MediaUploader from "@/components/admin/MediaUploader";


export default function AdminStoriesPage() {
  const [stories, setStories] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [modalOpen, setModalOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [notification, setNotification] = useState<string | null>(null);

  const [editingId, setEditingId] = useState<string | null>(null);
  const [coupleName, setCoupleName] = useState("");
  const [location, setLocation] = useState("");
  const [coverImage, setCoverImage] = useState("/images/1.jpg");
  const [description, setDescription] = useState("");

  useEffect(() => {
    fetchStories();
  }, []);

  const fetchStories = async () => {
    try {
      setLoading(true);
      const res = await fetch("/api/admin/content/stories");
      const data = await res.json();
      if (res.ok) setStories(data.data || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const openCreateModal = () => {
    setEditingId(null);
    setCoupleName("");
    setLocation("");
    setCoverImage("/images/1.jpg");
    setDescription("");
    setModalOpen(true);
  };

  const openEditModal = (story: any) => {
    setEditingId(String(story.id));
    setCoupleName(story.couple_name || "");
    setLocation(story.location || "");
    setCoverImage(story.cover_image || "/images/1.jpg");
    setDescription(story.description || "");
    setModalOpen(true);
  };

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch("/api/admin/content/stories", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id: editingId,
          couple_name: coupleName,
          location,
          cover_image: coverImage,
          description,
        }),
      });

      if (res.ok) {
        setNotification(editingId ? "Story updated!" : "New Wedding Story created!");
        setTimeout(() => setNotification(null), 3000);
        setModalOpen(false);
        fetchStories();
      }
    } catch (err) {
      console.error("Save story error:", err);
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id: string) => {
    if (!confirm("Are you sure you want to delete this story?")) return;
    try {
      const res = await fetch(`/api/admin/content/stories?id=${id}`, { method: "DELETE" });
      if (res.ok) {
        setNotification("Story deleted.");
        setTimeout(() => setNotification(null), 3000);
        fetchStories();
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
            FEATURED STORIES CMS
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Wedding Stories & Photo Media</h1>
          <p className="text-xs text-zinc-400 mt-1">
            Manage your featured couple stories, cover images, location tags, and narrative copy.
          </p>
        </div>
        <button
          onClick={openCreateModal}
          className="bg-[#c4a472] hover:bg-[#b09060] text-black text-xs font-semibold uppercase tracking-wider py-3 px-5 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-[#c4a472]/20 shrink-0 self-start sm:self-auto"
        >
          <Plus size={16} /> Add New Story
        </button>
      </div>

      {notification && (
        <div className="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs flex items-center gap-2">
          <CheckCircle2 size={16} /> {notification}
        </div>
      )}

      {loading ? (
        <div className="p-12 text-center text-zinc-500 text-sm">Loading stories...</div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {stories.map((story) => (
            <div
              key={story.id}
              className="bg-[#121214] border border-white/5 rounded-3xl overflow-hidden flex flex-col justify-between hover:border-white/10 transition-all"
            >
              <div className="relative aspect-[4/3] bg-zinc-900">
                <img
                  src={story.cover_image || "/images/1.jpg"}
                  alt={story.couple_name}
                  className="w-full h-full object-cover"
                />
              </div>

              <div className="p-6">
                <span className="text-xs uppercase text-[#c4a472] font-semibold tracking-wider block mb-1">
                  📍 {story.location || "Destination Wedding"}
                </span>
                <h3 className="font-serif text-2xl text-white font-light mb-2">{story.couple_name}</h3>
                <p className="text-xs text-zinc-400 line-clamp-2 mb-6">
                  {story.description || "A breathtaking celebration of timeless love."}
                </p>

                <div className="pt-4 border-t border-white/5 flex items-center justify-between">
                  <button
                    onClick={() => openEditModal(story)}
                    className="text-xs text-zinc-300 hover:text-white flex items-center gap-1"
                  >
                    <Edit size={14} /> Edit
                  </button>
                  <button
                    onClick={() => handleDelete(String(story.id))}
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
              {editingId ? "Edit Story" : "Create New Story"}
            </h2>

            <form onSubmit={handleSave} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Couple Name *
                </label>
                <input
                  type="text"
                  value={coupleName}
                  onChange={(e) => setCoupleName(e.target.value)}
                  required
                  placeholder="e.g. Aditi & Kabir"
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Location / Venue
                </label>
                <input
                  type="text"
                  value={location}
                  onChange={(e) => setLocation(e.target.value)}
                  placeholder="The Oberoi Udaivilas, Udaipur"
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <MediaUploader
                label="Cover Image"
                value={coverImage}
                onChange={(url) => setCoverImage(url)}
                helperText="Upload a high-res wedding cover photo from your device, or paste a URL."
              />

              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Story Narrative Description
                </label>
                <textarea
                  value={description}
                  onChange={(e) => setDescription(e.target.value)}
                  rows={4}
                  placeholder="A magical royal celebration set against the shimmering waters of Lake Pichola..."
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

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
                  {saving ? "Saving..." : "Save Story"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}