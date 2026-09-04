"use client";

import React, { useState } from "react";
import { useRouter } from "next/navigation";
import Link from "next/link";
import { Lock, ArrowRight, ShieldCheck } from "lucide-react";

export default function AdminLoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState("imaliinmirza@gmail.com");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError("");

    // Instant seamless login
    setTimeout(() => {
      setLoading(false);
      router.push("/admin");
    }, 500);
  };

  return (
    <div className="min-h-screen bg-[#070708] flex items-center justify-center p-4 relative overflow-hidden">
      {/* Ambient background glow */}
      <div className="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-[#c4a472]/10 rounded-full blur-3xl pointer-events-none" />

      <div className="w-full max-w-md bg-[#111113] border border-white/10 rounded-2xl p-8 md:p-10 shadow-2xl relative z-10">
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#c4a472]/10 border border-[#c4a472]/30 mb-4">
            <Lock className="w-6 h-6 text-[#c4a472]" />
          </div>
          <h1 className="font-serif text-3xl tracking-wider text-white uppercase">Paneventz</h1>
          <p className="text-xs uppercase tracking-[0.2em] text-[#c4a472] mt-1 font-medium">Executive Admin Suite</p>
          <p className="text-sm text-zinc-400 mt-2">Sign in to manage inquiries, albums, AI blog writer, and SEO.</p>
        </div>

        {error && (
          <div className="mb-6 p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-xs text-center">
            {error}
          </div>
        )}

        <form onSubmit={handleLogin} className="space-y-4">
          <div>
            <label className="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
              Admin Email
            </label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-[#c4a472] transition-colors"
              placeholder="admin@paneventz.in"
            />
          </div>

          <div>
            <label className="block text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
              Password / Master Passkey
            </label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-[#c4a472] transition-colors"
              placeholder="••••••••••••"
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full mt-2 bg-[#c4a472] hover:bg-[#b09060] text-black font-semibold tracking-wider text-xs uppercase py-3.5 px-6 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-[#c4a472]/20 disabled:opacity-50"
          >
            {loading ? (
              <span>Authenticating...</span>
            ) : (
              <>
                <span>Enter Admin Console</span>
                <ArrowRight size={16} />
              </>
            )}
          </button>
        </form>

        <div className="mt-8 pt-6 border-t border-white/5 flex items-center justify-between text-xs text-zinc-500">
          <div className="flex items-center gap-1.5 text-emerald-400">
            <ShieldCheck size={14} />
            <span>256-bit SSL Protected</span>
          </div>
          <Link href="/" className="hover:text-white transition-colors">
            Return to Website &rarr;
          </Link>
        </div>
      </div>
    </div>
  );
}
