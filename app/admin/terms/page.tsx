"use client";

import React, { useState, useEffect } from "react";
import { FileText, Save, CheckCircle2 } from "lucide-react";

export default function AdminTermsPage() {
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [notification, setNotification] = useState<string | null>(null);

  const [formData, setFormData] = useState<any>({
    id: null,
    advance_percentage: 40,
    balance_percentage: 60,
    balance_due: "Before final delivery of high-res master files",
    estimated_delivery_period: "6-8 weeks after event date",
    cancellation_policy: "Advance booking amounts are non-refundable and retain your exclusive calendar date.",
    content: "",
  });

  useEffect(() => {
    fetchTerms();
  }, []);

  const fetchTerms = async () => {
    try {
      setLoading(true);
      const res = await fetch("/api/admin/content/terms");
      const data = await res.json();
      if (res.ok && data.data && data.data.length > 0) {
        setFormData(data.data[0]);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch("/api/admin/content/terms", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });

      if (res.ok) {
        setNotification("VIP Contract Terms & Policies updated!");
        setTimeout(() => setNotification(null), 3000);
      }
    } catch (err) {
      console.error("Save terms error:", err);
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return <div className="p-12 text-center text-zinc-500 text-sm">Loading contract terms...</div>;
  }

  return (
    <div className="space-y-8 max-w-4xl">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
            VIP AGREEMENT CMS
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Terms & Contract Policies</h1>
          <p className="text-xs text-zinc-400 mt-1">
            Manage deposit percentages, delivery commitments, cancellation terms, and legal policies.
          </p>
        </div>

        <button
          onClick={handleSave}
          disabled={saving}
          className="bg-[#c4a472] hover:bg-[#b09060] text-black text-xs font-semibold uppercase tracking-wider py-3 px-6 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-[#c4a472]/20 shrink-0 disabled:opacity-50"
        >
          <Save size={16} />
          <span>{saving ? "Saving Policies..." : "Save Policies"}</span>
        </button>
      </div>

      {notification && (
        <div className="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs flex items-center gap-2">
          <CheckCircle2 size={16} /> {notification}
        </div>
      )}

      <form onSubmit={handleSave} className="bg-[#121214] border border-white/5 rounded-3xl p-6 sm:p-8 space-y-6">
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
              Advance Deposit Percentage (%)
            </label>
            <input
              type="number"
              name="advance_percentage"
              value={formData.advance_percentage || 40}
              onChange={handleChange}
              className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
            />
          </div>
          <div>
            <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
              Balance Percentage (%)
            </label>
            <input
              type="number"
              name="balance_percentage"
              value={formData.balance_percentage || 60}
              onChange={handleChange}
              className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
            />
          </div>
        </div>

        <div>
          <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
            Estimated Delivery Period
          </label>
          <input
            type="text"
            name="estimated_delivery_period"
            value={formData.estimated_delivery_period || ""}
            onChange={handleChange}
            placeholder="6-8 weeks from the date of the wedding"
            className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
          />
        </div>

        <div>
          <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
            Cancellation & Date Change Policy
          </label>
          <textarea
            name="cancellation_policy"
            rows={3}
            value={formData.cancellation_policy || ""}
            onChange={handleChange}
            className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
          />
        </div>

        <div>
          <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
            Full VIP Terms & Conditions Policy Body
          </label>
          <textarea
            name="content"
            rows={6}
            value={formData.content || ""}
            onChange={handleChange}
            placeholder="Full contractual clause markdown..."
            className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
          />
        </div>

        <div className="pt-4 border-t border-white/5 flex justify-end">
          <button
            type="submit"
            disabled={saving}
            className="px-8 py-3 bg-[#c4a472] hover:bg-[#b09060] text-black font-semibold text-xs uppercase tracking-wider rounded-xl transition-all disabled:opacity-50"
          >
            {saving ? "Saving Policies..." : "Save Policies"}
          </button>
        </div>
      </form>
    </div>
  );
}
