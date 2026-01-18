'use client'

import { Eye, Edit3 } from 'lucide-react'
import { useEditor } from '../context/EditorContext'

export function ModeToggle() {
  const { mode, setMode, hasUnsavedChanges } = useEditor()

  const handleToggle = () => {
    if (hasUnsavedChanges && mode === 'edit') {
      const confirmed = window.confirm('You have unsaved changes. Continue anyway?')
      if (!confirmed) return
    }
    setMode(mode === 'view' ? 'edit' : 'view')
  }

  return (
    <button
      onClick={handleToggle}
      className="fixed top-4 right-4 z-50 px-4 py-2 rounded-lg bg-white/98 backdrop-blur-lg border border-neutral-200 shadow-lg flex items-center gap-2 hover:bg-neutral-50 transition-colors"
    >
      {mode === 'view' ? (
        <>
          <Edit3 size={16} />
          <span>Edit Mode</span>
        </>
      ) : (
        <>
          <Eye size={16} />
          <span>View Mode</span>
        </>
      )}
    </button>
  )
}
