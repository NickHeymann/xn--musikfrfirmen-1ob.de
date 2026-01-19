'use client'

import { useEffect } from 'react'
import { useEditor } from '../context/EditorContext'

export function useKeyboardShortcuts() {
  const {
    saveDraft,
    undo,
    redo,
    setMode,
    mode,
    selectBlock,
    hasUnsavedChanges,
    isSaving,
  } = useEditor()

  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0
      const modifier = isMac ? e.metaKey : e.ctrlKey

      // Cmd+S / Ctrl+S: Save
      if (modifier && e.key === 's') {
        e.preventDefault()
        if (hasUnsavedChanges && !isSaving) {
          saveDraft().catch((err) => {
            console.error('Save failed:', err)
            alert('Failed to save changes')
          })
        }
        return
      }

      // Cmd+Z: Undo
      if (modifier && e.key === 'z' && !e.shiftKey) {
        e.preventDefault()
        undo()
        return
      }

      // Cmd+Shift+Z: Redo
      if (modifier && e.key === 'z' && e.shiftKey) {
        e.preventDefault()
        redo()
        return
      }

      // Cmd+E: Toggle edit mode
      if (modifier && e.key === 'e') {
        e.preventDefault()
        if (hasUnsavedChanges && mode === 'edit') {
          const confirmed = window.confirm(
            'You have unsaved changes. Continue anyway?'
          )
          if (!confirmed) return
        }
        setMode(mode === 'view' ? 'edit' : 'view')
        return
      }

      // Esc: Cancel editing / deselect block
      if (e.key === 'Escape') {
        if (mode === 'edit') {
          selectBlock(null)
        }
        return
      }
    }

    window.addEventListener('keydown', handleKeyDown)
    return () => window.removeEventListener('keydown', handleKeyDown)
  }, [
    saveDraft,
    undo,
    redo,
    setMode,
    mode,
    selectBlock,
    hasUnsavedChanges,
    isSaving,
  ])
}
