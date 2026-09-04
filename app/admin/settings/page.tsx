import React from "react";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import { Settings, Save, Phone, Mail, Instagram, MessageSquare, Award } from "lucide-react";

export const revalidate = 0;

export default async function AdminSettingsPage() {
  const setting = await db.websiteSetting.findFirst().then(serializeData);

  return (
    <div className="max-w-4xl">
      <div className="mb-8">
        <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
          GLOBAL CONFIGURATION
        </span>
        <h1 className="font-serif text-3xl text-white font-light">Website Settings & Branding</h1>
      </div>

      <div className="bg-[#121214] border border-white/5 rounded-3xl p-8 space-y-8">
        <div>
          <h3 className="font-serif text-xl text-white font-light mb-4 pb-2 border-b border-white/5">
            Studio Identity & Contact
          </h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2">Studio Name</label>
              <input type="text" readOnly defaultValue={setting?.studio_name || "Paneventz"} className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white" />
            </div>
            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2">WhatsApp Number</label>
              <input type="text" readOnly defaultValue={setting?.whatsapp || "+91 80820 24787"} className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white" />
            </div>
            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2">Primary Email</label>
              <input type="text" readOnly defaultValue={setting?.email || "imaliinmirza@gmail.com"} className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white" />
            </div>
            <div>
              <label className="block text-xs uppercase tracking-widest text-[#a1a1aa] mb-2">Instagram Profile</label>
              <input type="text" readOnly defaultValue={setting?.instagram_url || "https://instagram.com/paneventz"} className="w-full bg-[#0c0c0d] border border-white/10 rounded-xl px-4 py-3 text-sm text-white" />
            </div>
          </div>
        </div>

        <div>
          <h3 className="font-serif text-xl text-white font-light mb-4 pb-2 border-b border-white/5">
            Homepage Statistics Counters
          </h3>
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div className="bg-[#18181b] p-4 rounded-xl text-center">
              <span className="font-serif text-2xl text-[#c4a472]">{setting?.stat_1_number || 250}+</span>
              <span className="text-[10px] uppercase text-[#a1a1aa] block mt-1">Weddings</span>
            </div>
            <div className="bg-[#18181b] p-4 rounded-xl text-center">
              <span className="font-serif text-2xl text-[#c4a472]">{setting?.stat_2_number || 10}+</span>
              <span className="text-[10px] uppercase text-[#a1a1aa] block mt-1">Years</span>
            </div>
            <div className="bg-[#18181b] p-4 rounded-xl text-center">
              <span className="font-serif text-2xl text-[#c4a472]">{setting?.stat_3_number || 35}+</span>
              <span className="text-[10px] uppercase text-[#a1a1aa] block mt-1">Palaces</span>
            </div>
            <div className="bg-[#18181b] p-4 rounded-xl text-center">
              <span className="font-serif text-2xl text-[#c4a472]">{setting?.stat_4_number || 100}%</span>
              <span className="text-[10px] uppercase text-[#a1a1aa] block mt-1">Trust</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}