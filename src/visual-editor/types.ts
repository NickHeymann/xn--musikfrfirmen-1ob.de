export interface EditorState {
  mode: 'view' | 'edit'
  selectedBlockId: string | null
  blocks: Block[]
  debouncedBlocks: Block[]
  hasUnsavedChanges: boolean
  isSaving: boolean
  history: Block[][]
  historyIndex: number
}

export interface EditorActions {
  setMode: (mode: 'view' | 'edit') => void
  selectBlock: (blockId: string | null) => void
  updateBlock: (blockId: string, props: any) => void
  reorderBlocks: (sourceIndex: number, targetIndex: number) => void
  saveDraft: () => Promise<void>
  undo: () => void
  redo: () => void
}

export interface Block {
  id: string
  type: string
  props: Record<string, any>
}
