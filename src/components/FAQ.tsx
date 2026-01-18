"use client";

import { useState, useRef, useEffect } from "react";
import type { FAQItem } from "@/types";
import { faqItems as defaultFaqItems } from "@/data/faq";

function FAQItemComponent({ item, isActive, onToggle }: { item: FAQItem; isActive: boolean; onToggle: () => void }) {
  const answerRef = useRef<HTMLDivElement>(null);
  const [maxHeight, setMaxHeight] = useState("0px");

  useEffect(() => {
    if (isActive && answerRef.current) {
      setMaxHeight(`${answerRef.current.scrollHeight}px`);
    } else {
      setMaxHeight("0px");
    }
  }, [isActive]);

  // Handle window resize to recalculate height
  useEffect(() => {
    const handleResize = () => {
      if (isActive && answerRef.current) {
        setMaxHeight(`${answerRef.current.scrollHeight}px`);
      }
    };

    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, [isActive]);

  const openCalculator = (e: React.MouseEvent) => {
    e.stopPropagation();
    window.dispatchEvent(new CustomEvent("openMFFCalculator"));
  };

  // Replace the link text with a clickable span
  const renderAnswer = (text: string, hasLink?: boolean) => {
    if (hasLink) {
      const parts = text.split('"Unverbindliches Angebot anfragen"');
      return (
        <>
          {parts[0]}
          <span
            onClick={openCalculator}
            className="text-[#7dc9b1] cursor-pointer underline hover:text-[#5eb89d] transition-colors"
          >
Unverbindliches Angebot anfragen
          </span>
          {parts[1]}
        </>
      );
    }
    return text;
  };

  return (
    <div className="faq-item border-b border-[#e0e0e0]">
      <button
        onClick={onToggle}
        className="faq-question w-full bg-transparent border-none outline-none text-left text-[1.25rem] font-semibold text-black cursor-pointer flex justify-between items-center py-[30px] transition-opacity duration-300 hover:opacity-70"
        style={{ fontFamily: "'Poppins', sans-serif" }}
      >
        <span className="pr-4">{item.question}</span>
        <span
          className="icon text-2xl font-light min-w-[30px] text-center ml-5 transition-transform duration-300"
          style={{ transform: isActive ? 'rotate(45deg)' : 'rotate(0deg)' }}
        >
          +
        </span>
      </button>
      <div
        ref={answerRef}
        className="faq-answer overflow-hidden transition-[max-height] duration-400 ease-out"
        style={{ maxHeight }}
      >
        <p className="pb-[30px] text-base leading-[1.6] font-light text-[#333] whitespace-pre-line">
          {renderAnswer(item.answer, item.hasLink)}
        </p>
      </div>
    </div>
  );
}

interface FAQProps {
  faqItems?: FAQItem[];
}

export default function FAQ({ faqItems = defaultFaqItems }: FAQProps = {}) {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);

  const handleToggle = (index: number) => {
    setActiveIndex(activeIndex === index ? null : index);
  };

  return (
    <section
      className="faq-section max-w-[900px] mx-auto px-5"
      style={{ fontFamily: "'Poppins', sans-serif" }}
    >
      <div className="faq-container border-t border-[#e0e0e0]">
        {faqItems.map((item, index) => (
          <FAQItemComponent
            key={index}
            item={item}
            isActive={activeIndex === index}
            onToggle={() => handleToggle(index)}
          />
        ))}
      </div>
    </section>
  );
}
