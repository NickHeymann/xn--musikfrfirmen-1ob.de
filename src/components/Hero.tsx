"use client";

import { useEffect, useRef, useState } from "react";
import { getAssetPath } from "@/lib/config";

interface HeroProps {
  sliderContent?: string[];
  backgroundVideo?: string;
  ctaText?: string;
  features?: string[];
}

export default function Hero({
  sliderContent = ["Musik", "Livebands", "Djs", "Technik"],
  backgroundVideo = "/videos/hero-background.mp4",
  ctaText = "Unverbindliches Angebot anfragen",
  features = [
    "Musik für jedes Firmenevent",
    "Rundum-sorglos-Paket",
    "Angebot innerhalb von 24 Stunden"
  ]
}: HeroProps = {}) {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [letters, setLetters] = useState<string[]>([]);
  const [isHolding, setIsHolding] = useState(false);
  const [scrollPromptVisible, setScrollPromptVisible] = useState(true);
  const videoRef = useRef<HTMLVideoElement>(null);

  // Update letters when currentIndex changes
  useEffect(() => {
    const word = sliderContent[currentIndex];
    setLetters(word.split(""));
    setIsHolding(true);

    const holdTimer = setTimeout(() => {
      setIsHolding(false);
    }, 2650);

    return () => clearTimeout(holdTimer);
  }, [currentIndex]);

  // Auto-advance slider
  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % sliderContent.length);
    }, 3000);

    return () => clearInterval(interval);
  }, [])

  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 100) {
        setScrollPromptVisible(false);
      } else {
        setScrollPromptVisible(true);
      }
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  // Force video to play on mount (browser autoplay policies)
  useEffect(() => {
    const video = videoRef.current;
    if (video) {
      video.play().catch(() => {
        // Autoplay blocked - video will show poster
      });
    }
  }, []);

  const handleScrollPromptClick = () => {
    const target = document.getElementById("waswirbieten");
    if (target) {
      target.scrollIntoView({ behavior: "smooth" });
    } else {
      window.scrollBy({ top: window.innerHeight, behavior: "smooth" });
    }
  };

  const openCalculator = () => {
    window.dispatchEvent(new CustomEvent("openMFFCalculator"));
  };

  return (
    <section className="relative flex items-center justify-center overflow-hidden" style={{ minHeight: "calc(100vh - 108px)" }}>
      <div className="absolute inset-0 z-0">
        <video
          ref={videoRef}
          autoPlay
          loop
          muted
          playsInline
          className="absolute inset-0 w-full h-full object-cover"
          poster={getAssetPath("/images/hero-fallback.jpg")}
        >
          <source src={getAssetPath(backgroundVideo)} type="video/mp4" />
        </video>
        <div
          className="absolute inset-0"
          style={{ backgroundColor: "rgba(0, 0, 0, 0.36)" }}
        />
      </div>

      <div className="relative z-10 w-full max-w-[1200px] mx-auto px-4 text-center">
        <div
          className="slider-container text-center text-white font-bold"
          style={{ fontFamily: "'Poppins', sans-serif", marginBottom: "160px" }}
        >
          <div
            id="slider"
            className="inline-flex flex-row gap-[5px] items-center justify-center flex-wrap"
            style={{
              fontSize: "clamp(31px, 5vw, 55px)",
              letterSpacing: "1.5px"
            }}
          >
            <span className="static-text whitespace-nowrap">Deine</span>
            <div className="animated-section inline-flex items-center gap-[5px]">
              <span
                id="sliderValue"
                className={`inline-flex text-[#B2EAD8] font-bold justify-start whitespace-nowrap text-left ${
                  isHolding ? "holder-animation" : ""
                }`}
                style={{ letterSpacing: "1.5px" }}
              >
                {letters.map((letter, index) => (
                  <span
                    key={`${currentIndex}-${index}`}
                    className="inline-block animate-letter-fade"
                    style={{
                      animationDelay: `${index * 0.04 + 0.05}s`,
                    }}
                  >
                    {letter === " " ? "\u00A0" : letter}
                  </span>
                ))}
              </span>
              <span className="static-text whitespace-nowrap">für Firmenevents!</span>
            </div>
          </div>
        </div>

        <div className="mt-12">
          <button
            onClick={openCalculator}
            className="mff-open-calculator-btn inline-block mx-auto px-12 py-[18px] bg-white text-[#292929] rounded-[50px] text-lg font-medium cursor-pointer transition-all duration-300 hover:bg-[#B2EAD8] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)]"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            {ctaText}
          </button>
        </div>

        <ul className="mff-btn-features">
          {features.map((feature, index) => (
            <li key={index}>{feature}</li>
          ))}
        </ul>
      </div>

      <div
        className={`scroll-prompt fixed bottom-8 left-1/2 -translate-x-1/2 cursor-pointer z-20 transition-opacity duration-300 ${
          scrollPromptVisible ? "opacity-100" : "opacity-0 pointer-events-none"
        }`}
        onClick={handleScrollPromptClick}
      >
        <div className="scroll-prompt-arrow-container flex flex-col items-center animate-scroll-bounce">
          <div className="scroll-prompt-arrow">
            <div className="w-9 h-9 border-r-[6px] border-b-[6px] border-white/60 rotate-45" />
          </div>
          <div className="scroll-prompt-arrow -mt-3">
            <div className="w-9 h-9 border-r-[6px] border-b-[6px] border-white/60 rotate-45" />
          </div>
        </div>
      </div>

      <style jsx>{`
        @keyframes letterFade {
          0% {
            opacity: 0;
            transform: translateY(20px);
          }
          100% {
            opacity: 1;
            transform: translateY(0);
          }
        }

        .animate-letter-fade {
          animation: letterFade 0.35s forwards;
          opacity: 0;
        }

        @keyframes holderAnimation {
          0% { opacity: 1; }
          88% { opacity: 1; }
          100% { opacity: 0; }
        }

        .holder-animation {
          animation: holderAnimation 3s;
        }

        .mff-btn-features {
          max-width: 350px;
          margin: 60px auto 0;
          padding: 0;
          list-style: none;
          display: flex;
          flex-direction: column;
          align-items: flex-start;
          font-family: 'Poppins', sans-serif;
        }

        .mff-btn-features li {
          width: 100%;
          display: grid;
          grid-template-columns: 20px 1fr;
          column-gap: 12px;
          font-size: calc(14px + 2pt);
          font-weight: 300;
          color: white;
          margin: 3px 0;
          opacity: 0;
          animation: fadeInText 0.6s ease-out forwards;
          text-align: left;
        }

        .mff-btn-features li:nth-child(1) {
          animation-delay: 0.3s;
        }

        .mff-btn-features li:nth-child(2) {
          animation-delay: 0.9s;
        }

        .mff-btn-features li:nth-child(3) {
          animation-delay: 1.5s;
        }

        .mff-btn-features li::before {
          content: '✔';
          color: #B2EAD8;
          font-weight: 700;
          font-size: 20px;
          display: flex;
          align-items: center;
          justify-content: center;
          opacity: 0;
          transform: scale(0) rotate(-180deg);
          animation: popInCheck 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        .mff-btn-features li:nth-child(1)::before {
          animation-delay: 0s;
        }

        .mff-btn-features li:nth-child(2)::before {
          animation-delay: 0.6s;
        }

        .mff-btn-features li:nth-child(3)::before {
          animation-delay: 1.2s;
        }

        @keyframes popInCheck {
          0% {
            opacity: 0;
            transform: scale(0) rotate(-180deg);
          }
          70% {
            transform: scale(1.2) rotate(10deg);
          }
          100% {
            opacity: 1;
            transform: scale(1) rotate(0deg);
          }
        }

        @keyframes fadeInText {
          0% {
            opacity: 0;
            transform: translateX(-10px);
          }
          100% {
            opacity: 1;
            transform: translateX(0);
          }
        }

        @keyframes scrollBounce {
          0%, 100% {
            transform: translateY(0);
          }
          50% {
            transform: translateY(20px);
          }
        }

        .animate-scroll-bounce {
          animation: scrollBounce 1.5s infinite;
        }

        @media (max-width: 768px) {
          .mff-btn-features {
            margin-top: 20px;
            padding: 0 16px;
          }

          .mff-btn-features li {
            font-size: 14px;
            padding: 6px 0 6px 28px;
          }
        }
      `}</style>
    </section>
  );
}
