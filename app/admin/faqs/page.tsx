"use client";

import React, { useState, useEffect } from "react";
import { Plus, Edit, Trash2, CheckCircle2, X, HelpCircle } from "lucide-react";

export default function AdminFaqsPage() {
  const [faqs, setFaqs] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [modalOpen, setModalOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [notification, setNotification] = useState<string | null>(null);

  const [editingId, setEditingId] = useState<string | null>(null);
  const [question, setQuestion] = useState("");
  const [answer, setAnswer] = useState("");

  useEffect(() => {
    fetchFaqs();
  }, []);

  const fetchFaqs = async () => {
    try {
      setLoading(true);
      const res = await fetch("/api/admin/content/faqs");
      const data = await res.json();
      if (res.ok) setFaqs(data.data || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const openCreateModal = () => {
    setEditingId(null);
    setQuestion("");
    setAnswer("");
    setModalOpen(true);
  };

  const openEditModal = (faq: any) => {
    setEditingId(String(faq.id));
    setQuestion(faq.question || "");
    setAnswer(faq.answer || "");
    setModalOpen(true);
  };

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch("/api/admin/content/faqs", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id: editingId,
          question,
          answer,
        }),
      });

      if (res.ok) {
        setNotification(editingId ? "FAQ updated!" : "New FAQ created!");
        setTimeout(() => setNotification(null), 3000);
        setModalOpen(false);
        fetchFaqs();
      }
    } catch (err) {
      console.error("Save FAQ error:", err);
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id: string) => {
    if (!confirm("Are you sure you want to delete this FAQ?")) return;
    try {
      const res = await fetch(`/api/admin/content/faqs?id=${id}`, { method: "DELETE" });
      if (res.ok) {
        setNotification("FAQ deleted.");
        setTimeout(() => setNotification(null), 3000);
        fetchFaqs();
      }
    } catch (err) {
      console.error("Delete error:", err);
    }
  };

  return (
    <div className="space-y-8">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold block mb-1">
            CLIENT KNOWLEDGE CMS
          </span>
          <h1 className="font-serif text-3xl text-white font-light">Frequently Asked Questions</h1>
          <p className="text-xs text-zinc-400 mt-1">
            Manage your accordion FAQs and answers displayed across the website.
          </p>
        </div>
        <button
          onClick={openCreateModal}
          className="bg-[#c4a472] hover:bg-[#b09060] text-black text-xs font-semibold uppercase tracking-wider py-3 px-5 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-[#c4a472]/20 shrink-0 self-start sm:self-auto"
        >
          <Plus size={16} /> Add New FAQ
        </button>
      </div>

      {notification && (
        <div className="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-xs flex items-center gap-2">
          <CheckCircle2 size={16} /> {notification}
        </div>
      )}

      {loading ? (
        <div className="p-12 text-center text-zinc-500 text-sm">Loading FAQs...</div>
      ) : (
        <div className="space-y-4 max-w-4xl">
          {faqs.map((faq) => (
            <div
              key={faq.id}
              className="bg-[#121214] border border-white/5 rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-white/10 transition-all"
            >
              <div className="space-y-1">
                <h3 className="font-serif text-lg text-white font-medium">{faq.question}</h3>
                <p className="text-xs text-zinc-400 leading-relaxed">{faq.answer}</p>
              </div>

              <div className="flex items-center gap-3 shrink-0 self-end sm:self-center">
                <button
                  onClick={() => openEditModal(faq)}
                  className="p-2 text-zinc-400 hover:text-white bg-white/5 rounded-xl transition-colors"
                >
                  <Edit size={14} />
                </button>
                <button
                  onClick={() => handleDelete(String(faq.id))}
                  className="p-2 text-red-400 hover:text-red-300 bg-red-500/10 rounded-xl transition-colors"
                >
                  <Trash2 size={14} />
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      {modalOpen && (
        <div className="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
          <div className="bg-[#121214] border border-white/10 rounded-3xl p-6 sm:p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto relative shadow-2xl">
            <button
              onClick={() => setModalOpen(false)}
              className="absolute top-6 right-6 text-zinc-400 hover:text-white"
            >
              <X size={20} />
            </button>

            <h2 className="font-serif text-2xl text-white font-light mb-4">
              {editingId ? "Edit FAQ" : "Add New FAQ"}
            </h2>

            <form onSubmit={handleSave} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Question *
                </label>
                <input
                  type="text"
                  value={question}
                  onChange={(e) => setQuestion(e.target.value)}
                  required
                  placeholder="e.g. How far in advance should we book?"
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold uppercase text-zinc-400 mb-1">
                  Answer *
                </label>
                <textarea
                  value={answer}
                  onChange={(e) => setAnswer(e.target.value)}
                  required
                  rows={4}
                  placeholder="We recommend reserving dates 6 to 12 months in advance..."
                  className="w-full bg-[#18181b] border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:outline-none focus:border-[#c4a472]"
                />
              </div>

              <div className="pt-4 flex items-center justify-end gap-3">
                <button
                  type="button"
                  onClick={() => setModalOpen(false)}
                  className="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-white text-xs rounded-xl"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={saving}
                  className="px-6 py-2.5 bg-[#c4a472] hover:bg-[#b09060] text-black font-semibold text-xs uppercase tracking-wider rounded-xl transition-all"
                >
                  {saving ? "Saving..." : "Save FAQ"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
