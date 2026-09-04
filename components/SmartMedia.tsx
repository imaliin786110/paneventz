"use client";

import React, { useRef, useEffect, useState } from "react";

interface SmartMediaProps {
  src?: string | null;
  alt?: string;
  className?: string;
  containerClassName?: string;
  poster?: string;
  priority?: boolean;
}

const VIDEO_EXTENSIONS = [".mp4", ".webm", ".mov", ".m4v", ".ogg"];

export function isVideoSource(url?: string | null): boolean {
  if (!url) return false;
  const cleanUrl = url.split("?")[0].split("#")[0].toLowerCase();
  return VIDEO_EXTENSIONS.some((ext) => cleanUrl.endsWith(ext));
}

export function formatMediaUrl(url?: string | null, fallback = "/images/1.webp"): string {
  if (!url) return fallback;
  if (url.startsWith("http") || url.startsWith("/")) return url;
  return `/storage/${url}`;
}

export default function SmartMedia({
  src,
  alt = "Paneventz Luxury Wedding Media",
  className = "w-full h-full object-cover",
  containerClassName = "relative w-full h-full overflow-hidden bg-black",
  poster,
  priority = false,
}: SmartMediaProps) {
  const videoRef = useRef<HTMLVideoElement | null>(null);
  const [isInView, setIsInView] = useState(priority);
  const [isLoaded, setIsLoaded] = useState(false);

  const isVideo = isVideoSource(src);
  const mediaUrl = formatMediaUrl(src);

  // VIEWPORT-AWARE LAZY AUTOPLAY FOR ULTRA-FAST LOADING
  useEffect(() => {
    if (!isVideo || priority) return;

    const el = videoRef.current;
    if (!el) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            setIsInView(true);
            if (el.paused) {
              el.play().catch(() => {
                // Auto-play was prevented by browser policy (safely handled)
              });
            }
          } else {
            if (!el.paused) {
              el.pause();
            }
          }
        });
      },
      {
        rootMargin: "200px 0px", // Preload slightly before scrolling into view
        threshold: 0.1,
      }
    );

    observer.observe(el);
    return () => observer.disconnect();
  }, [isVideo, priority]);

  // AUTOPLAY ATTEMPT ON MOUNT
  useEffect(() => {
    if (isVideo && videoRef.current && (isInView || priority)) {
      videoRef.current.play().catch(() => {});
    }
  }, [isVideo, isInView, priority]);

  if (isVideo) {
    return (
      <div className={containerClassName}>
        <video
          ref={videoRef}
          src={isInView || priority ? mediaUrl : undefined}
          poster={poster}
          autoPlay
          loop
          muted
          playsInline
          preload={priority ? "auto" : "metadata"}
          controls={false}
          disablePictureInPicture
          disableRemotePlayback
          onLoadedData={() => setIsLoaded(true)}
          className={`${className} ${
            isLoaded ? "opacity-100" : "opacity-90"
          } transition-opacity duration-700`}
        />
      </div>
    );
  }

  return (
    <div className={containerClassName}>
      <img
        src={mediaUrl}
        alt={alt}
        loading={priority ? "eager" : "lazy"}
        decoding="async"
        onLoad={() => setIsLoaded(true)}
        className={`${className} ${
          isLoaded ? "opacity-100" : "opacity-90"
        } transition-opacity duration-700`}
      />
    </div>
  );
}
