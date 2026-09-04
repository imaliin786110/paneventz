"use client";

import React, { useState } from "react";
import { Play, X } from "lucide-react";

import SmartMedia, { isVideoSource, formatMediaUrl } from "@/components/SmartMedia";

export default function StoriesGrid({ stories }: { stories: any[] }) {
  const [activeVideo, setActiveVideo] = useState<string | null>(null);

  if (!stories || stories.length === 0) return null;

  return (
    <section id="stories" className="py-28 px-6 lg:px-12 bg-[#09090b] scroll-mt-20">
      <div className="max-w-7xl mx-auto">
        <div className="text-center max-w-4xl mx-auto mb-16">
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-semibold">
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
          {stories.map((story, idx) => {
            const isVideo = isVideoSource(story.cover_image);
            const mediaUrl = formatMediaUrl(story.cover_image, "/images/1.webp");

            return (
              <div
                key={story.id || idx}
                className="group relative rounded-3xl overflow-hidden bg-[#121214] border border-white/5 flex flex-col hover:border-[#c4a472]/40 transition-all duration-500 shadow-xl"
              >
                <div
                  className="relative aspect-[4/3] w-full overflow-hidden bg-black cursor-pointer"
                  onClick={() => isVideo && setActiveVideo(mediaUrl)}
                >
                  <SmartMedia
                    src={story.cover_image}
                    alt={story.couple_name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                    containerClassName="relative w-full h-full overflow-hidden bg-black"
                  />

                  {isVideo && (
                    <>
                      <span className="absolute top-4 left-4 bg-black/80 backdrop-blur-md text-[#c4a472] text-[9px] uppercase tracking-wider font-bold px-3 py-1 rounded-full border border-[#c4a472]/30 z-10 flex items-center gap-1.5">
                        <span className="w-1.5 h-1.5 rounded-full bg-[#c4a472] animate-pulse" />
                        Live Cinema Highlight
                      </span>
                      <div className="absolute bottom-4 right-4 bg-black/70 backdrop-blur-md text-white/90 text-[10px] uppercase tracking-wider px-3 py-1 rounded-full border border-white/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1.5 z-10">
                        <Play size={10} className="fill-current" />
                        <span>Watch with Sound</span>
                      </div>
                    </>
                  )}
                </div>

                <div className="p-8 flex flex-col justify-between flex-1">
                  <div>
                    <span className="text-[10px] uppercase tracking-widest text-[#c4a472] font-semibold block mb-2">
                      📍 {story.location || "Destination Wedding"}
                    </span>
                    <h3 className="font-serif text-2xl sm:text-3xl text-white font-light mb-3">
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