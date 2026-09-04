"use client";

import React, { useState } from "react";
import Image from "next/image";
import { Play, Eye, X } from "lucide-react";

export default function StoriesGrid({ stories }: { stories: any[] }) {
  const [activeVideo, setActiveVideo] = useState<string | null>(null);
  const [activeGallery, setActiveGallery] = useState<{ title: string; images: string[] } | null>(null);

  if (!stories || stories.length === 0) return null;

  return (
    <section id="stories" className="py-28 px-6 lg:px-12 bg-[#0c0c0d] scroll-mt-20">
      <div className="max-w-7xl mx-auto">
        <div className="flex flex-col md:flex-row md:items-end justify-between mb-16">
          <div>
            <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] mb-3 block font-light">
              FEATURED CELEBRATIONS
            </span>
            <h2 className="font-serif text-4xl sm:text-6xl text-[#f5f5f7] font-light">
              Captured Love Stories
            </h2>
          </div>
          <p className="text-[#a1a1aa] text-sm max-w-sm mt-4 md:mt-0 font-light leading-relaxed">
            Every celebration is unique. Here is a curated selection of weddings we had the honour of telling.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {stories.map((story, idx) => {
            const isVideo =
              story.cover_image &&
              ["mp4", "mov", "webm"].some((ext) => story.cover_image.toLowerCase().endsWith(ext));
            const mediaUrl = story.cover_image
              ? `https://paneventz.in/storage/${story.cover_image}`
              : `https://paneventz.in/images/1.webp`;

            return (
              <div
                key={story.id || idx}
                className="group relative rounded-2xl overflow-hidden bg-[#121214] border border-white/5 flex flex-col hover:border-[#c4a472]/40 transition-all duration-500 shadow-xl"
              >
                <div className="relative aspect-[4/3] w-full overflow-hidden bg-[#09090b]">
                  {isVideo ? (
                    <div
                      className="w-full h-full cursor-pointer relative"
                      onClick={() => setActiveVideo(mediaUrl)}
                    >
                      <video
                        src={`${mediaUrl}#t=0.001`}
                        preload="metadata"
                        muted
                        playsInline
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                      />
                      <div className="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-colors">
                        <div className="w-14 h-14 rounded-full bg-[#c4a472] text-[#0c0c0d] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                          <Play size={22} className="fill-current ml-1" />
                        </div>
                      </div>
                      <span className="absolute top-4 right-4 bg-black/70 backdrop-blur-md text-[#c4a472] px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-semibold border border-[#c4a472]/30">
                        ▶ Cinema Film
                      </span>
                    </div>
                  ) : (
                    <div
                      className="w-full h-full cursor-pointer relative"
                      onClick={() =>
                        setActiveGallery({
                          title: story.couple_name,
                          images: Array.isArray(story.gallery)
                            ? story.gallery.map((img: string) => `https://paneventz.in/storage/${img}`)
                            : [mediaUrl],
                        })
                      }
                    >
                      <img
                        src={mediaUrl}
                        alt={story.couple_name}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                        loading="lazy"
                      />
                      <div className="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <div className="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center">
                          <Eye size={20} />
                        </div>
                      </div>
                    </div>
                  )}
                </div>

                <div className="p-6 flex-1 flex flex-col justify-between">
                  <div>
                    <span className="text-[11px] uppercase tracking-widest text-[#c4a472] font-light">
                      {story.location || "Mumbai, India"}
                    </span>
                    <h3 className="font-serif text-2xl text-[#f5f5f7] font-light mt-1">
                      {story.couple_name}
                    </h3>
                    {story.description && (
                      <p className="text-[#a1a1aa] text-xs font-light mt-2 line-clamp-2 leading-relaxed">
                        {story.description}
                      </p>
                    )}
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
          <button
            onClick={() => setActiveVideo(null)}
            className="absolute top-6 right-6 text-white/70 hover:text-white p-2"
          >
            <X size={32} />
          </button>
          <div className="w-full max-w-4xl aspect-video rounded-2xl overflow-hidden bg-black shadow-2xl">
            <video src={activeVideo} controls autoPlay className="w-full h-full" />
          </div>
        </div>
      )}

      {/* Gallery Modal */}
      {activeGallery && (
        <div className="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex flex-col p-6 overflow-y-auto">
          <div className="flex items-center justify-between mb-6 max-w-6xl mx-auto w-full">
            <h3 className="font-serif text-2xl text-white font-light">{activeGallery.title} Gallery</h3>
            <button
              onClick={() => setActiveGallery(null)}
              className="text-white/70 hover:text-white p-2"
            >
              <X size={28} />
            </button>
          </div>
          <div className="max-w-6xl mx-auto w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            {activeGallery.images.map((img, idx) => (
              <div key={idx} className="relative aspect-[4/3] rounded-xl overflow-hidden bg-[#121214]">
                <img src={img} alt="" className="w-full h-full object-cover" />
              </div>
            ))}
          </div>
        </div>
      )}
    </section>
  );
}