"use client";

import { useState, useRef, useEffect } from "react";
import type { FAQItem } from "@/types";
import { faqItems as defaultFaqItems } from "@/data/faq";
import DOMPurify from "dompurify";

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

  // Render answer as sanitized HTML (from rich text editor) or plain text
  const renderAnswer = (text: string, hasLink?: boolean) => {
    // Check if text contains HTML tags (from rich text editor)
    const isHTML = /<[a-z][\s\S]*>/i.test(text);

    if (isHTML) {
      // Sanitize and render HTML safely
      const sanitized = DOMPurify.sanitize(text, {
        ALLOWED_TAGS: ['p', 'br', 'strong', 'em', 'u', 'a', 'ul', 'ol', 'li', 'span', 'mark', 'div'],
        ALLOWED_ATTR: ['href', 'class', 'style', 'data-*'],
        ALLOWED_STYLES: {
          '*': {
            'color': [/^#([0-9a-fA-F]{3}){1,2}$/],
            'background-color': [/^#([0-9a-fA-F]{3}){1,2}$/],
            'font-size': [/^\d+(\.\d+)?(rem|em|px|%)$/],
            'font-family': [/.*/],
            'text-align': [/^(left|center|right|justify)$/],
          },
        },
      });

      return (
        <div
          dangerouslySetInnerHTML={{ __html: sanitized }}
          onClick={(e) => {
            // Handle clicks on links within rich text
            const target = e.target as HTMLElement;
            if (target.tagName === 'A') {
              const href = target.getAttribute('href');
              if (href === '#calculator' || target.textContent?.includes('Angebot anfragen')) {
                e.preventDefault();
                openCalculator(e as unknown as React.MouseEvent);
              }
            }
          }}
        />
      );
    }

    // Fallback: Plain text rendering (for backward compatibility)
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
  externalActiveIndex?: number | null;
  onToggle?: (index: number) => void;
}

export default function FAQ({ faqItems = defaultFaqItems, externalActiveIndex, onToggle }: FAQProps = {}) {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);

  // Use external index if provided (for editor sync)
  const displayIndex = externalActiveIndex !== undefined ? externalActiveIndex : activeIndex;

  const handleToggle = (index: number) => {
    const newIndex = displayIndex === index ? null : index;

    if (onToggle) {
      onToggle(index);
    } else {
      setActiveIndex(newIndex);
    }
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
            isActive={displayIndex === index}
            onToggle={() => handleToggle(index)}
          />
        ))}
      </div>
    </section>
  );
}
