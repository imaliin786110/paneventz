import React from "react";
import { db } from "@/lib/db";
import { serializeData } from "@/lib/utils";
import { MessageSquare, Phone, Mail, MapPin, Calendar, Clock } from "lucide-react";

export const revalidate = 0;

export default async function AdminEnquiriesPage() {
  const enquiries = await db.enquiry.findMany({
    orderBy: { created_at: "desc" },
  }).then(serializeData);

  return (
    <div>
      <div className="mb-8 flex items-center justify-between">
        <div>
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
            CLIENT LEADS
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Inquiries CRM</h1>
        </div>
        <span className="bg-[#18181b] text-white px-4 py-2 rounded-full text-xs font-semibold">
          Total: {enquiries.length} Inquiries
        </span>
      </div>

      <div className="space-y-4">
        {enquiries.map((enq: any) => (
          <div
            key={enq.id}
            className="bg-[#121214] border border-white/5 rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-6"
          >
            <div className="space-y-2">
              <div className="flex items-center gap-3">
                <h3 className="font-serif text-xl text-white font-medium">{enq.name}</h3>
                <span className="bg-[#c4a472]/20 text-[#c4a472] px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase">
                  {enq.status}
                </span>
              </div>
              <div className="flex flex-wrap items-center gap-4 text-xs text-[#a1a1aa]">
                <span className="flex items-center gap-1.5"><MapPin size={13} /> {enq.wedding_location}</span>
                <span className="flex items-center gap-1.5"><Phone size={13} /> {enq.phone}</span>
                <span className="flex items-center gap-1.5"><Mail size={13} /> {enq.email}</span>
                <span className="flex items-center gap-1.5"><Calendar size={13} /> {enq.wedding_date ? new Date(enq.wedding_date).toLocaleDateString() : "TBD"}</span>
              </div>
              {enq.message && (
                <p className="text-xs text-[#71717a] font-light italic mt-2 bg-[#18181b] p-3 rounded-xl">
                  "{enq.message}"
                </p>
              )}
            </div>

            <div className="flex items-center gap-3 shrink-0">
              <a
                href={`https://wa.me/${enq.phone.replace(/[^0-9]/g, "")}?text=Hi%20${encodeURIComponent(enq.name)},%20thank%20you%20for%20inquiring%20with%20Paneventz!`}
                target="_blank"
                rel="noreferrer"
                className="px-4 py-2 rounded-full text-xs font-bold bg-[#25D366] text-white flex items-center gap-1.5 hover:opacity-90"
              >
                <MessageSquare size={14} /> WhatsApp
              </a>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}