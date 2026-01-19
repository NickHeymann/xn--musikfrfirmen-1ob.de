'use client'

import { createContext, useContext, useState, useCallback, ReactNode } from 'react'
import { useDebounce } from 'use-debounce'
import type { EditorState, EditorActions, Block } from '../types'

const EditorContext = createContext<(EditorState & EditorActions) | null>(null)

export function EditorProvider({
  children,
  initialBlocks,
  slug
}: {
  children: ReactNode
  initialBlocks: Block[]
  slug: string
}) {
  const [mode, setMode] = useState<'view' | 'edit'>('view')
  const [selectedBlockId, setSelectedBlockId] = useState<string | null>(null)
  const [blocks, setBlocks] = useState<Block[]>(initialBlocks)
  const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false)
  const [isSaving, setIsSaving] = useState(false)
  const [history, setHistory] = useState<Block[][]>([initialBlocks])
  const [historyIndex, setHistoryIndex] = useState(0)

  // Add debounced blocks for preview (300ms delay)
  const [debouncedBlocks] = useDebounce(blocks, 300)

  const addToHistory = useCallback((newBlocks: Block[]) => {
    setHistory(prev => [...prev.slice(0, historyIndex + 1), newBlocks])
    setHistoryIndex(prev => prev + 1)
  }, [historyIndex])

  const updateBlock = useCallback((blockId: string, props: any) => {
    setBlocks(prev => {
      const updated = prev.map(block =>
        block.id === blockId ? { ...block, props: { ...block.props, ...props } } : block
      )
      addToHistory(updated)
      setHasUnsavedChanges(true)
      return updated
    })
  }, [addToHistory])

  const reorderBlocks = useCallback((sourceIndex: number, targetIndex: number) => {
    setBlocks(prev => {
      const updated = [...prev]
      const [removed] = updated.splice(sourceIndex, 1)
      updated.splice(targetIndex, 0, removed)
      addToHistory(updated)
      setHasUnsavedChanges(true)
      return updated
    })
  }, [addToHistory])

  const saveDraft = useCallback(async () => {
    setIsSaving(true)
    try {
      const response = await fetch(`http://localhost:8001/api/pages/${slug}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ content: { blocks } }),
      })
      if (!response.ok) throw new Error('Save failed')
      setHasUnsavedChanges(false)
    } catch (error) {
      console.error('Save error:', error)
      throw error
    } finally {
      setIsSaving(false)
    }
  }, [blocks, slug])

  const undo = useCallback(() => {
    if (historyIndex > 0) {
      setHistoryIndex(prev => prev - 1)
      setBlocks(history[historyIndex - 1])
      setHasUnsavedChanges(true)
    }
  }, [history, historyIndex])

  const redo = useCallback(() => {
    if (historyIndex < history.length - 1) {
      setHistoryIndex(prev => prev + 1)
      setBlocks(history[historyIndex + 1])
      setHasUnsavedChanges(true)
    }
  }, [history, historyIndex])

  return (
    <EditorContext.Provider value={{
      mode,
      selectedBlockId,
      blocks,
      debouncedBlocks,
      hasUnsavedChanges,
      isSaving,
      history,
      historyIndex,
      setMode,
      selectBlock: setSelectedBlockId,
      updateBlock,
      reorderBlocks,
      saveDraft,
      undo,
      redo,
    }}>
      {children}
    </EditorContext.Provider>
  )
}

export function useEditor() {
  const context = useContext(EditorContext)
  if (!context) throw new Error('useEditor must be used within EditorProvider')
  return context
}
