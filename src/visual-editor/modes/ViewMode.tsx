"use client";

import type { FC } from "react";
import { useEditor } from "../context/EditorContext";
import Hero from "@/components/Hero";
import ServiceCards from "@/components/ServiceCards";
import ProcessSteps from "@/components/ProcessSteps";
import TeamSection from "@/components/TeamSection";
import FAQ from "@/components/FAQ";
import LogoAnimation from "@/components/LogoAnimation";
import HamburgAnimation from "@/components/HamburgAnimation";

const componentRegistry: Record<string, FC<Record<string, unknown>>> = {
  Hero,
  ServiceCards,
  ProcessSteps,
  TeamSection,
  FAQ,
  CTASection: LogoAnimation, // Map CTASection to LogoAnimation for compatibility
};

/**
 * ViewMode - Renders blocks with exact same structure as live website
 * Matches src/app/page.tsx pixel-perfectly
 */
export function ViewMode() {
  const { blocks } = useEditor();

  const scrollToSection = (sectionId: string) => {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: "smooth" });
    }
  };

  return (
    <div className="view-mode">
      {blocks.map((block) => {
        const Component = componentRegistry[block.type];
        if (!Component) return null;

        // Match exact section structure from page.tsx
        switch (block.type) {
          case "Hero":
            return <Hero key={block.id} {...block.props} />;

          case "ServiceCards":
            return (
              <section
                key={block.id}
                id={block.props.sectionId as string || "waswirbieten"}
                className="bg-white overflow-visible pt-[187px] scroll-mt-[0px]"
                style={{ paddingTop: block.props.paddingTop as string || "187px" }}
              >
                <ServiceCards {...block.props} />
              </section>
            );

          case "ProcessSteps":
            return (
              <section
                key={block.id}
                id={block.props.sectionId as string || "service"}
                className="pt-[108px] bg-white"
                style={{ paddingTop: block.props.paddingTop as string || "108px" }}
              >
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                  <div className="text-center mb-[60px]">
                    <h2
                      onClick={() => scrollToSection(block.props.sectionId as string || "service")}
                      className="text-[3rem] font-semibold text-[#1a1a1a] mb-[10px] cursor-pointer hover:opacity-70 transition-opacity"
                      style={{ fontFamily: "'Poppins', sans-serif" }}
                    >
                      {block.props.title as string || "Musik und Technik? Läuft."}
                    </h2>
                    <p
                      className="text-[1.5rem] font-normal text-[#1a1a1a] max-w-[600px] mx-auto mb-2"
                      style={{ fontFamily: "'Poppins', sans-serif" }}
                    >
                      {block.props.subtitle as string || "Von uns geplant. Von euch gefeiert."}
                    </p>
                  </div>
                  <ProcessSteps {...block.props} />
                </div>
              </section>
            );

          case "TeamSection":
            return (
              <section
                key={block.id}
                id={block.props.sectionId as string || "wir"}
                className="pt-[178px] bg-white"
                style={{ paddingTop: block.props.paddingTop as string || "178px" }}
              >
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                  <div
                    className="text-center px-5 overflow-visible"
                    style={{ fontFamily: "'Poppins', sans-serif" }}
                  >
                    <div
                      onClick={() => scrollToSection(block.props.sectionId as string || "wir")}
                      className="cursor-pointer hover:opacity-70 transition-opacity inline-block"
                    >
                      <HamburgAnimation />
                    </div>
                  </div>
                  <TeamSection {...block.props} />
                </div>
              </section>
            );

          case "FAQ":
            return (
              <section
                key={block.id}
                id={block.props.sectionId as string || "faq"}
                className="pt-[134px] bg-white"
                style={{ paddingTop: block.props.paddingTop as string || "134px" }}
              >
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                  <h2
                    onClick={() => scrollToSection(block.props.sectionId as string || "faq")}
                    className="text-center text-[3rem] font-semibold mb-[120px] tracking-[-1px] text-black cursor-pointer hover:opacity-70 transition-opacity"
                    style={{ fontFamily: "'Poppins', sans-serif" }}
                  >
                    {block.props.title as string || "FAQ"}
                  </h2>
                  <FAQ {...block.props} />
                </div>
              </section>
            );

          case "CTASection":
            return (
              <section
                key={block.id}
                className="pt-[190px] pb-[163px] bg-white relative z-[1]"
                style={{
                  paddingTop: block.props.paddingTop as string || "190px",
                  paddingBottom: block.props.paddingBottom as string || "163px",
                }}
              >
                <LogoAnimation />
              </section>
            );

          default:
            return null;
        }
      })}
    </div>
  );
}
