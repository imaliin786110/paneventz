import React from "react";
import Link from "next/link";
import { Instagram, Mail, Phone, MessageSquare } from "lucide-react";

export default function Footer({ setting }: { setting: any }) {
  const year = new Date().getFullYear();
  return (
    <footer className="bg-[#09090b] border-t border-white/5 py-16 px-6 lg:px-12 text-[#a1a1aa] text-sm font-light">
      <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
        <div className="md:col-span-2">
          <span className="font-serif text-3xl tracking-widest text-[#f5f5f7] uppercase block mb-4">
            {setting?.studio_name || "Paneventz"}
          </span>
          <p className="max-w-md text-sm leading-relaxed mb-6">
            {setting?.footer_description ||
              "Now reserving select dates for wedding celebrations across India and destinations worldwide."}
          </p>
          <div className="flex items-center gap-4 text-[#d6d6d8]">
            {setting?.instagram_url && (
              <a
                href={setting.instagram_url}
                target="_blank"
                rel="noreferrer"
                className="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:border-[#c4a472] hover:text-[#c4a472] transition-colors"
              >
                <Instagram size={18} />
              </a>
            )}
            {setting?.whatsapp && (
              <a
                href={`https://wa.me/${setting.whatsapp.replace(/[^0-9]/g, "")}?text=Hi%20Paneventz,%20I'd%20like%20to%20enquire%20about%20wedding%20photography.`}
                target="_blank"
                rel="noreferrer"
                className="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:border-[#25D366] hover:text-[#25D366] transition-colors"
              >
                <MessageSquare size={18} />
              </a>
            )}
            {setting?.email && (
              <a
                href={`mailto:${setting.email}`}
                className="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:border-[#c4a472] hover:text-[#c4a472] transition-colors"
              >
                <Mail size={18} />
              </a>
            )}
          </div>
        </div>

        <div>
          <h4 className="font-serif text-lg text-[#f5f5f7] mb-4">Explore</h4>
          <ul className="space-y-2 text-xs uppercase tracking-widest">
            <li>
              <Link href="#stories" className="hover:text-[#c4a472] transition-colors">
                Stories
              </Link>
            </li>
            <li>
              <Link href="#films" className="hover:text-[#c4a472] transition-colors">
                Cinematic Films
              </Link>
            </li>
            <li>
              <Link href="/services" className="hover:text-[#c4a472] transition-colors">
                Investment & Packages
              </Link>
            </li>
            <li>
              <Link href="/blog" className="hover:text-[#c4a472] transition-colors">
                Journal
              </Link>
            </li>
            <li>
              <Link href="/client-portal" className="hover:text-[#c4a472] transition-colors">
                VIP Client Portal
              </Link>
            </li>
          </ul>
        </div>

        <div>
          <h4 className="font-serif text-lg text-[#f5f5f7] mb-4">Legal & Destinations</h4>
          <ul className="space-y-2 text-xs uppercase tracking-widest mb-6">
            <li>
              <Link href="/terms" className="hover:text-[#c4a472] transition-colors">
                Terms & Conditions
              </Link>
            </li>
            <li>
              <Link href="/wedding-photographer-mumbai" className="hover:text-[#c4a472] transition-colors">
                Mumbai Weddings
              </Link>
            </li>
            <li>
              <Link href="/wedding-photographer-udaipur" className="hover:text-[#c4a472] transition-colors">
                Udaipur Royal Palaces
              </Link>
            </li>
            <li>
              <Link href="/wedding-photographer-goa" className="hover:text-[#c4a472] transition-colors">
                Goa Beach Celebrations
              </Link>
            </li>
          </ul>
          <p className="text-xs text-[#71717a]">
            {setting?.footer_address || "Mumbai · Available Pan-India & Worldwide"}
          </p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-[#71717a]">
        <p>{setting?.footer_copyright || `© ${year} Paneventz Studio. Handcrafted for unforgettable love stories.`}</p>
        <p>Crafted with Next.js & Neon Cloud PostgreSQL</p>
      </div>
    </footer>
  );
}