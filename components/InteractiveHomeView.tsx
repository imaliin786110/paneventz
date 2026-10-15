"use client";

import React, { useState, useEffect } from 'react';
import { Navigation } from './Navigation';
import { HeroTransform } from './HeroTransform';
import { WeddingJourney } from './WeddingJourney';
import { HorizontalGallery } from './HorizontalGallery';
import { CinematicFilmReveal } from './CinematicFilmReveal';
import { LayeredPhotos } from './LayeredPhotos';
import { TypographyChoreography } from './TypographyChoreography';
import { ColorGradeSlider } from './ColorGradeSlider';
import { Footer } from './Footer';
import { InquiryModal } from './InquiryModal';
import { ClientPortalModal } from './ClientPortalModal';
import { GuestPhotosModal } from './GuestPhotosModal';
import { AdminDashboard } from './admin/AdminDashboard';
import { useExperienceSettings } from '@/hooks/useExperienceSettings';
import { useScrollProgress } from '@/hooks/useScrollProgress';

// Authentic database sections
import StoriesGrid from './StoriesGrid';
import FilmsSection from './FilmsSection';
import InteractiveEstimator from './InteractiveEstimator';
import TestimonialsSection from './TestimonialsSection';
import FaqSection from './FaqSection';
import EnquiryForm from './EnquiryForm';

interface InteractiveHomeViewProps {
  setting: any;
  stories: any[];
  films: any[];
  services: any[];
  testimonials: any[];
  faqs: any[];
}

export default function InteractiveHomeView({
  setting,
  stories,
  films,
  services,
  testimonials,
  faqs
}: InteractiveHomeViewProps) {
  const { settings, updateSettings } = useExperienceSettings();
  const { totalProgress } = useScrollProgress();

  const [inquiryOpen, setInquiryOpen] = useState(false);
  const [clientPortalOpen, setClientPortalOpen] = useState(false);
  const [guestPhotosOpen, setGuestPhotosOpen] = useState(false);
  const [adminOpen, setAdminOpen] = useState(false);
  const [previewSettings, setPreviewSettings] = useState(settings);

  useEffect(() => {
    setPreviewSettings(settings);
  }, [settings]);

  return (
    <div className="relative min-h-screen bg-[#070709] text-[#e5e5e7] overflow-x-hidden">
      
      {/* Top Scroll Progress Bar */}
      <div className="fixed top-0 left-0 w-full h-[3px] z-[70] pointer-events-none">
        <div
          className="h-full w-full origin-left bg-gradient-to-r from-[#d8b886] via-[#fff4df] to-[#c4a472] shadow-[0_0_12px_rgba(196,164,114,0.9)]"
          style={{
            transform: `scaleX(${totalProgress})`,
            willChange: 'transform'
          }}
        />
      </div>

      {/* Global Luxury Navigation */}
      <Navigation
        onOpenInquiry={() => setInquiryOpen(true)}
        onOpenClientPortal={() => setClientPortalOpen(true)}
        onOpenGuestPhotos={() => setGuestPhotosOpen(true)}
        onToggleAdmin={() => setAdminOpen(!adminOpen)}
        isAdminOpen={adminOpen}
      />

      {/* The Signature Scroll-Transforming Hero */}
      <HeroTransform
        settings={previewSettings}
        onOpenInquiry={() => setInquiryOpen(true)}
      />

      {/* Heritage Stats Strip */}
      <section className="relative py-16 sm:py-20 px-6 bg-[#09090b] border-y border-white/5 overflow-hidden">
        <div className="max-w-7xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
          <div className="p-4">
            <span className="font-display text-4xl sm:text-6xl text-gold-gradient font-light block mb-1">
              {setting?.stat_1_number || "250"}{setting?.stat_1_suffix || "+"}
            </span>
            <span className="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-zinc-400 font-light">
              {setting?.stat_1_label || "Royal Weddings Documented"}
            </span>
          </div>
          <div className="p-4">
            <span className="font-display text-4xl sm:text-6xl text-gold-gradient font-light block mb-1">
              {setting?.stat_2_number || "10"}{setting?.stat_2_suffix || "+"}
            </span>
            <span className="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-zinc-400 font-light">
              {setting?.stat_2_label || "Years of Artistic Cinema"}
            </span>
          </div>
          <div className="p-4">
            <span className="font-display text-4xl sm:text-6xl text-gold-gradient font-light block mb-1">
              {setting?.stat_3_number || "35"}{setting?.stat_3_suffix || "+"}
            </span>
            <span className="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-zinc-400 font-light">
              {setting?.stat_3_label || "Palace & Heritage Venues"}
            </span>
          </div>
          <div className="p-4">
            <span className="font-display text-4xl sm:text-6xl text-gold-gradient font-light block mb-1">
              {setting?.stat_4_number || "100"}{setting?.stat_4_suffix || "%"}
            </span>
            <span className="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-zinc-400 font-light">
              {setting?.stat_4_label || "Handcrafted Heirlooms"}
            </span>
          </div>
        </div>
      </section>

      {/* Experience A: The Wedding Journey (Pinned 340vh Stage) */}
      <WeddingJourney />

      {/* Experience B: Horizontal Wedding Gallery */}
      <HorizontalGallery />

      {/* Authentic Database Palace Love Stories Grid */}
      <section id="stories" className="relative bg-[#0c0c0d] py-28 text-white border-t border-white/5">
        <div className="max-w-7xl mx-auto px-6 sm:px-12">
          <div className="text-center max-w-3xl mx-auto mb-16">
            <span className="text-xs uppercase tracking-[0.3em] text-[#c4a472] font-semibold mb-3 block">
              PALACE ARCHIVE
            </span>
            <h2 className="font-display text-4xl sm:text-6xl text-white font-medium uppercase">
              Curated Love Stories
            </h2>
            <p className="font-luxury text-2xl text-gold-gradient italic mt-2">
              Every couple documented with fine-art devotion across India and global destinations.
            </p>
          </div>
          <StoriesGrid stories={stories} />
        </div>
      </section>

      {/* Experience C: Cinematic Film Reveal */}
      <CinematicFilmReveal />

      {/* Authentic Database Films Section */}
      <FilmsSection films={films} />

      {/* Experience D: Layered Photo Compositions */}
      <LayeredPhotos />

      {/* Experience E: Typography Choreography */}
      <TypographyChoreography />

      {/* Interactive Color Science & Film Grade Slider */}
      <ColorGradeSlider />

      {/* Interactive Wedding Planner & Cost Estimator */}
      <InteractiveEstimator />

      {/* Couple Testimonials */}
      <TestimonialsSection testimonials={testimonials} />

      {/* Frequently Asked Questions */}
      <FaqSection faqs={faqs} />

      {/* Direct Booking & Consultation Form */}
      <EnquiryForm />

      {/* Luxury Footer with Counter-Rotating Marquees */}
      <Footer setting={setting} />

      {/* Interactive Modals */}
      <InquiryModal
        isOpen={inquiryOpen}
        onClose={() => setInquiryOpen(false)}
      />

      <ClientPortalModal
        isOpen={clientPortalOpen}
        onClose={() => setClientPortalOpen(false)}
      />

      <GuestPhotosModal
        isOpen={guestPhotosOpen}
        onClose={() => setGuestPhotosOpen(false)}
      />

      {/* Visual Experience Manager */}
      <AdminDashboard
        isOpen={adminOpen}
        onClose={() => setAdminOpen(false)}
        settings={previewSettings}
        onSaveSettings={(s) => updateSettings(s)}
        onLivePreview={(s) => setPreviewSettings(s)}
      />

    </div>
  );
}
