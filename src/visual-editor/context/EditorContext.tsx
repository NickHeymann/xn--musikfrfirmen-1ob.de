"use client";

import {
  createContext,
  useContext,
  useState,
  useCallback,
  ReactNode,
} from "react";
import { useDebounce } from "use-debounce";
import type { EditorState, EditorActions, Block } from "../types";
import type { BlockTemplate } from "../types/blockTemplate";
import { useToast } from "./ToastContext";

const EditorContext = createContext<(EditorState & EditorActions) | null>(null);

/**
 * EditorProvider - Provides editor state and actions to child components
 *
 * Features:
 * - Block management (CRUD operations)
 * - Undo/Redo history
 * - Auto-save with debouncing
 * - View/Edit mode switching
 *
 * @param children - Child components
 * @param initialBlocks - Initial page blocks
 * @param slug - Page slug for API calls
 */
export function EditorProvider({
  children,
  initialBlocks,
  slug,
}: {
  children: ReactNode;
  initialBlocks: Block[];
  slug: string;
}) {
  const { showToast } = useToast();
  const [mode, setMode] = useState<"view" | "edit">("view");
  const [selectedBlockId, setSelectedBlockId] = useState<string | null>(null);
  const [blocks, setBlocks] = useState<Block[]>(initialBlocks);
  const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [history, setHistory] = useState<Block[][]>([initialBlocks]);
  const [historyIndex, setHistoryIndex] = useState(0);

  // FAQ expansion sync state
  const [expandedFAQIndex, setExpandedFAQIndex] = useState<number | null>(null);

  // Add debounced blocks for preview (300ms delay)
  const [debouncedBlocks] = useDebounce(blocks, 300);

  const addToHistory = useCallback(
    (newBlocks: Block[]) => {
      setHistory((prev) => [...prev.slice(0, historyIndex + 1), newBlocks]);
      setHistoryIndex((prev) => prev + 1);
    },
    [historyIndex],
  );

  const updateBlock = useCallback(
    (blockId: string, props: Record<string, unknown>) => {
      setBlocks((prev) => {
        const updated = prev.map((block) =>
          block.id === blockId
            ? { ...block, props: { ...block.props, ...props } }
            : block,
        );
        addToHistory(updated);
        setHasUnsavedChanges(true);
        return updated;
      });
    },
    [addToHistory],
  );

  const reorderBlocks = useCallback(
    (sourceIndex: number, targetIndex: number) => {
      setBlocks((prev) => {
        const updated = [...prev];
        const [removed] = updated.splice(sourceIndex, 1);
        updated.splice(targetIndex, 0, removed);
        addToHistory(updated);
        setHasUnsavedChanges(true);
        return updated;
      });
    },
    [addToHistory],
  );

  /**
   * Insert template blocks into the page
   * @param template - BlockTemplate to insert
   * @param position - Optional position to insert at (default: append to end)
   */
  const insertTemplate = useCallback(
    (template: BlockTemplate, position?: number) => {
      setBlocks((prev) => {
        // Generate unique IDs for template blocks
        const newBlocks = template.blocks.map((block) => ({
          ...block,
          id: `${block.type}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`,
        }));

        let updated: Block[];
        if (position !== undefined && position >= 0 && position <= prev.length) {
          // Insert at specific position
          updated = [...prev.slice(0, position), ...newBlocks, ...prev.slice(position)];
        } else {
          // Append to end
          updated = [...prev, ...newBlocks];
        }

        addToHistory(updated);
        setHasUnsavedChanges(true);
        
        // Show success toast
        showToast(
          "success",
          `Template "${template.name}" inserted with ${template.blocks.length} block${template.blocks.length !== 1 ? 's' : ''}`
        );
        
        return updated;
      });
    },
    [addToHistory, showToast],
  );

  const saveDraft = useCallback(async () => {
    setIsSaving(true);
    try {
      const response = await fetch(`http://localhost:8001/api/pages/${slug}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ content: { blocks } }),
      });
      if (!response.ok) throw new Error("Save failed");
      setHasUnsavedChanges(false);
    } catch (error) {
      console.error("Save error:", error);
      throw error;
    } finally {
      setIsSaving(false);
    }
  }, [blocks, slug]);

  const undo = useCallback(() => {
    if (historyIndex > 0) {
      setHistoryIndex((prev) => prev - 1);
      setBlocks(history[historyIndex - 1]);
      setHasUnsavedChanges(true);
    }
  }, [history, historyIndex]);

  const redo = useCallback(() => {
    if (historyIndex < history.length - 1) {
      setHistoryIndex((prev) => prev + 1);
      setBlocks(history[historyIndex + 1]);
      setHasUnsavedChanges(true);
    }
  }, [history, historyIndex]);

  return (
    <EditorContext.Provider
      value={{
        mode,
        selectedBlockId,
        blocks,
        debouncedBlocks,
        hasUnsavedChanges,
        isSaving,
        isLoading,
        history,
        historyIndex,
        expandedFAQIndex,
        setMode,
        selectBlock: setSelectedBlockId,
        updateBlock,
        reorderBlocks,
        insertTemplate,
        saveDraft,
        undo,
        redo,
        setExpandedFAQIndex,
      }}
    >
      {children}
    </EditorContext.Provider>
  );
}

/**
 * useEditor - Hook to access editor state and actions
 *
 * Must be used within EditorProvider
 *
 * @returns Editor state and actions
 * @throws Error if used outside EditorProvider
 */
export function useEditor() {
  const context = useContext(EditorContext);
  if (!context) throw new Error("useEditor must be used within EditorProvider");
  return context;
}
