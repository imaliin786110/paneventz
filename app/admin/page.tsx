import React from "react";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import Link from "next/link";
import { Users, Camera, Image, Sparkles, ArrowUpRight, FolderSync, ShieldCheck, Settings } from "lucide-react";

export const revalidate = 0;

export default async function AdminDashboardPage() {
  const [enquiriesCount, storiesCount, albumsCount, blogCount, recentEnquiries, recentStories] = await Promise.all([
    db.enquiry.count(),
    db.story.count(),
    db.weddingAlbum.count(),
    db.blogPost.count(),
    db.enquiry.findMany({ take: 5, orderBy: { created_at: "desc" } }).then(serializeData),
    db.story.findMany({ take: 4, orderBy: { created_at: "desc" } }).then(serializeData),
  ]);

  return (
    <div>
      <div className="mb-10">
        <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
          STUDIO MANAGEMENT SUITE
        </span>
        <h1 className="font-serif text-3xl sm:text-4xl text-white font-light">
          Welcome to Paneventz Control Center
        </h1>
      </div>

      {/* Stats Counter Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div className="bg-[#121214] border border-white/5 rounded-2xl p-6 flex items-center justify-between">
          <div>
            <span className="text-xs text-[#a1a1aa] uppercase tracking-wider block mb-1">Total Leads</span>
            <span className="font-serif text-3xl text-white font-light">{enquiriesCount}</span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-[#c4a472]/10 text-[#c4a472] flex items-center justify-center">
            <Users size={22} />
          </div>
        </div>

        <div className="bg-[#121214] border border-white/5 rounded-2xl p-6 flex items-center justify-between">
          <div>
            <span className="text-xs text-[#a1a1aa] uppercase tracking-wider block mb-1">Active Stories</span>
            <span className="font-serif text-3xl text-white font-light">{storiesCount}</span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-[#c4a472]/10 text-[#c4a472] flex items-center justify-center">
            <Camera size={22} />
          </div>
        </div>

        <div className="bg-[#121214] border border-white/5 rounded-2xl p-6 flex items-center justify-between">
          <div>
            <span className="text-xs text-[#a1a1aa] uppercase tracking-wider block mb-1">Client Albums</span>
            <span className="font-serif text-3xl text-white font-light">{albumsCount}</span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-[#00f0ff]/10 text-[#00f0ff] flex items-center justify-center">
            <Image size={22} />
          </div>
        </div>

        <div className="bg-[#121214] border border-white/5 rounded-2xl p-6 flex items-center justify-between">
          <div>
            <span className="text-xs text-[#a1a1aa] uppercase tracking-wider block mb-1">Journal Articles</span>
            <span className="font-serif text-3xl text-white font-light">{blogCount}</span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
            <Sparkles size={22} />
          </div>
        </div>
      </div>

      <section className="mb-12 rounded-3xl border border-white/10 bg-gradient-to-r from-[#121929] via-[#121214] to-[#17121a] p-6 sm:p-8">
        <div className="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
          <div>
            <span className="text-[10px] uppercase tracking-[0.3em] text-[#00f0ff] font-bold">Studio workflow</span>
            <h2 className="font-serif text-2xl text-white font-light mt-2">Everything important, ready for the next wedding</h2>
          </div>
          <span className="text-xs text-zinc-400">Manage content without touching code.</span>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
          <Link href="/admin/wedding-albums" className="group rounded-2xl border border-[#00f0ff]/20 bg-[#00f0ff]/5 p-5 hover:bg-[#00f0ff]/10 transition-colors">
            <FolderSync size={20} className="text-[#00f0ff] mb-3" /><h3 className="text-sm text-white font-semibold">Prepare guest galleries</h3><p className="text-xs text-zinc-400 mt-1">Create albums, add PINs, upload and face-index guest photos.</p><span className="mt-4 inline-flex text-xs text-[#00f0ff] group-hover:translate-x-1 transition-transform">Open album manager →</span>
          </Link>
          <Link href="/admin/settings" className="group rounded-2xl border border-[#c4a472]/20 bg-[#c4a472]/5 p-5 hover:bg-[#c4a472]/10 transition-colors">
            <Settings size={20} className="text-[#c4a472] mb-3" /><h3 className="text-sm text-white font-semibold">Update website content</h3><p className="text-xs text-zinc-400 mt-1">Edit studio details, homepage content, contact information and SEO.</p><span className="mt-4 inline-flex text-xs text-[#c4a472] group-hover:translate-x-1 transition-transform">Open content CMS →</span>
          </Link>
          <Link href="/admin/enquiries" className="group rounded-2xl border border-emerald-400/20 bg-emerald-400/5 p-5 hover:bg-emerald-400/10 transition-colors">
            <ShieldCheck size={20} className="text-emerald-400 mb-3" /><h3 className="text-sm text-white font-semibold">Review new client leads</h3><p className="text-xs text-zinc-400 mt-1">Keep wedding enquiries moving with notes and clear status updates.</p><span className="mt-4 inline-flex text-xs text-emerald-400 group-hover:translate-x-1 transition-transform">Open inquiries →</span>
          </Link>
        </div>
      </section>

      {/* Two Column Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Recent Inquiries */}
        <div className="bg-[#121214] border border-white/5 rounded-3xl p-6 sm:p-8">
          <div className="flex items-center justify-between mb-6">
            <h3 className="font-serif text-xl text-white font-light">Recent Inquiries</h3>
            <Link href="/admin/enquiries" className="text-xs text-[#c4a472] hover:underline flex items-center gap-1">
              View All <ArrowUpRight size={14} />
            </Link>
          </div>

          <div className="space-y-4">
            {recentEnquiries.map((enq: any) => (
              <div key={enq.id} className="bg-[#18181b] p-4 rounded-xl flex items-center justify-between text-xs">
                <div>
                  <h4 className="text-white font-medium text-sm mb-0.5">{enq.name}</h4>
                  <span className="text-[#a1a1aa]">{enq.wedding_location} · {enq.phone}</span>
                </div>
                <span className="bg-[#c4a472]/20 text-[#c4a472] px-2.5 py-1 rounded-full text-[10px] font-bold uppercase">
                  {enq.status}
                </span>
              </div>
            ))}
          </div>
        </div>

        {/* Quick AI Tool Banner */}
        <div className="bg-gradient-to-br from-[#121929] to-[#0c0c0d] border border-[#00f0ff]/30 rounded-3xl p-6 sm:p-8 flex flex-col justify-between">
          <div>
            <span className="text-[10px] uppercase tracking-[0.3em] text-[#00f0ff] font-bold block mb-2">
              AUTOMATED MARKETING
            </span>
            <h3 className="font-serif text-2xl text-white font-light mb-3">
              1-Click Wedding Article AI Writer
            </h3>
            <p className="text-xs text-[#94a3b8] font-light leading-relaxed mb-6">
              Generate a high-ranking 1,500+ word wedding article from any recent wedding story or royal palace destination with customized keywords and JSON-LD schema.
            </p>
          </div>

          <Link
            href="/admin/ai-blog-writer"
            className="w-full py-3.5 rounded-full text-xs uppercase tracking-widest bg-gradient-to-r from-[#0099cc] to-[#00f0ff] text-[#070a12] font-bold text-center hover:opacity-90 transition-all shadow-lg"
          >
            Launch AI Writer Tool ✨
          </Link>
        </div>
      </div>
    </div>
  );
}
