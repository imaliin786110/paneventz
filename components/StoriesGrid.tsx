"use client";

import React, { useState } from "react";
import { Play, X } from "lucide-react";

import SmartMedia, { isVideoSource, formatMediaUrl } from "@/components/SmartMedia";

export default function StoriesGrid({ stories }: { stories: any[] }) {
  const [activeVideo, setActiveVideo] = useState<string | null>(null);

  // Filter out any blank/empty stories or Natasha & Dev
  const validStories = (stories || []).filter((story) => {
    if (!story) return false;
    const name = (story.couple_name || "").toLowerCase();
    if (name.includes("natasha") || name.includes("dev")) return false;
    if (!story.cover_image || story.cover_image.trim() === "") return false;
    return true;
  });

  if (validStories.length === 0) return null;

  return (
    <section id="stories" className="py-28 px-6 lg:px-12 bg-[#09090b] scroll-mt-20 relative overflow-hidden">
      {/* AMBIENT LIVING LIGHT */}
      <div className="absolute top-1/2 right-10 w-[500px] h-[500px] bg-[radial-gradient(circle,rgba(196,164,114,0.06),transparent_70%)] pointer-events-none animate-ambient-glow" />

      <div className="relative z-10 max-w-7xl mx-auto">
        <div className="text-center max-w-4xl mx-auto mb-16">
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-semibold animate-pulse [animation-duration:6s]">
            MOMENTS THAT STAY
          </span>
          <h2 className="font-serif text-3xl sm:text-5xl lg:text-6xl text-white font-light mb-4 leading-tight">
            One day. A thousand emotions.<br />
            A lifetime of memories.
          </h2>
          <div className="font-serif text-xl sm:text-2xl text-[#c4a472] italic font-normal mb-4">
            The smiles. The tears. The stolen glances. Every feeling, beautifully preserved.
          </div>
          <p className="text-xs sm:text-sm text-zinc-400 font-light">
            Stories we&apos;ve had the honour of telling.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {validStories.map((story, idx) => {
            const isVideo = isVideoSource(story.cover_image);
            const mediaUrl = formatMediaUrl(story.cover_image, "/images/1.webp");

            return (
              <div
                key={story.id || idx}
                className="group luxury-card-interactive relative rounded-3xl overflow-hidden bg-[#121214] border border-white/5 flex flex-col hover:border-[#c4a472]/50 shadow-xl"
              >
                <div
                  className="relative aspect-[4/3] w-full overflow-hidden bg-black cursor-pointer"
                  onClick={() => isVideo && setActiveVideo(mediaUrl)}
                >
                  <SmartMedia
                    src={story.cover_image}
                    alt={`${story.couple_name} luxury wedding photography in ${story.location || "India"}`}
                    className="w-full h-full object-cover group-hover:scale-108 transition-transform duration-1000 ease-out"
                    containerClassName="relative w-full h-full overflow-hidden bg-black"
                  />

                  <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-40 group-hover:opacity-20 transition-opacity duration-500 pointer-events-none" />

                  {isVideo && (
                    <>
                      <span className="absolute top-4 left-4 bg-black/80 backdrop-blur-md text-[#c4a472] text-[9px] uppercase tracking-wider font-bold px-3 py-1 rounded-full border border-[#c4a472]/40 z-10 flex items-center gap-1.5 shadow-lg">
                        <span className="w-1.5 h-1.5 rounded-full bg-[#c4a472] animate-ping [animation-duration:2s]" />
                        Live Cinema Highlight
                      </span>
                      <div className="absolute bottom-4 right-4 bg-black/80 backdrop-blur-md text-white text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-full border border-[#c4a472]/40 opacity-0 group-hover:opacity-100 group-hover:scale-105 transition-all duration-300 flex items-center gap-2 z-10 shadow-xl">
                        <Play size={10} className="fill-[#c4a472] text-[#c4a472]" />
                        <span>Watch with Sound</span>
                      </div>
                    </>
                  )}
                </div>

                <div className="p-8 flex flex-col justify-between flex-1">
                  <div>
                    <span className="text-[10px] uppercase tracking-widest text-[#c4a472] font-semibold block mb-2 group-hover:text-[#f7ebd7] transition-colors">
                      📍 {story.location || "Destination Wedding"}
                    </span>
                    <h3 className="font-serif text-2xl sm:text-3xl text-white font-light mb-3 group-hover:text-[#c4a472] transition-colors duration-300">
                      {story.couple_name}
                    </h3>
                    <p className="text-xs text-zinc-400 font-light leading-relaxed line-clamp-3">
                      {story.description || "A breathtaking celebration of timeless love."}
                    </p>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>

      {/* Video Modal */}
      {activeVideo && (
        <div className="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4">
          <div className="relative max-w-4xl w-full bg-black rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
            <button
              onClick={() => setActiveVideo(null)}
              className="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black transition-colors"
            >
              <X size={20} />
            </button>
            <video src={activeVideo} controls autoPlay className="w-full h-auto max-h-[80vh]" />
          </div>
        </div>
      )}
    </section>
  );
}