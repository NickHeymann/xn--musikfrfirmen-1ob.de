"use client";

import { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { useEditor } from "../context/EditorContext";
import { useKeyboardShortcuts } from "../hooks/useKeyboardShortcuts";
import { EditorSidebar } from "../sidebar/EditorSidebar";
import { SkeletonLoader } from "../components/SkeletonLoader";
import Hero from "@/components/Hero";
import ServiceCards from "@/components/ServiceCards";
import ProcessSteps from "@/components/ProcessSteps";
import TeamSection from "@/components/TeamSection";
import FAQ from "@/components/FAQ";
import LogoAnimation from "@/components/LogoAnimation";
import HamburgAnimation from "@/components/HamburgAnimation";

const componentRegistry: Record<string, any> = {
  Hero,
  ServiceCards,
  ProcessSteps,
  TeamSection,
  FAQ,
  CTASection: LogoAnimation,
};

export function EditMode() {
  const { debouncedBlocks, selectBlock, selectedBlockId, isLoading, expandedFAQIndex, setExpandedFAQIndex } =
    useEditor();
  const [hoveredBlockId, setHoveredBlockId] = useState<string | null>(null);
  const [activeTab, setActiveTab] = useState<"blocks" | "properties">("blocks");

  // Add keyboard shortcuts
  useKeyboardShortcuts();

  // Handle double-click to open properties
  const handleBlockDoubleClick = (blockId: string) => {
    selectBlock(blockId);
    setActiveTab("properties");
  };

  // Handle click to open properties (single click on block)
  const handleBlockClick = (blockId: string, event: React.MouseEvent) => {
    // Don't trigger if clicking on interactive elements
    const target = event.target as HTMLElement;
    if (
      target.closest('button') ||
      target.closest('a') ||
      target.closest('input') ||
      target.closest('textarea')
    ) {
      return;
    }

    selectBlock(blockId);
    setActiveTab("properties");
  };

  const scrollToSection = (sectionId: string) => {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: "smooth" });
    }
  };

  const renderBlock = (block: any) => {
    const Component = componentRegistry[block.type];
    if (!Component) return null;

    const isHovered = hoveredBlockId === block.id;
    const isSelected = selectedBlockId === block.id;

    const editableWrapper = (content: React.ReactNode) => (
      <div
        key={block.id}
        data-block-id={block.id}
        className={`editable-block ${isSelected ? "selected" : ""}`}
        onMouseEnter={() => setHoveredBlockId(block.id)}
        onMouseLeave={() => setHoveredBlockId(null)}
        onClick={(e) => handleBlockClick(block.id, e)}
        onDoubleClick={() => handleBlockDoubleClick(block.id)}
      >
        {content}

        <AnimatePresence>
          {isHovered && !isSelected && (
            <motion.button
              className="block-edit-button"
              initial={{ opacity: 0, y: -10 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -10 }}
              transition={{ duration: 0.15 }}
              onClick={(e) => {
                e.stopPropagation();
                handleBlockDoubleClick(block.id);
              }}
            >
              Edit
            </motion.button>
          )}
        </AnimatePresence>
      </div>
    );

    // Match exact section structure from page.tsx (same as ViewMode)
    switch (block.type) {
      case "Hero":
        return editableWrapper(<Hero {...block.props} />);

      case "ServiceCards":
        return editableWrapper(
          <section
            id={block.props.sectionId as string || "waswirbieten"}
            className="bg-white overflow-visible pt-[187px] scroll-mt-[0px]"
            style={{ paddingTop: block.props.paddingTop as string || "187px" }}
          >
            <ServiceCards {...block.props} />
          </section>
        );

      case "ProcessSteps":
        return editableWrapper(
          <section
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
        return editableWrapper(
          <section
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
        return editableWrapper(
          <section
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
              <FAQ
                {...block.props}
                externalActiveIndex={expandedFAQIndex}
                onToggle={(index) => {
                  setExpandedFAQIndex(expandedFAQIndex === index ? null : index);
                  selectBlock(block.id);
                  setActiveTab("properties");
                }}
              />
            </div>
          </section>
        );

      case "CTASection":
        return editableWrapper(
          <section
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
  };

  return (
    <div className="edit-mode-container">
      {/* Preview (70%) */}
      <div className="edit-mode-preview">
        {isLoading ? (
          <div className="skeleton-container" style={{ padding: "40px 20px" }}>
            <SkeletonLoader height={400} rounded animate />
            <div style={{ marginTop: "20px" }}>
              <SkeletonLoader height={300} rounded animate />
            </div>
            <div style={{ marginTop: "20px" }}>
              <SkeletonLoader height={200} rounded animate />
            </div>
          </div>
        ) : (
          <>
            {debouncedBlocks.map((block) => renderBlock(block))}
          </>
        )}
      </div>

      {/* Sidebar (30%) */}
      <EditorSidebar activeTab={activeTab} setActiveTab={setActiveTab} />
    </div>
  );
}
