"use client";

import React, { useState, useRef } from "react";
import { Upload, X, Check, Image as ImageIcon, Video, Loader2 } from "lucide-react";

interface MediaUploaderProps {
  label: string;
  value: string;
  onChange: (url: string) => void;
  accept?: string;
  placeholder?: string;
  helperText?: string;
  isVideo?: boolean;
}

export default function MediaUploader({
  label,
  value,
  onChange,
  accept = "image/*",
  placeholder = "https://... or upload a file",
  helperText,
  isVideo = false,
}: MediaUploaderProps) {
  const [uploading, setUploading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const fileInputRef = useRef<HTMLInputElement | null>(null);

  const handleFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    setUploading(true);
    setError(null);

    try {
      const formData = new FormData();
      formData.append("file", file);

      const res = await fetch("/api/admin/upload", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();
      if (res.ok && data.url) {
        onChange(data.url);
      } else {
        setError(data.error || "Upload failed");
      }
    } catch (err) {
      console.error(err);
      setError("Network error during upload");
    } finally {
      setUploading(false);
      if (fileInputRef.current) fileInputRef.current.value = "";
    }
  };

  return (
    <div className="space-y-2">
      <label className="block text-xs font-semibold uppercase tracking-wider text-zinc-400 flex items-center justify-between">
        <span className="flex items-center gap-1.5">
          {isVideo ? <Video size={14} className="text-[#c4a472]" /> : <ImageIcon size={14} className="text-[#00f0ff]" />}
          {label}
        </span>
        {value && (
          <span className="text-[10px] text-emerald-400 font-medium lowercase flex items-center gap-1">
            <Check size={12} /> Active Media
          </span>
        )}
      </label>

      {/* Preview Card if value exists */}
      {value && (
        <div className="relative rounded-2xl overflow-hidden border border-white/10 bg-black/40 p-2 flex items-center gap-4">
          <div className="w-20 h-20 rounded-xl overflow-hidden bg-zinc-900 shrink-0 border border-white/10 relative flex items-center justify-center">
            {isVideo ? (
              <video src={value} className="w-full h-full object-cover" muted />
            ) : (
              <img src={value} alt="Preview" className="w-full h-full object-cover" />
            )}
          </div>
          <div className="flex-1 min-w-0 pr-2">
            <p className="text-xs text-zinc-200 truncate font-mono">{value}</p>
            <p className="text-[10px] text-zinc-500 mt-1">Uploaded & ready on live website</p>
          </div>
          <button
            type="button"
            onClick={() => onChange("")}
            className="p-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg transition-colors shrink-0"
            title="Remove Media"
          >
            <X size={16} />
          </button>
        </div>
      )}

      {/* Upload Button + URL Input */}
      <div className="flex items-center gap-2">
        <input
          type="text"
          value={value || ""}
          onChange={(e) => onChange(e.target.value)}
          placeholder={placeholder}
          className="flex-1 bg-[#18181b] border border-white/10 rounded-xl px-4 py-2.5 text-white text-xs focus:outline-none focus:border-[#c4a472] font-mono transition-colors"
        />

        <input
          type="file"
          ref={fileInputRef}
          accept={accept}
          onChange={handleFileChange}
          className="hidden"
        />

        <button
          type="button"
          onClick={() => fileInputRef.current?.click()}
          disabled={uploading}
          className="px-4 py-2.5 bg-[#c4a472]/15 hover:bg-[#c4a472]/25 text-[#c4a472] border border-[#c4a472]/30 rounded-xl text-xs font-semibold uppercase tracking-wider flex items-center gap-1.5 transition-all shrink-0 disabled:opacity-50"
        >
          {uploading ? (
            <>
              <Loader2 size={14} className="animate-spin" />
              <span>Uploading...</span>
            </>
          ) : (
            <>
              <Upload size={14} />
              <span>Upload {isVideo ? "Video" : "Image"}</span>
            </>
          )}
        </button>
      </div>

      {error && <p className="text-[11px] text-red-400">{error}</p>}
      {helperText && <p className="text-[10px] text-zinc-500">{helperText}</p>}
    </div>
  );
}
