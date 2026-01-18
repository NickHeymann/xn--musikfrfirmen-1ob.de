"use client";

import { useEffect, useRef, useState } from "react";
import { serviceBlocks as defaultServiceBlocks } from "@/data/services";
import type { ServiceBlock } from "@/types";

interface ProcessStepsProps {
  serviceBlocks?: ServiceBlock[];
  ctaText?: string;
}

export default function ProcessSteps({
  serviceBlocks = defaultServiceBlocks,
  ctaText = "Zum Angebot"
}: ProcessStepsProps = {}) {
  const [visibleBlocks, setVisibleBlocks] = useState<boolean[]>([false, false, false]);
  const [fillProgress, setFillProgress] = useState<number[]>([0, 0, 0]);
  const [lineProgress, setLineProgress] = useState<number[]>([0, 0]);
  const [ctaVisible, setCtaVisible] = useState(false);
  const [animationStarted, setAnimationStarted] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && !animationStarted) {
            setAnimationStarted(true);
            startAnimation();
          }
        });
      },
      { threshold: 0.3 }
    );

    if (containerRef.current) {
      observer.observe(containerRef.current);
    }

    return () => observer.disconnect();
  }, [animationStarted]);

  const animateFill = (blockIndex: number, duration: number): Promise<void> => {
    return new Promise((resolve) => {
      const startTime = Date.now();

      const animate = () => {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);

        const easeProgress = progress < 0.5
          ? 2 * progress * progress
          : 1 - Math.pow(-2 * progress + 2, 2) / 2;

        setFillProgress(prev => {
          const newProgress = [...prev];
          newProgress[blockIndex] = easeProgress;
          return newProgress;
        });

        if (progress < 1) {
          requestAnimationFrame(animate);
        } else {
          setTimeout(resolve, 400);
        }
      };

      requestAnimationFrame(animate);
    });
  };

  const animateLine = (lineIndex: number, duration: number): Promise<void> => {
    return new Promise((resolve) => {
      const startTime = Date.now();

      const animate = () => {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);

        setLineProgress(prev => {
          const newProgress = [...prev];
          newProgress[lineIndex] = progress;
          return newProgress;
        });

        if (progress < 1) {
          requestAnimationFrame(animate);
        } else {
          resolve();
        }
      };

      requestAnimationFrame(animate);
    });
  };

  const startAnimation = async () => {
    setVisibleBlocks(prev => [true, prev[1], prev[2]]);

    await new Promise(resolve => setTimeout(resolve, 300));
    await animateFill(0, 800);
    await animateLine(0, 200);

    setVisibleBlocks(prev => [prev[0], true, prev[2]]);

    await new Promise(resolve => setTimeout(resolve, 300));
    await animateFill(1, 800);
    await animateLine(1, 200);

    setVisibleBlocks(prev => [prev[0], prev[1], true]);

    await new Promise(resolve => setTimeout(resolve, 300));
    await animateFill(2, 800);

    setCtaVisible(true);
  };

  const openCalculator = () => {
    window.dispatchEvent(new CustomEvent("openMFFCalculator"));
  };

  const getConicGradient = (progress: number) => {
    const degrees = progress * 360;
    return `conic-gradient(
      from 0deg at 50% 50%,
      #D4F4E8 0deg,
      #B2EAD8 ${degrees * 0.95}deg,
      #D4F4E8 ${degrees}deg,
      transparent ${degrees}deg,
      transparent 360deg
    )`;
  };

  return (
    <div
      ref={containerRef}
      className="service-animation-container flex flex-col items-center"
      style={{ fontFamily: "'Poppins', sans-serif" }}
    >
      <div className="service-blocks-wrapper flex flex-col md:flex-row items-center gap-0">
        {serviceBlocks.map((block, index) => (
          <div key={block.id} className="flex flex-col md:flex-row items-center">
            <div
              className={`service-block relative bg-white rounded-xl shadow-[0_2px_15px_rgba(0,0,0,0.06)]
                w-full md:w-[380px] min-h-[350px] max-h-[350px] overflow-hidden
                flex flex-col items-center justify-center p-10 md:p-[50px_40px]
                transition-all duration-300
                ${visibleBlocks[index] ? 'opacity-100' : 'opacity-0'}
                hover:-translate-y-[5px] hover:shadow-[0_8px_30px_rgba(0,0,0,0.12)]`}
              style={{ transition: 'opacity 1.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.3s ease, box-shadow 0.3s ease' }}
            >
              <div
                className="absolute inset-0 rounded-xl"
                style={{
                  background: getConicGradient(fillProgress[index]),
                  opacity: fillProgress[index] > 0 && fillProgress[index] < 1 ? 0.7 : 0,
                  transition: 'opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                }}
              />

              <div className="relative z-10 text-center w-full max-w-[300px]">
                <h3 className="text-[2rem] font-semibold text-[#1a1a1a] mb-5 leading-[1.2] min-h-[40px] flex items-center justify-center">
                  {block.title}
                </h3>
                <p className="text-[#4a4a4a] font-light leading-[1.7]">
                  {block.text}
                  <span className="text-[#2DD4A8] font-semibold">{block.highlight}</span>
                  {block.description}
                </p>
              </div>
            </div>

            {index < 2 && (
              <div className="service-connecting-line w-16 h-1 md:w-16 md:h-1 relative bg-[#e5e7eb] rounded-sm my-4 md:my-0 md:mx-0 rotate-90 md:rotate-0">
                <div
                  className="absolute top-0 left-0 h-full bg-[#2DD4A8] rounded-sm"
                  style={{
                    width: `${lineProgress[index] * 100}%`,
                    transition: 'width 0.05s linear',
                  }}
                />
              </div>
            )}
          </div>
        ))}
      </div>

      <div className="w-full flex justify-center mt-[60px]">
        <button
          onClick={openCalculator}
          className={`service-cta-button py-[18px] px-12 bg-[#D4F4E8] text-[#292929] border-none rounded-[50px]
            text-lg font-medium cursor-pointer transition-all duration-300
            hover:bg-[#7dc9b1] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)]
            ${ctaVisible ? 'opacity-100' : 'opacity-0'}`}
          style={{
            fontFamily: "'Poppins', sans-serif",
            transition: 'all 0.3s ease, opacity 1s ease-out',
          }}
        >
          {ctaText}
        </button>
      </div>

      <style jsx>{`
        @media (max-width: 900px) {
          .service-connecting-line {
            width: 4px !important;
            height: 40px !important;
            transform: rotate(0deg) !important;
          }
        }
      `}</style>
    </div>
  );
}
