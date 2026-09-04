"use client";

import React, { useState } from "react";
import { Play, X } from "lucide-react";

export default function FilmsSection({ films }: { films: any[] }) {
  const [activeFilm, setActiveFilm] = useState<any | null>(null);

  if (!films || films.length === 0) return null;

  return (
    <section id="films" className="py-28 px-6 lg:px-12 bg-[#09090b] border-t border-white/5 scroll-mt-20">
      <div className="max-w-7xl mx-auto">
        <div className="flex flex-col md:flex-row md:items-end justify-between mb-16">
          <div>
            <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
              MOTION PICTURES
            </span>
            <h2 className="font-serif text-4xl sm:text-6xl text-[#f5f5f7] font-light">
              Cinematic Love Stories
            </h2>
          </div>
          <p className="text-[#a1a1aa] text-sm max-w-sm mt-4 md:mt-0 font-light leading-relaxed">
            Crafted like fine cinema. Original soundscapes, vows, and heartbeats preserved forever.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {films.map((film, idx) => (
            <div
              key={film.id || idx}
              className="group bg-[#121214] border border-white/5 rounded-3xl overflow-hidden flex flex-col hover:border-[#c4a472]/40 transition-all duration-500 shadow-2xl"
            >
              <div
                className="relative aspect-video w-full overflow-hidden bg-black cursor-pointer"
                onClick={() => setActiveFilm(film)}
              >
                <img
                  src={film.thumbnail || "https://paneventz.in/images/1.jpg"}
                  alt={film.title}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                />
                <div className="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-colors">
                  <div className="w-16 h-16 rounded-full bg-[#c4a472] text-[#0c0c0d] flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                    <Play size={24} className="fill-current ml-1" />
                  </div>
                </div>
                <span className="absolute top-4 left-4 bg-black/70 backdrop-blur-sm text-[#c4a472] px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-semibold border border-[#c4a472]/20">
                  4K Master Cinema
                </span>
              </div>

              <div className="p-8 flex flex-col justify-between flex-1">
                <div>
                  <span className="text-[11px] uppercase tracking-widest text-[#c4a472] font-semibold block mb-1">
                    {film.location || "Destination Wedding"}
                  </span>
                  <h3 className="font-serif text-3xl text-[#f5f5f7] font-light mb-2">
                    {film.title}
                  </h3>
                  {film.description && (
                    <p className="text-xs text-[#a1a1aa] font-light leading-relaxed line-clamp-2">
                      {film.description}
                    </p>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Video Modal */}
      {activeFilm && (
        <div className="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4">
          <button
            onClick={() => setActiveFilm(null)}
            className="absolute top-6 right-6 text-white/70 hover:text-white p-2"
          >
            <X size={32} />
          </button>
          <div className="w-full max-w-5xl aspect-video rounded-3xl overflow-hidden bg-black shadow-2xl">
            {activeFilm.video_url.includes("youtube.com") || activeFilm.video_url.includes("youtu.be") ? (
              <iframe
                src={`https://www.youtube-nocookie.com/embed/${activeFilm.video_url.split("v=")[1]?.split("&")[0] || activeFilm.video_url.split("/").pop()}?autoplay=1&rel=0`}
                className="w-full h-full border-0"
                allow="autoplay; encrypted-media; picture-in-picture"
                allowFullScreen
              />
            ) : (
              <video src={activeFilm.video_url} controls autoPlay className="w-full h-full" />
            )}
          </div>
        </div>
      )}
    </section>
  );
}