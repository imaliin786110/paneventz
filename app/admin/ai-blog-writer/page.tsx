"use client";

import React, { useState } from "react";
import { Sparkles, Send, CheckCircle2, FileText, Globe } from "lucide-react";

export default function AdminAiBlogWriterPage() {
  const [topic, setTopic] = useState("The Ultimate Guide to Royal Destination Weddings in Udaipur");
  const [location, setLocation] = useState("Udaipur, Rajasthan");
  const [keywords, setKeywords] = useState("luxury wedding photographer Udaipur, royal palace wedding cost, Jagmandir Island palace");
  const [status, setStatus] = useState<"idle" | "generating" | "success" | "error">("idle");
  const [generatedArticle, setGeneratedArticle] = useState<any | null>(null);

  const handleGenerate = async (e: React.FormEvent) => {
    e.preventDefault();
    setStatus("generating");

    try {
      const res = await fetch("/api/admin/ai-generate-blog", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ topic, location, keywords }),
      });

      const data = await res.json();
      if (res.ok && data.success) {
        setGeneratedArticle(data.article);
        setStatus("success");
      } else {
        setStatus("error");
      }
    } catch (err) {
      console.error(err);
      setStatus("error");
    }
  };

  return (
    <div className="max-w-4xl">
      <div className="mb-8">
        <span className="text-xs uppercase tracking-[0.3em] text-[#00f0ff] font-semibold block mb-1">
          AUTOMATED EDITORIAL ENGINE
        </span>
        <h1 className="font-serif text-3xl sm:text-4xl text-white font-light">
          1-Click AI Wedding Article Generator
        </h1>
        <p className="text-xs text-[#94a3b8] mt-2">
          Leverages Google Gemini / OpenAI neural models to research and craft high-ranking 1,500+ word wedding articles with custom JSON-LD schema.
        </p>
      </div>

      {status === "success" && generatedArticle ? (
        <div className="bg-[#121929] border border-[#00f0ff]/40 rounded-3xl p-8 shadow-2xl">
          <CheckCircle2 size={48} className="text-[#00f0ff] mb-4" />
          <h2 className="font-serif text-2xl text-white font-light mb-2">{generatedArticle.title}</h2>
          <p className="text-xs text-[#00f0ff] uppercase tracking-wider mb-6">Published to /blog/{generatedArticle.slug}</p>
          <div className="bg-[#090b10] p-6 rounded-2xl text-xs text-[#d6d6d8] leading-relaxed max-h-96 overflow-y-auto whitespace-pre-line mb-6">
            {generatedArticle.content}
          </div>
          <button
            onClick={() => setStatus("idle")}
            className="px-6 py-2.5 rounded-full text-xs uppercase tracking-wider bg-[#c4a472] text-[#0c0c0d] font-bold"
          >
            Generate Another Article
          </button>
        </div>
      ) : (
        <form onSubmit={handleGenerate} className="bg-[#121214] border border-white/5 rounded-3xl p-8 space-y-6 shadow-2xl">
          <div>
            <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2 font-medium">
              Article Topic / Headline *
            </label>
            <input
              type="text"
              required
              value={topic}
              onChange={(e) => setTopic(e.target.value)}
              className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-[#00f0ff] focus:outline-none"
            />
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2 font-medium">
                Destination Focus
              </label>
              <input
                type="text"
                value={location}
                onChange={(e) => setLocation(e.target.value)}
                className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-[#00f0ff] focus:outline-none"
              />
            </div>

            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2 font-medium">
                Primary SEO Keywords
              </label>
              <input
                type="text"
                value={keywords}
                onChange={(e) => setKeywords(e.target.value)}
                className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-[#00f0ff] focus:outline-none"
              />
            </div>
          </div>

          <button
            type="submit"
            disabled={status === "generating"}
            className="w-full py-4 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#0099cc] to-[#00f0ff] text-[#070a12] font-bold flex items-center justify-center gap-2 hover:opacity-90 disabled:opacity-50 shadow-xl shadow-[#00f0ff]/10"
          >
            <Sparkles size={16} /> {status === "generating" ? "AI Neural Generation in Progress..." : "Generate & Publish Article"}
          </button>
        </form>
      )}
    </div>
  );
}