"use client";

import React, { useEffect, useState } from "react";

export default function ScrollLivingEffects() {
  const [scrollProgress, setScrollProgress] = useState(0);
  const [isScrolling, setIsScrolling] = useState(false);
  const [scrollDir, setScrollDir] = useState<"up" | "down">("down");
  const [mousePos, setMousePos] = useState({ x: -1000, y: -1000 });

  useEffect(() => {
    let lastScrollY = window.scrollY;
    let scrollTimeout: NodeJS.Timeout | null = null;
    let ticking = false;

    const onScroll = () => {
      const currentScrollY = window.scrollY;
      const totalHeight = document.documentElement.scrollHeight - window.innerHeight;

      if (!ticking) {
        window.requestAnimationFrame(() => {
          // Calculate precise scroll percentage
          const progress = totalHeight > 0 ? (currentScrollY / totalHeight) * 100 : 0;
          setScrollProgress(progress);

          // Determine scroll direction
          if (currentScrollY > lastScrollY) {
            setScrollDir("down");
          } else if (currentScrollY < lastScrollY) {
            setScrollDir("up");
          }

          setIsScrolling(true);
          lastScrollY = currentScrollY;
          ticking = false;
        });
        ticking = true;
      }

      if (scrollTimeout) clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        setIsScrolling(false);
      }, 150);
    };

    const onMouseMove = (e: MouseEvent) => {
      setMousePos({ x: e.clientX, y: e.clientY });
    };

    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("mousemove", onMouseMove, { passive: true });

    // Intersection observer for continuous text and element glow triggers
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("in-view-glow");
          }
        });
      },
      { threshold: 0.15, rootMargin: "0px 0px -50px 0px" }
    );

    const observeElements = () => {
      const targets = document.querySelectorAll(
        "h1, h2, h3, .font-serif, .stat-counter-box, .luxury-card-interactive, [data-glow]"
      );
      targets.forEach((el) => observer.observe(el));
    };

    observeElements();

    return () => {
      window.removeEventListener("scroll", onScroll);
      window.removeEventListener("mousemove", onMouseMove);
      if (scrollTimeout) clearTimeout(scrollTimeout);
      observer.disconnect();
    };
  }, []);

  return (
    <>
      {/* 1. TOP AMBIENT GOLD SCROLL PROGRESSION BEAM */}
      <div className="fixed top-0 left-0 w-full h-[2.5px] z-[70] pointer-events-none bg-transparent">
        <div
          className="h-full bg-gradient-to-r from-[#c4a472] via-[#fff4df] to-[#b38a4c] shadow-[0_0_14px_rgba(196,164,114,0.95),0_0_24px_rgba(247,235,215,0.7)] transition-all duration-150 ease-out"
          style={{ width: `${scrollProgress}%` }}
        />
      </div>

      {/* 2. LIVING MULTI-COLOR AMBIENT BACKGROUND SYSTEM */}
      <div className="fixed inset-0 pointer-events-none z-[1] overflow-hidden">
        {/* Layer A: Royal Champagne Gold Aurora (Top-Right) */}
        <div
          className="absolute -top-[10%] -right-[5%] w-[650px] h-[650px] rounded-full bg-[radial-gradient(circle,rgba(196,164,114,0.085)_0%,rgba(212,175,55,0.04)_45%,transparent_70%)] blur-[95px] animate-bg-drift-1 pointer-events-none"
          style={{
            transform: `translate3d(0, ${scrollDir === "down" ? "-25px" : "25px"}, 0) scale(${
              isScrolling ? 1.08 : 1
            })`,
            transition: "transform 0.8s cubic-bezier(0.16, 1, 0.3, 1)",
          }}
        />

        {/* Layer B: Velvet Rose / Sunset Blush Aurora (Mid-Left) */}
        <div
          className="absolute top-[35%] -left-[10%] w-[600px] h-[600px] rounded-full bg-[radial-gradient(circle,rgba(180,60,105,0.065)_0%,rgba(217,119,87,0.035)_50%,transparent_70%)] blur-[110px] animate-bg-drift-2 pointer-events-none"
          style={{
            transform: `translate3d(0, ${scrollDir === "down" ? "30px" : "-30px"}, 0) scale(${
              isScrolling ? 1.12 : 1
            })`,
            transition: "transform 0.8s cubic-bezier(0.16, 1, 0.3, 1)",
          }}
        />

        {/* Layer C: Cinematic Sapphire Indigo Glow (Mid-Right) */}
        <div
          className="absolute top-[60%] -right-[8%] w-[580px] h-[580px] rounded-full bg-[radial-gradient(circle,rgba(20,90,160,0.065)_0%,rgba(0,180,216,0.03)_50%,transparent_70%)] blur-[105px] animate-bg-drift-3 pointer-events-none"
          style={{
            transform: `translate3d(0, ${scrollDir === "down" ? "-35px" : "35px"}, 0) scale(${
              isScrolling ? 1.1 : 1
            })`,
            transition: "transform 0.8s cubic-bezier(0.16, 1, 0.3, 1)",
          }}
        />

        {/* Layer D: Deep Warm Gold Ember Glow (Bottom-Left / Center) */}
        <div
          className="absolute -bottom-[10%] left-[20%] w-[700px] h-[700px] rounded-full bg-[radial-gradient(circle,rgba(196,164,114,0.075)_0%,rgba(245,210,140,0.035)_50%,transparent_70%)] blur-[120px] animate-bg-drift-4 pointer-events-none"
          style={{
            transform: `translate3d(0, ${scrollDir === "down" ? "20px" : "-20px"}, 0) scale(${
              isScrolling ? 1.06 : 1
            })`,
            transition: "transform 0.8s cubic-bezier(0.16, 1, 0.3, 1)",
          }}
        />

        {/* Layer E: Interactive Desktop Cursor Light Glow */}
        {mousePos.x > 0 && (
          <div
            className="hidden md:block absolute w-[400px] h-[400px] rounded-full bg-[radial-gradient(circle,rgba(196,164,114,0.04)_0%,rgba(247,235,215,0.015)_50%,transparent_70%)] blur-[60px] pointer-events-none transition-transform duration-300 ease-out"
            style={{
              left: `${mousePos.x - 200}px`,
              top: `${mousePos.y - 200}px`,
              transform: `scale(${isScrolling ? 1.2 : 1})`,
            }}
          />
        )}
      </div>
    </>
  );
}
