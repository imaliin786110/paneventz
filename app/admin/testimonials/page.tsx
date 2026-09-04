"use client";

import React, { useState, useEffect } from "react";
import { Plus, Edit, Trash2, CheckCircle2, X, Star } from "lucide-react";
import MediaUploader from "@/components/admin/MediaUploader";


export default function AdminTestimonialsPage() {
  const [testimonials, setTestimonials] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [modalOpen, setModalOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [notification, setNotification] = useState<string | null>(null);

  const [editingId, setEditingId] = useState<string | null>(null);
  const [coupleName, setCoupleName] = useState("");
  const [location, setLocation] = useState("");
  const [rating, setRating] = useState(5);
  const [review, setReview] = useState("");
  const [photo, setPhoto] = useState("/images/1.jpg");

  useEffect(() => {
    fetchTestimonials();
  }, []);

  const fetchTestimonials = async () => {
    try {
      setLoading(true);
      const res = await fetch("/api/admin/content/testimonials");
      const data = await res.json();
      if (res.ok) setTestimonials(data.data || []);
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
    setRating(5);
    setReview("");
    setPhoto("/images/1.jpg");
    setModalOpen(true);
  };

  const openEditModal = (item: any) => {
    setEditingId(String(item.id));
    setCoupleName(item.couple_name || "");
    setLocation(item.location || "");
    setRating(item.rating || 5);
    setReview(item.review || "");
    setPhoto(item.photo || "/images/1.jpg");
    setModalOpen(true);
  };

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch("/api/admin/content/testimonials", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id: editingId,
          couple_name: coupleName,
          location,
          rating,
          review,
          photo,
        }),
      });

      if (res.ok) {
        setNotification(editingId ? "Review updated!" : "New Review added!");
        setTimeout(() => setNotification(null), 3000);
        setModalOpen(false);
        fetchTestimonials();
      }
    } catch (err) {
      console.error("Save testimonial error:", err);
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id: string) => {
    if (!confirm("Are you sure you want to delete this testimonial?")) return;
    try {
      const res = await fetch(`/api/admin/content/testimonials?id=${id}`, { method: "DELETE" });
      if (res.ok) {
        setNotification("Testimonial deleted.");
        setTimeout(() => setNotification(null), 3000);
        fetchTestimonials();
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
            CLIENT REVIEWS CMS
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Testimonials & Couple Reviews</h1>
          <p className="text-xs text-zinc-400 mt-1">
            Manage 5-star couple reviews, quotes, and wedding portrait thumbnails.
          </p>
        </div>
        <button
          onClick={openCreateModal}
          className="bg-[#c4a472] hover:bg-[#b09060] text-black text-xs font-semibold uppercase tracking-wider py-3 px-5 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-[#c4a472]/20 shrink-0 self-start sm:self-auto"
        >
          <Plus size={16} /> Add New Review
        </button>
      </div>

      {notification && (
        <div className="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs flex items-center gap-2">
          <CheckCircle2 size={16} /> {notification}
        </div>
      )}

      {loading ? (
        <div className="p-12 text-center text-zinc-500 text-sm">Loading testimonials...</div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {testimonials.map((item) => (
            <div
              key={item.id}
              className="bg-[#121214] border border-white/5 rounded-3xl p-6 sm:p-8 flex flex-col justify-between hover:border-white/10 transition-all"
            >
              <div>
                <div className="flex items-center gap-1 text-[#c4a472] mb-3">
                  {[...Array(item.rating || 5)].map((_, i) => (
                    <Star key={i} size={14} className="fill-current" />
                  ))}
                </div>
                <p className="text-xs text-zinc-300 italic mb-6 leading-relaxed">"{item.review}"</p>
                <div className="flex items-center gap-3">
                  <img
                    src={item.photo || "/images/1.jpg"}
                    alt={item.couple_name}
                    className="w-10 h-10 rounded-full object-cover border border-[#c4a472]/40"
                  />
                  <div>
                    <h4 className="font-serif text-sm text-white font-medium">{item.couple_name}</h4>
                    <span className="text-[10px] text-zinc-400">{item.location || "Destination Wedding"}</span>
                  </div>
                </div>
              </div>

              <div className="pt-4 mt-6 border-t border-white/5 flex items-center justify-between">
                <button
                  onClick={() => openEditModal(item)}
                  className="text-xs text-zinc-300 hover:text-white flex items-center gap-1"
                >
                  <Edit size={14} /> Edit
                </button>
                <button
                  onClick={() => handleDelete(String(item.id))}
                  className="text-xs text-red-400 hover:text-red-300 flex items-center gap-1"
                >
                  <Trash2 size={14} /> Delete
                </button>
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
              {editingId ? "Edit Couple Review" : "Add New Testimonial"}
            </h2>

            <form onSubmit={handleSave} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                    Couple Names *
                  </label>
                  <input
                    type="text"
                    value={coupleName}
                    onChange={(e) => setCoupleName(e.target.value)}
                    required
                    placeholder="Aditi & Kabir"
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
                    placeholder="Udaipur Palace"
                    className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Star Rating (1 - 5)
                </label>
                <input
                  type="number"
                  min={1}
                  max={5}
                  value={rating}
                  onChange={(e) => setRating(Number(e.target.value))}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Review Text *
                </label>
                <textarea
                  value={review}
                  onChange={(e) => setReview(e.target.value)}
                  required
                  rows={4}
                  placeholder="Their cinematography captured our wedding emotions flawlessly..."
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <MediaUploader
                label="Couple Portrait Photo"
                value={photo}
                onChange={(url) => setPhoto(url)}
                helperText="Upload couple portrait from your device, or paste a URL."
              />

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
                  {saving ? "Saving..." : "Save Review"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
