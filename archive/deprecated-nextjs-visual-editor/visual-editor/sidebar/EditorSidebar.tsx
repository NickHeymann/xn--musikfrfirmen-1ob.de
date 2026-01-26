"use client";

import { useState } from "react";
import { Save, Undo2, Redo2, Layout, FileDown } from "lucide-react";
import { useEditor } from "../context/EditorContext";
import { useToast } from "../context/ToastContext";
import { useValidationContext } from "../context/ValidationContext";
import { Spinner } from "../components/Spinner";
import { BlockList } from "./BlockList";
import { HeroEditor } from "./editors/HeroEditor";
import { ServiceCardsEditor } from "./editors/ServiceCardsEditor";
import { ProcessStepsEditor } from "./editors/ProcessStepsEditor";
import { TeamSectionEditor } from "./editors/TeamSectionEditor";
import { FAQEditor } from "./editors/FAQEditor";
import { CTASectionEditor } from "./editors/CTASectionEditor";
import { motion } from "framer-motion";
import { TemplateLibrary } from "../components/TemplateLibrary";
import { SaveTemplateModal } from "../components/SaveTemplateModal";
import { useCustomTemplates } from "../hooks/useCustomTemplates";
import type { BlockTemplate } from "../types/blockTemplate";

type Tab = "blocks" | "properties";

interface EditorSidebarProps {
  activeTab: Tab;
  setActiveTab: (tab: Tab) => void;
}

export function EditorSidebar({ activeTab, setActiveTab }: EditorSidebarProps) {
  const {
    blocks,
    debouncedBlocks,
    selectedBlockId,
    selectBlock,
    hasUnsavedChanges,
    isSaving,
    saveDraft,
    insertTemplate,
    undo,
    redo,
    history,
    historyIndex,
  } = useEditor();

  const [isTemplateLibraryOpen, setIsTemplateLibraryOpen] = useState(false);
  const [isSaveTemplateModalOpen, setIsSaveTemplateModalOpen] = useState(false);

  const { showToast } = useToast();
  const { validateAll } = useValidationContext();
  const { customTemplates, addCustomTemplate, deleteCustomTemplate } = useCustomTemplates();

  const handleSave = async () => {
    // Validate all fields before saving
    const isValid = validateAll();

    if (!isValid) {
      showToast("warning", "Please fix validation errors before saving");
      return;
    }

    try {
      await saveDraft();
      showToast("success", "Changes saved successfully!");
    } catch (error) {
      const message = error instanceof Error ? error.message : "Unknown error";
      showToast("error", `Failed to save: ${message}`);
    }
  };

  const canUndo = historyIndex > 0;
  const canRedo = historyIndex < history.length - 1;

  const handleSelectTemplate = (template: BlockTemplate) => {
    insertTemplate(template);
    setIsTemplateLibraryOpen(false);
  };

  const handleSaveTemplate = (name: string, description: string, category: any) => {
    const newTemplate = addCustomTemplate(name, description, category, blocks);
    showToast("success", `Template "${name}" saved successfully!`);
    setIsSaveTemplateModalOpen(false);
  };

  const handleDeleteTemplate = (templateId: string) => {
    deleteCustomTemplate(templateId);
    showToast("success", "Template deleted");
  };

  // Check if preview is updating (debouncing)
  const isPreviewUpdating =
    JSON.stringify(blocks) !== JSON.stringify(debouncedBlocks);

  return (
    <motion.div
      className="editor-sidebar"
      initial={{ x: 420 }}
      animate={{ x: 0 }}
      exit={{ x: 420 }}
      transition={{ type: "spring", damping: 25 }}
    >
      {/* Header */}
      <div className="sidebar-header">
        <div className="flex items-center gap-2">
          <button
            onClick={undo}
            disabled={!canUndo}
            className="icon-button"
            title="Undo (Cmd+Z)"
          >
            <Undo2 size={16} />
          </button>
          <button
            onClick={redo}
            disabled={!canRedo}
            className="icon-button"
            title="Redo (Cmd+Shift+Z)"
          >
            <Redo2 size={16} />
          </button>
          <div className="h-4 w-px bg-gray-300 mx-1" />
          <button
            onClick={() => setIsTemplateLibraryOpen(true)}
            className="icon-button"
            title="Browse Templates"
          >
            <Layout size={16} />
          </button>
          <button
            onClick={() => setIsSaveTemplateModalOpen(true)}
            className="icon-button"
            title="Save as Template"
            disabled={blocks.length === 0}
          >
            <FileDown size={16} />
          </button>
        </div>

        <div className="save-row">
          {isPreviewUpdating && (
            <span className="preview-updating-hint">Preview updating...</span>
          )}
          {hasUnsavedChanges && !isPreviewUpdating && (
            <span className="text-xs text-neutral-500">Unsaved changes</span>
          )}
          <button
            onClick={handleSave}
            disabled={!hasUnsavedChanges || isSaving}
            className="save-button"
            title="Save changes (Cmd+S)"
          >
            {isSaving ? (
              <>
                <Spinner size="sm" />
                <span>Saving...</span>
              </>
            ) : (
              <>
                <Save size={16} />
                <span>Save</span>
                <kbd className="keyboard-hint">⌘S</kbd>
              </>
            )}
          </button>
        </div>
      </div>

      {/* Tabs */}
      <div className="sidebar-tabs">
        <button
          onClick={() => setActiveTab("blocks")}
          className={`tab ${activeTab === "blocks" ? "active" : ""}`}
        >
          BLOCKS
        </button>
        <button
          onClick={() => setActiveTab("properties")}
          className={`tab ${activeTab === "properties" ? "active" : ""}`}
          disabled={!selectedBlockId}
        >
          PROPERTIES
        </button>
      </div>

      {/* Tab Content */}
      <div className="sidebar-content">
        {activeTab === "blocks" && (
          <BlockList onBlockDoubleClick={() => setActiveTab("properties")} />
        )}
        {activeTab === "properties" && (
          <>
            {selectedBlockId ? (
              <>
                {blocks.find((b) => b.id === selectedBlockId)?.type ===
                  "Hero" && <HeroEditor />}
                {blocks.find((b) => b.id === selectedBlockId)?.type ===
                  "ServiceCards" && <ServiceCardsEditor />}
                {blocks.find((b) => b.id === selectedBlockId)?.type ===
                  "ProcessSteps" && <ProcessStepsEditor />}
                {blocks.find((b) => b.id === selectedBlockId)?.type ===
                  "TeamSection" && <TeamSectionEditor />}
                {blocks.find((b) => b.id === selectedBlockId)?.type ===
                  "FAQ" && <FAQEditor />}
                {blocks.find((b) => b.id === selectedBlockId)?.type ===
                  "CTASection" && <CTASectionEditor />}
              </>
            ) : (
              <div className="no-selection">
                <p>Select a block to edit its properties</p>
              </div>
            )}
          </>
        )}
      </div>

      {/* Template Library Modal */}
      {isTemplateLibraryOpen && (
        <TemplateLibrary
          onSelectTemplate={handleSelectTemplate}
          onClose={() => setIsTemplateLibraryOpen(false)}
          customTemplates={customTemplates}
          onDeleteTemplate={handleDeleteTemplate}
        />
      )}

      {/* Save Template Modal */}
      {isSaveTemplateModalOpen && (
        <SaveTemplateModal
          onSave={handleSaveTemplate}
          onClose={() => setIsSaveTemplateModalOpen(false)}
        />
      )}
    </motion.div>
  );
}
