"use client";

import { useState, useEffect, useCallback } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { useModal } from "./ModalProvider";

const rotatingWords = ["Musik", "Livebands", "DJs", "Technik"];

export default function Hero() {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isVisible, setIsVisible] = useState(true);
  const { openContactModal } = useModal();

  const rotateWord = useCallback(() => {
    setIsVisible(false);
    setTimeout(() => {
      setCurrentIndex((prev) => (prev + 1) % rotatingWords.length);
      setIsVisible(true);
    }, 350);
  }, []);

  useEffect(() => {
    const interval = setInterval(rotateWord, 3000);
    return () => clearInterval(interval);
  }, [rotateWord]);

  const scrollToLeistungen = () => {
    document.getElementById("leistungen")?.scrollIntoView({ behavior: "smooth" });
  };

  return (
    <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
      {/* Video Background */}
      <div className="absolute inset-0 z-0">
        <video
          autoPlay
          muted
          loop
          playsInline
          className="w-full h-full object-cover object-[50%_0%]"
          poster="/images/hero-fallback.jpg"
        >
          <source src="/videos/hero-background.mp4" type="video/mp4" />
        </video>
        {/* Overlay */}
        <div className="absolute inset-0 bg-black/36" />
      </div>

      {/* Content */}
      <div className="relative z-10 text-center px-4 max-w-5xl mx-auto">
        {/* Animated Headline */}
        <h1 className="text-white font-bold text-4xl sm:text-5xl md:text-6xl lg:text-7xl tracking-wide mb-8">
          <span>Deine </span>
          <span className="inline-flex min-w-[200px] sm:min-w-[280px] justify-start">
            <AnimatePresence mode="wait">
              {isVisible && (
                <motion.span
                  key={currentIndex}
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  exit={{ opacity: 0, y: -20 }}
                  transition={{ duration: 0.35 }}
                  className="text-[#B2EAD8]"
                >
                  {rotatingWords[currentIndex].split("").map((letter, i) => (
                    <motion.span
                      key={i}
                      initial={{ opacity: 0, y: 20 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ delay: i * 0.04, duration: 0.35 }}
                    >
                      {letter}
                    </motion.span>
                  ))}
                </motion.span>
              )}
            </AnimatePresence>
          </span>
          <br className="sm:hidden" />
          <span> für Firmenevents!</span>
        </h1>

        {/* CTA Button */}
        <motion.button
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.5, duration: 0.6 }}
          onClick={openContactModal}
          className="btn-primary text-lg"
        >
          Unverbindliches Angebot anfragen
        </motion.button>

        {/* Features */}
        <motion.ul
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.8, duration: 0.6 }}
          className="mt-12 space-y-2 text-white/90"
        >
          {[
            "Musik für jedes Firmenevent",
            "Rundum-sorglos-Paket",
            "Angebot innerhalb von 24 Stunden",
          ].map((feature, i) => (
            <motion.li
              key={feature}
              initial={{ opacity: 0, x: -10 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: 0.8 + i * 0.2 }}
              className="flex items-center justify-center gap-3 text-base sm:text-lg font-light"
            >
              <span className="text-[#B2EAD8] font-bold text-xl">✔</span>
              {feature}
            </motion.li>
          ))}
        </motion.ul>
      </div>

      {/* Scroll Prompt - matching Squarespace style */}
      <motion.button
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ delay: 1.5 }}
        onClick={scrollToLeistungen}
        className="absolute bottom-0 left-1/2 -translate-x-1/2 z-10 cursor-pointer w-40 h-20"
        aria-label="Nach unten scrollen"
      >
        <div className="scroll-prompt-container absolute top-0 left-1/2 -ml-[18px]">
          <div className="scroll-arrow">
            <div className="w-9 h-9 border-r-[6px] border-b-[6px] border-white/60 rotate-45" />
          </div>
          <div className="scroll-arrow -mt-1.5">
            <div className="w-9 h-9 border-r-[6px] border-b-[6px] border-white/60 rotate-45" />
          </div>
        </div>
      </motion.button>
    </section>
  );
}
