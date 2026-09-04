import React from "react";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Link from "next/link";
import { Camera, Plus, Play, CheckCircle, Eye } from "lucide-react";

export const revalidate = 0;

export default async function AdminStoriesPage() {
  const stories = await db.story.findMany({
    orderBy: { sort_order: "asc" },
  }).then(serializeData);

  return (
    <div>
      <div className="mb-8 flex items-center justify-between">
        <div>
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
            PORTFOLIO SHOWCASE
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Stories & Media Manager</h1>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {stories.map((story: any) => {
          const isVideo = story.cover_image && ["mp4", "mov", "webm"].some((ext: string) => story.cover_image.toLowerCase().endsWith(ext));
          const mediaUrl = story.cover_image ? (story.cover_image.startsWith("http") || story.cover_image.startsWith("/") ? story.cover_image : `/${story.cover_image}`) : "/images/1.jpg";

          return (
            <div key={story.id} className="bg-[#121214] border border-white/5 rounded-2xl overflow-hidden flex flex-col">
              <div className="relative aspect-[16/10] bg-black">
                {isVideo ? (
                  <div className="w-full h-full relative flex items-center justify-center">
                    <video src={`${mediaUrl}#t=0.001`} className="w-full h-full object-cover opacity-70" />
                    <span className="absolute top-3 left-3 bg-[#c4a472] text-[#0c0c0d] text-[9px] uppercase font-bold px-2 py-0.5 rounded-full">
                      ▶ Video Story
                    </span>
                  </div>
                ) : (
                  <img src={mediaUrl} alt="" className="w-full h-full object-cover" />
                )}
              </div>

              <div className="p-5 flex-1 flex flex-col justify-between">
                <div>
                  <span className="text-[10px] uppercase text-[#c4a472] font-semibold">{story.location}</span>
                  <h3 className="font-serif text-xl text-white font-normal mt-0.5 mb-2">{story.couple_name}</h3>
                  <p className="text-xs text-[#a1a1aa] line-clamp-2">{story.description}</p>
                </div>

                <div className="mt-4 pt-3 border-t border-white/5 flex items-center justify-between text-xs">
                  <span className="text-green-400 flex items-center gap-1 font-medium">
                    <CheckCircle size={12} /> Published
                  </span>
                  <span className="text-[#71717a]">Order: {story.sort_order}</span>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}