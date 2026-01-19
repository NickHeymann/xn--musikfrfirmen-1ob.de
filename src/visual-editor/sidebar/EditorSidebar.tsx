'use client'

import { useState } from 'react'
import { Save, Undo2, Redo2, Loader2 } from 'lucide-react'
import { useEditor } from '../context/EditorContext'
import { BlockList } from './BlockList'
import { HeroEditor } from './editors/HeroEditor'
import { ServiceCardsEditor } from './editors/ServiceCardsEditor'
import { motion } from 'framer-motion'

type Tab = 'blocks' | 'properties'

export function EditorSidebar() {
  const {
    blocks,
    debouncedBlocks,
    selectedBlockId,
    selectBlock,
    hasUnsavedChanges,
    isSaving,
    saveDraft,
    undo,
    redo,
    history,
    historyIndex
  } = useEditor()

  const [activeTab, setActiveTab] = useState<Tab>('blocks')

  const handleSave = async () => {
    try {
      await saveDraft()
      alert('Changes saved!')
    } catch (error) {
      alert('Failed to save changes')
    }
  }

  const canUndo = historyIndex > 0
  const canRedo = historyIndex < history.length - 1

  // Check if preview is updating (debouncing)
  const isPreviewUpdating = JSON.stringify(blocks) !== JSON.stringify(debouncedBlocks)

  return (
    <motion.div
      className="editor-sidebar"
      initial={{ x: 420 }}
      animate={{ x: 0 }}
      exit={{ x: 420 }}
      transition={{ type: 'spring', damping: 25 }}
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
        </div>

        <div className="save-row">
          {isPreviewUpdating && (
            <span className="preview-updating-hint">
              Preview updating...
            </span>
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
                <Loader2 size={16} className="spinning" />
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
          onClick={() => setActiveTab('blocks')}
          className={`tab ${activeTab === 'blocks' ? 'active' : ''}`}
        >
          BLOCKS
        </button>
        <button
          onClick={() => setActiveTab('properties')}
          className={`tab ${activeTab === 'properties' ? 'active' : ''}`}
          disabled={!selectedBlockId}
        >
          PROPERTIES
        </button>
      </div>

      {/* Tab Content */}
      <div className="sidebar-content">
        {activeTab === 'blocks' && <BlockList />}
        {activeTab === 'properties' && (
          <>
            {selectedBlockId ? (
              <>
                {blocks.find(b => b.id === selectedBlockId)?.type === 'Hero' && (
                  <HeroEditor />
                )}
                {blocks.find(b => b.id === selectedBlockId)?.type === 'ServiceCards' && (
                  <ServiceCardsEditor />
                )}
                {/* Future: Add other block editors */}
              </>
            ) : (
              <div className="no-selection">
                <p>Select a block to edit its properties</p>
              </div>
            )}
          </>
        )}
      </div>
    </motion.div>
  )
}
