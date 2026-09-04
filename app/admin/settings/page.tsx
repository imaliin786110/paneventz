"use client";

import React, { useState, useEffect } from "react";
import {
  Settings,
  Sparkles,
  Layers,
  Phone,
  Sliders,
  CheckCircle2,
  Save,
  Image as ImageIcon,
  Video,
} from "lucide-react";
import MediaUploader from "@/components/admin/MediaUploader";

export default function AdminSettingsPage() {
  const [activeTab, setActiveTab] = useState<"hero" | "stats" | "colorgrade" | "contact">("hero");
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [notification, setNotification] = useState<string | null>(null);

  const [formData, setFormData] = useState<any>({
    studio_name: "",
    tagline: "",
    hero_eyebrow: "",
    hero_heading: "",
    hero_description: "",
    hero_button_label: "",
    hero_button_url: "",
    hero_background_image: "",
    hero_background_video: "",
    stat_1_number: 250,
    stat_1_suffix: "+",
    stat_1_label: "Weddings Captured",
    stat_2_number: 10,
    stat_2_suffix: "+",
    stat_2_label: "Years of Craft",
    stat_3_number: 35,
    stat_3_suffix: "+",
    stat_3_label: "Destination Cities",
    stat_4_number: 100,
    stat_4_suffix: "%",
    stat_4_label: "Five-Star Experience",
    color_grade_heading: "",
    color_grade_description: "",
    color_grade_before_image: "",
    color_grade_after_image: "",
    email: "",
    phone: "",
    whatsapp: "",
    instagram_url: "",
    youtube_url: "",
    footer_address: "",
    footer_copyright: "",
  });

  useEffect(() => {
    fetchSettings();
  }, []);

  const fetchSettings = async () => {
    try {
      setLoading(true);
      const res = await fetch("/api/admin/settings");
      const data = await res.json();
      if (res.ok && data.setting) {
        setFormData(data.setting);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleMediaChange = (fieldName: string, url: string) => {
    setFormData((prev: any) => ({ ...prev, [fieldName]: url }));
  };

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch("/api/admin/settings", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });

      if (res.ok) {
        setNotification("Website content & media updated successfully!");
        setTimeout(() => setNotification(null), 3500);
      }
    } catch (err) {
      console.error("Save error:", err);
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return <div className="p-12 text-center text-zinc-500 text-sm">Loading global settings...</div>;
  }

  return (
    <div className="space-y-8 max-w-5xl">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
            GLOBAL SITE CMS
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Website Content & Media Manager</h1>
          <p className="text-xs text-zinc-400 mt-1">
            Upload images, videos, and customize every headline and stat counter directly from your device.
          </p>
        </div>

        <button
          onClick={handleSave}
          disabled={saving}
          className="bg-[#c4a472] hover:bg-[#b09060] text-black text-xs font-semibold uppercase tracking-wider py-3 px-6 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-[#c4a472]/20 shrink-0 disabled:opacity-50"
        >
          <Save size={16} />
          <span>{saving ? "Saving Changes..." : "Save All Changes"}</span>
        </button>
      </div>

      {notification && (
        <div className="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs flex items-center gap-2">
          <CheckCircle2 size={16} /> {notification}
        </div>
      )}

      {/* Tabs */}
      <div className="flex flex-wrap items-center gap-2 border-b border-white/5 pb-4">
        <button
          onClick={() => setActiveTab("hero")}
          className={`px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold transition-all flex items-center gap-2 ${
            activeTab === "hero"
              ? "bg-[#c4a472] text-black"
              : "bg-white/5 text-zinc-400 hover:text-white"
          }`}
        >
          <Layers size={14} /> Hero Banner & Media
        </button>
        <button
          onClick={() => setActiveTab("stats")}
          className={`px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold transition-all flex items-center gap-2 ${
            activeTab === "stats"
              ? "bg-[#c4a472] text-black"
              : "bg-white/5 text-zinc-400 hover:text-white"
          }`}
        >
          <Sparkles size={14} /> Counter Statistics
        </button>
        <button
          onClick={() => setActiveTab("colorgrade")}
          className={`px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold transition-all flex items-center gap-2 ${
            activeTab === "colorgrade"
              ? "bg-[#c4a472] text-black"
              : "bg-white/5 text-zinc-400 hover:text-white"
          }`}
        >
          <Sliders size={14} /> Color Grading Slider
        </button>
        <button
          onClick={() => setActiveTab("contact")}
          className={`px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold transition-all flex items-center gap-2 ${
            activeTab === "contact"
              ? "bg-[#c4a472] text-black"
              : "bg-white/5 text-zinc-400 hover:text-white"
          }`}
        >
          <Phone size={14} /> Studio & Social Links
        </button>
      </div>

      {/* Form Content */}
      <form onSubmit={handleSave} className="bg-[#121214] border border-white/5 rounded-3xl p-6 sm:p-8 space-y-6">
        {/* 1. Hero Tab */}
        {activeTab === "hero" && (
          <div className="space-y-6">
            <h2 className="text-sm font-semibold uppercase tracking-wider text-[#c4a472]">
              Homepage Hero Section & Media
            </h2>

            <div>
              <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                Eyebrow Subheading
              </label>
              <input
                type="text"
                name="hero_eyebrow"
                value={formData.hero_eyebrow || ""}
                onChange={handleChange}
                className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
              />
            </div>

            <div>
              <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                Main Hero Headline
              </label>
              <input
                type="text"
                name="hero_heading"
                value={formData.hero_heading || ""}
                onChange={handleChange}
                className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
              />
            </div>

            <div>
              <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                Hero Description Text
              </label>
              <textarea
                name="hero_description"
                rows={3}
                value={formData.hero_description || ""}
                onChange={handleChange}
                className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
              />
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Primary Button Label
                </label>
                <input
                  type="text"
                  name="hero_button_label"
                  value={formData.hero_button_label || ""}
                  onChange={handleChange}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Primary Button URL
                </label>
                <input
                  type="text"
                  name="hero_button_url"
                  value={formData.hero_button_url || ""}
                  onChange={handleChange}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>
            </div>

            {/* Media Uploaders for Hero */}
            <div className="pt-4 border-t border-white/5 space-y-6">
              <MediaUploader
                label="Hero Background Image"
                value={formData.hero_background_image || ""}
                onChange={(url) => handleMediaChange("hero_background_image", url)}
                helperText="Upload any high-res JPG, PNG, or WebP photo from your device, or paste a URL."
              />

              <MediaUploader
                label="Hero Background Video (Optional 4K MP4)"
                value={formData.hero_background_video || ""}
                onChange={(url) => handleMediaChange("hero_background_video", url)}
                accept="video/*"
                isVideo={true}
                placeholder="/storage/stories/... or upload MP4"
                helperText="Upload an MP4 cinematic video loop to play in the background of the hero section."
              />
            </div>
          </div>
        )}

        {/* 2. Stats Tab */}
        {activeTab === "stats" && (
          <div className="space-y-6">
            <h2 className="text-sm font-semibold uppercase tracking-wider text-[#c4a472]">
              Counter Statistics (4 Metrics)
            </h2>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
              {/* Stat 1 */}
              <div className="p-4 bg-[#18181b] rounded-2xl border border-white/5 space-y-3">
                <span className="text-xs text-[#c4a472] font-semibold uppercase block">Statistic #1</span>
                <div className="grid grid-cols-2 gap-2">
                  <input
                    type="number"
                    name="stat_1_number"
                    value={formData.stat_1_number || 250}
                    onChange={handleChange}
                    placeholder="250"
                    className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                  />
                  <input
                    type="text"
                    name="stat_1_suffix"
                    value={formData.stat_1_suffix || "+"}
                    onChange={handleChange}
                    placeholder="+"
                    className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                  />
                </div>
                <input
                  type="text"
                  name="stat_1_label"
                  value={formData.stat_1_label || ""}
                  onChange={handleChange}
                  placeholder="Weddings Captured"
                  className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                />
              </div>

              {/* Stat 2 */}
              <div className="p-4 bg-[#18181b] rounded-2xl border border-white/5 space-y-3">
                <span className="text-xs text-[#c4a472] font-semibold uppercase block">Statistic #2</span>
                <div className="grid grid-cols-2 gap-2">
                  <input
                    type="number"
                    name="stat_2_number"
                    value={formData.stat_2_number || 10}
                    onChange={handleChange}
                    placeholder="10"
                    className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                  />
                  <input
                    type="text"
                    name="stat_2_suffix"
                    value={formData.stat_2_suffix || "+"}
                    onChange={handleChange}
                    placeholder="+"
                    className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                  />
                </div>
                <input
                  type="text"
                  name="stat_2_label"
                  value={formData.stat_2_label || ""}
                  onChange={handleChange}
                  placeholder="Years of Craft"
                  className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                />
              </div>

              {/* Stat 3 */}
              <div className="p-4 bg-[#18181b] rounded-2xl border border-white/5 space-y-3">
                <span className="text-xs text-[#c4a472] font-semibold uppercase block">Statistic #3</span>
                <div className="grid grid-cols-2 gap-2">
                  <input
                    type="number"
                    name="stat_3_number"
                    value={formData.stat_3_number || 35}
                    onChange={handleChange}
                    placeholder="35"
                    className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                  />
                  <input
                    type="text"
                    name="stat_3_suffix"
                    value={formData.stat_3_suffix || "+"}
                    onChange={handleChange}
                    placeholder="+"
                    className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                  />
                </div>
                <input
                  type="text"
                  name="stat_3_label"
                  value={formData.stat_3_label || ""}
                  onChange={handleChange}
                  placeholder="Destination Cities"
                  className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                />
              </div>

              {/* Stat 4 */}
              <div className="p-4 bg-[#18181b] rounded-2xl border border-white/5 space-y-3">
                <span className="text-xs text-[#c4a472] font-semibold uppercase block">Statistic #4</span>
                <div className="grid grid-cols-2 gap-2">
                  <input
                    type="number"
                    name="stat_4_number"
                    value={formData.stat_4_number || 100}
                    onChange={handleChange}
                    placeholder="100"
                    className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                  />
                  <input
                    type="text"
                    name="stat_4_suffix"
                    value={formData.stat_4_suffix || "%"}
                    onChange={handleChange}
                    placeholder="%"
                    className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                  />
                </div>
                <input
                  type="text"
                  name="stat_4_label"
                  value={formData.stat_4_label || ""}
                  onChange={handleChange}
                  placeholder="Five-Star Experience"
                  className="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-white text-xs"
                />
              </div>
            </div>
          </div>
        )}

        {/* 3. Color Grade Tab */}
        {activeTab === "colorgrade" && (
          <div className="space-y-6">
            <h2 className="text-sm font-semibold uppercase tracking-wider text-[#c4a472]">
              Interactive Color Grading Showcase
            </h2>

            <div>
              <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                Section Heading
              </label>
              <input
                type="text"
                name="color_grade_heading"
                value={formData.color_grade_heading || ""}
                onChange={handleChange}
                placeholder="Signature Cinematic Grade"
                className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
              />
            </div>

            <div>
              <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                Section Description
              </label>
              <textarea
                name="color_grade_description"
                rows={3}
                value={formData.color_grade_description || ""}
                onChange={handleChange}
                className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
              />
            </div>

            {/* Media Uploaders for Color Grade */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-white/5">
              <MediaUploader
                label="Before RAW Image (Camera Log)"
                value={formData.color_grade_before_image || ""}
                onChange={(url) => handleMediaChange("color_grade_before_image", url)}
                helperText="Upload the un-graded / raw photograph."
              />

              <MediaUploader
                label="After Master Image (Graded)"
                value={formData.color_grade_after_image || ""}
                onChange={(url) => handleMediaChange("color_grade_after_image", url)}
                helperText="Upload the final cinematic film graded photograph."
              />
            </div>
          </div>
        )}

        {/* 4. Contact & Social Tab */}
        {activeTab === "contact" && (
          <div className="space-y-4">
            <h2 className="text-sm font-semibold uppercase tracking-wider text-[#c4a472] mb-4">
              Studio Details & Social Channels
            </h2>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Studio Name
                </label>
                <input
                  type="text"
                  name="studio_name"
                  value={formData.studio_name || ""}
                  onChange={handleChange}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Studio Tagline
                </label>
                <input
                  type="text"
                  name="tagline"
                  value={formData.tagline || ""}
                  onChange={handleChange}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs"
                />
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Email
                </label>
                <input
                  type="email"
                  name="email"
                  value={formData.email || ""}
                  onChange={handleChange}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Phone
                </label>
                <input
                  type="text"
                  name="phone"
                  value={formData.phone || ""}
                  onChange={handleChange}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase text-emerald-400 mb-1">
                  WhatsApp Number
                </label>
                <input
                  type="text"
                  name="whatsapp"
                  value={formData.whatsapp || ""}
                  onChange={handleChange}
                  placeholder="+919876543210"
                  className="w-full bg-[#18181b] border border-emerald-500/30 rounded-xl px-4 py-3 text-white text-xs"
                />
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Instagram URL
                </label>
                <input
                  type="text"
                  name="instagram_url"
                  value={formData.instagram_url || ""}
                  onChange={handleChange}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  YouTube Channel URL
                </label>
                <input
                  type="text"
                  name="youtube_url"
                  value={formData.youtube_url || ""}
                  onChange={handleChange}
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                Footer Address
              </label>
              <input
                type="text"
                name="footer_address"
                value={formData.footer_address || ""}
                onChange={handleChange}
                placeholder="Mumbai · Udaipur · Delhi · Worldwide"
                className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs"
              />
            </div>
          </div>
        )}

        <div className="pt-4 border-t border-white/5 flex justify-end">
          <button
            type="submit"
            disabled={saving}
            className="px-8 py-3 bg-[#c4a472] hover:bg-[#b09060] text-black font-semibold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-[#c4a472]/20 disabled:opacity-50"
          >
            {saving ? "Saving Changes..." : "Save Website Settings"}
          </button>
        </div>
      </form>
    </div>
  );
}