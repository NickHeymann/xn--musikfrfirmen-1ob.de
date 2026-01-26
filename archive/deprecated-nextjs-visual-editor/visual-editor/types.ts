export interface EditorState {
  mode: "view" | "edit";
  selectedBlockId: string | null;
  blocks: Block[];
  debouncedBlocks: Block[];
  hasUnsavedChanges: boolean;
  isSaving: boolean;
  isLoading: boolean;
  history: Block[][];
  historyIndex: number;
  expandedFAQIndex: number | null;
}

export interface EditorActions {
  setMode: (mode: "view" | "edit") => void;
  selectBlock: (blockId: string | null) => void;
  updateBlock: (blockId: string, props: Record<string, unknown>) => void;
  reorderBlocks: (sourceIndex: number, targetIndex: number) => void;
  insertTemplate: (template: import('./types/blockTemplate').BlockTemplate, position?: number) => void;
  saveDraft: () => Promise<void>;
  undo: () => void;
  redo: () => void;
  setExpandedFAQIndex: (index: number | null) => void;
}

export interface Block {
  id: string;
  type: string;
  props: Record<string, unknown>;
}
