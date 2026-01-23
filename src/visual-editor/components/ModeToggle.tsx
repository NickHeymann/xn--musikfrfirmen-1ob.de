"use client";

import { Eye, Edit3 } from "lucide-react";
import { useEditor } from "../context/EditorContext";
import { PageNavigation } from "./PageNavigation";
import { useParams } from "next/navigation";

export function ModeToggle() {
  const { mode, setMode, hasUnsavedChanges } = useEditor();
  const params = useParams();
  const slug = params.slug as string;

  const handleToggle = () => {
    if (hasUnsavedChanges && mode === "edit") {
      const confirmed = window.confirm(
        "You have unsaved changes. Continue anyway?",
      );
      if (!confirmed) return;
    }
    setMode(mode === "view" ? "edit" : "view");
  };

  return (
    <>
      {/* Page Navigation - Top Left */}
      <div className="fixed top-4 left-4 z-50">
        <PageNavigation currentSlug={slug} />
      </div>

      {/* Mode Toggle - Top Right */}
      <button
        onClick={handleToggle}
        className="fixed top-4 right-4 z-50 px-4 py-2 rounded-lg bg-white/98 backdrop-blur-lg border border-neutral-200 shadow-lg flex items-center gap-2 hover:bg-neutral-50 transition-colors"
        title={
          mode === "view"
            ? "Switch to Edit Mode (Cmd+E)"
            : "Switch to View Mode (Cmd+E)"
        }
      >
        {mode === "view" ? (
          <>
            <Edit3 size={16} />
            <span>Edit Mode</span>
            <kbd className="mode-toggle-hint">⌘E</kbd>
          </>
        ) : (
          <>
            <Eye size={16} />
            <span>View Mode</span>
            <kbd className="mode-toggle-hint">⌘E</kbd>
          </>
        )}
      </button>
    </>
  );
}
