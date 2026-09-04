import React from "react";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Link from "next/link";
import { Image, Lock, Camera, ExternalLink, KeyRound } from "lucide-react";

export const revalidate = 0;

export default async function AdminAlbumsPage() {
  const albums = await db.weddingAlbum.findMany({
    include: {
      photos: true,
      _count: { select: { photos: true } },
    },
    orderBy: { created_at: "desc" },
  }).then(serializeData);

  return (
    <div>
      <div className="mb-8 flex items-center justify-between">
        <div>
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
            VIP CLIENT PORTALS
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Client Albums & AI Face Manager</h1>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {albums.map((album: any) => (
          <div key={album.id} className="bg-[#121214] border border-white/5 rounded-3xl p-6 sm:p-8 flex flex-col justify-between">
            <div>
              <div className="flex items-center justify-between mb-4">
                <span className="text-xs uppercase text-[#c4a472] font-semibold tracking-wider">
                  {album.couple_names || "Client Wedding"}
                </span>
                <span className="bg-[#00f0ff]/10 text-[#00f0ff] px-3 py-1 rounded-full text-[10px] font-bold uppercase flex items-center gap-1">
                  <KeyRound size={12} /> PIN: {album.pin_code || "Open"}
                </span>
              </div>

              <h2 className="font-serif text-2xl text-white font-light mb-2">{album.title}</h2>
              <p className="text-xs text-[#a1a1aa] mb-6">📍 {album.location || "Destination"} · {album._count?.photos || 0} Media Files</p>

              {album.google_drive_folder_id && (
                <div className="bg-[#18181b] p-3.5 rounded-xl text-xs text-[#94a3b8] mb-6 truncate">
                  <strong>Google Drive ID:</strong> {album.google_drive_folder_id}
                </div>
              )}
            </div>

            <div className="pt-4 border-t border-white/5 flex items-center justify-between text-xs">
              <Link
                href={`/gallery/${album.slug}`}
                target="_blank"
                className="text-[#00f0ff] hover:underline flex items-center gap-1 font-semibold"
              >
                Open Live Gallery <ExternalLink size={12} />
              </Link>
              <span className="text-green-400 font-medium">AI Face Finder Enabled</span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}