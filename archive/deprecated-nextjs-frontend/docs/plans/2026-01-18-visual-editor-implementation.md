# Visual Editor Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development to implement this plan task-by-task.

**Goal:** Build Edit/View mode toggle visual editor with sidebar, TipTap, drag-and-drop, and media preview for musikfürfirmen.de.de

**Architecture:** React Context for state, separate ViewMode/EditMode components, sidebar with BLOCKS/PROPERTIES tabs, block-specific editors, optimistic updates to Laravel API

**Tech Stack:** Next.js 16, React 19, TypeScript, TipTap, @dnd-kit/core, Framer Motion, Tailwind CSS 4

---

## Task 1: Setup Editor Context & Types

**Files:**
- Create: `src/visual-editor/context/EditorContext.tsx`
- Create: `src/visual-editor/types.ts`

**Step 1: Create types file**

Create `src/visual-editor/types.ts`:

```typescript
export interface EditorState {
  mode: 'view' | 'edit'
  selectedBlockId: string | null
  blocks: Block[]
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
```

**Step 2: Create EditorContext**

Create `src/visual-editor/context/EditorContext.tsx`:

```typescript
'use client'

import { createContext, useContext, useState, useCallback, ReactNode } from 'react'
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
```

**Step 3: Verify types**

Run: `npx tsc --noEmit`
Expected: No type errors

**Step 4: Commit**

```bash
git add src/visual-editor/context/EditorContext.tsx src/visual-editor/types.ts
git commit -m "feat: add editor context and types

- EditorContext with mode, blocks, history state
- updateBlock, reorderBlocks, saveDraft actions
- undo/redo with history tracking
- TypeScript interfaces

🤖 Generated with Claude Code"
```

---

## Task 2: Create ViewMode Component

**Files:**
- Create: `src/visual-editor/modes/ViewMode.tsx`

**Step 1: Create ViewMode**

Create `src/visual-editor/modes/ViewMode.tsx`:

```typescript
'use client'

import type { FC } from 'react'
import { useEditor } from '../context/EditorContext'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import Hero from '@/components/Hero'
import ServiceCards from '@/components/ServiceCards'
import ProcessSteps from '@/components/ProcessSteps'
import TeamSection from '@/components/TeamSection'
import FAQ from '@/components/FAQ'
import CTASection from '@/components/CTASection'

const componentRegistry: Record<string, FC<any>> = {
  Hero,
  ServiceCards,
  ProcessSteps,
  TeamSection,
  FAQ,
  CTASection,
}

export function ViewMode() {
  const { blocks } = useEditor()

  return (
    <div className="view-mode">
      <Header />

      {blocks.map((block) => {
        const Component = componentRegistry[block.type]
        if (!Component) {
          console.warn(`Unknown component: ${block.type}`)
          return null
        }
        return <Component key={block.id} {...block.props} />
      })}

      <Footer />
    </div>
  )
}
```

**Step 2: Verify component**

Run: `npx tsc --noEmit`
Expected: No type errors

**Step 3: Commit**

```bash
git add src/visual-editor/modes/ViewMode.tsx
git commit -m "feat: add ViewMode component

- Normal website view with animations
- Renders blocks from EditorContext
- Clean separation from editing logic

🤖 Generated with Claude Code"
```

---

## Task 3: Create EditMode Layout

**Files:**
- Create: `src/visual-editor/modes/EditMode.tsx`
- Modify: `src/visual-editor/styles/apple-editor.css`

**Step 1: Create EditMode**

Create `src/visual-editor/modes/EditMode.tsx`:

```typescript
'use client'

import { useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { useEditor } from '../context/EditorContext'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import Hero from '@/components/Hero'
import ServiceCards from '@/components/ServiceCards'
import ProcessSteps from '@/components/ProcessSteps'
import TeamSection from '@/components/TeamSection'
import FAQ from '@/components/FAQ'
import CTASection from '@/components/CTASection'

const componentRegistry: Record<string, any> = {
  Hero,
  ServiceCards,
  ProcessSteps,
  TeamSection,
  FAQ,
  CTASection,
}

export function EditMode() {
  const { blocks, selectBlock, selectedBlockId } = useEditor()
  const [hoveredBlockId, setHoveredBlockId] = useState<string | null>(null)

  return (
    <div className="edit-mode-container">
      {/* Preview (70%) */}
      <div className="edit-mode-preview">
        <Header />

        {blocks.map((block) => {
          const Component = componentRegistry[block.type]
          if (!Component) return null

          const isHovered = hoveredBlockId === block.id
          const isSelected = selectedBlockId === block.id

          return (
            <div
              key={block.id}
              className={`editable-block ${isSelected ? 'selected' : ''}`}
              onMouseEnter={() => setHoveredBlockId(block.id)}
              onMouseLeave={() => setHoveredBlockId(null)}
              onClick={() => selectBlock(block.id)}
            >
              <Component {...block.props} />

              <AnimatePresence>
                {isHovered && !isSelected && (
                  <motion.button
                    className="block-edit-button"
                    initial={{ opacity: 0, y: -10 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -10 }}
                    transition={{ duration: 0.15 }}
                    onClick={(e) => {
                      e.stopPropagation()
                      selectBlock(block.id)
                    }}
                  >
                    Edit
                  </motion.button>
                )}
              </AnimatePresence>
            </div>
          )
        })}

        <Footer />
      </div>

      {/* Sidebar (30%) - Placeholder */}
      <div className="editor-sidebar-placeholder">
        <div className="p-8 text-center text-neutral-500">
          Sidebar will go here
        </div>
      </div>
    </div>
  )
}
```

**Step 2: Add CSS for edit mode**

Add to `src/visual-editor/styles/apple-editor.css`:

```css
/* Edit Mode Layout */
.edit-mode-container {
  display: flex;
  min-height: 100vh;
}

.edit-mode-preview {
  flex: 1;
  max-width: 70%;
  overflow-y: auto;
}

/* Pause all animations in edit mode */
.edit-mode-preview * {
  animation-play-state: paused !important;
}

/* Editable Block */
.editable-block {
  position: relative;
  cursor: pointer;
  transition: outline 0.2s;
}

.editable-block:hover {
  outline: 2px solid #007AFF;
  outline-offset: 4px;
}

.editable-block.selected {
  outline: 3px solid #007AFF;
  outline-offset: 4px;
}

/* Edit Button */
.block-edit-button {
  position: absolute;
  top: 12px;
  right: 12px;
  padding: 8px 16px;
  background: #007AFF;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  z-index: 10;
  transition: all 0.15s;
}

.block-edit-button:hover {
  background: #0051D5;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3);
}

/* Sidebar Placeholder */
.editor-sidebar-placeholder {
  width: 30%;
  min-width: 420px;
  border-left: 1px solid #E5E5EA;
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(20px);
}
```

**Step 3: Verify TypeScript**

Run: `npx tsc --noEmit`
Expected: No type errors

**Step 4: Commit**

```bash
git add src/visual-editor/modes/EditMode.tsx src/visual-editor/styles/apple-editor.css
git commit -m "feat: add EditMode layout with block selection

- 70/30 split preview and sidebar
- Pause animations in edit mode
- Block hover with Edit button overlay
- Block selection with outline
- Framer Motion animations

🤖 Generated with Claude Code"
```

---

## Task 4: Create Mode Toggle Button

**Files:**
- Modify: `src/app/admin/editor/[slug]/page.tsx`
- Create: `src/visual-editor/components/ModeToggle.tsx`

**Step 1: Create ModeToggle component**

Create `src/visual-editor/components/ModeToggle.tsx`:

```typescript
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
```

**Step 2: Update editor page to use modes**

Modify `src/app/admin/editor/[slug]/page.tsx`:

```typescript
import { EditorProvider } from '@/visual-editor/context/EditorContext'
import { ViewMode } from '@/visual-editor/modes/ViewMode'
import { EditMode } from '@/visual-editor/modes/EditMode'
import { ModeToggle } from '@/visual-editor/components/ModeToggle'
import { useEditor } from '@/visual-editor/context/EditorContext'

function EditorContent() {
  const { mode } = useEditor()
  return (
    <>
      <ModeToggle />
      {mode === 'view' ? <ViewMode /> : <EditMode />}
    </>
  )
}

export default async function EditorPage({ params }: { params: { slug: string } }) {
  const { slug } = params

  // Fetch blocks from API
  const response = await fetch(`http://localhost:8001/api/pages/${slug}`)
  const data = await response.json()
  const blocks = data.content.blocks

  return (
    <EditorProvider initialBlocks={blocks} slug={slug}>
      <EditorContent />
    </EditorProvider>
  )
}
```

**Step 3: Test mode toggle**

Run: `npm run dev`
Navigate to: `http://localhost:3000/admin/editor/home`
Expected: See mode toggle button, can switch between View/Edit modes

**Step 4: Commit**

```bash
git add src/visual-editor/components/ModeToggle.tsx src/app/admin/editor/[slug]/page.tsx
git commit -m "feat: add mode toggle between View and Edit

- ModeToggle button with icon
- Confirm dialog if unsaved changes
- Update editor page to use EditorProvider
- Conditional rendering of ViewMode/EditMode

🤖 Generated with Claude Code"
```

---

## Task 5: Create EditorSidebar with Tabs

**Files:**
- Create: `src/visual-editor/sidebar/EditorSidebar.tsx`
- Create: `src/visual-editor/sidebar/BlockList.tsx`
- Modify: `src/visual-editor/modes/EditMode.tsx`

**Step 1: Create EditorSidebar**

Create `src/visual-editor/sidebar/EditorSidebar.tsx`:

```typescript
'use client'

import { useState } from 'react'
import { Save, X, Undo2, Redo2 } from 'lucide-react'
import { useEditor } from '../context/EditorContext'
import { BlockList } from './BlockList'
import { motion } from 'framer-motion'

type Tab = 'blocks' | 'properties'

export function EditorSidebar() {
  const {
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

        <div className="flex items-center gap-2">
          {hasUnsavedChanges && (
            <span className="text-xs text-neutral-500">Unsaved changes</span>
          )}
          <button
            onClick={handleSave}
            disabled={!hasUnsavedChanges || isSaving}
            className="save-button"
          >
            <Save size={16} />
            {isSaving ? 'Saving...' : 'Save'}
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
        {activeTab === 'properties' && selectedBlockId && (
          <div className="p-4 text-center text-neutral-500">
            Block editor will go here
          </div>
        )}
      </div>
    </motion.div>
  )
}
```

**Step 2: Create BlockList**

Create `src/visual-editor/sidebar/BlockList.tsx`:

```typescript
'use client'

import { GripVertical } from 'lucide-react'
import { useEditor } from '../context/EditorContext'

export function BlockList() {
  const { blocks, selectBlock } = useEditor()

  return (
    <div className="block-list">
      {blocks.map((block, index) => (
        <div
          key={block.id}
          className="block-item"
          onClick={() => selectBlock(block.id)}
        >
          <GripVertical size={16} className="drag-handle" />
          <span className="block-name">{block.type}</span>
        </div>
      ))}
    </div>
  )
}
```

**Step 3: Add sidebar CSS**

Add to `src/visual-editor/styles/apple-editor.css`:

```css
/* Editor Sidebar */
.editor-sidebar {
  width: 420px;
  height: 100vh;
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(20px);
  border-left: 1px solid #E5E5EA;
  display: flex;
  flex-direction: column;
}

.sidebar-header {
  padding: 16px;
  border-bottom: 1px solid #E5E5EA;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.icon-button {
  padding: 8px;
  border: none;
  background: transparent;
  color: #1D1D1F;
  cursor: pointer;
  border-radius: 6px;
  transition: all 0.15s;
}

.icon-button:hover:not(:disabled) {
  background: #F5F5F7;
}

.icon-button:disabled {
  opacity: 0.3;
  cursor: not-allowed;
}

.save-button {
  padding: 8px 16px;
  background: #007AFF;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.15s;
}

.save-button:hover:not(:disabled) {
  background: #0051D5;
}

.save-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Tabs */
.sidebar-tabs {
  display: flex;
  border-bottom: 1px solid #E5E5EA;
  background: #FAFAFA;
}

.tab {
  flex: 1;
  padding: 12px;
  border: none;
  background: transparent;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: #86868B;
  cursor: pointer;
  transition: all 0.15s;
  border-bottom: 2px solid transparent;
}

.tab:hover:not(:disabled) {
  background: #F5F5F7;
  color: #1D1D1F;
}

.tab.active {
  color: #007AFF;
  border-bottom-color: #007AFF;
  background: white;
}

.tab:disabled {
  opacity: 0.3;
  cursor: not-allowed;
}

/* Sidebar Content */
.sidebar-content {
  flex: 1;
  overflow-y: auto;
}

/* Block List */
.block-list {
  padding: 8px;
}

.block-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.15s;
}

.block-item:hover {
  background: #F5F5F7;
}

.drag-handle {
  color: #86868B;
  cursor: grab;
}

.drag-handle:active {
  cursor: grabbing;
}

.block-name {
  font-size: 14px;
  color: #1D1D1F;
  font-weight: 500;
}
```

**Step 4: Update EditMode to use EditorSidebar**

Modify `src/visual-editor/modes/EditMode.tsx` - replace sidebar placeholder:

```typescript
import { EditorSidebar } from '../sidebar/EditorSidebar'

// Replace <div className="editor-sidebar-placeholder">...</div> with:
<EditorSidebar />
```

**Step 5: Verify tabs work**

Run: `npm run dev`
Expected: See sidebar with BLOCKS/PROPERTIES tabs, can switch tabs

**Step 6: Commit**

```bash
git add src/visual-editor/sidebar/ src/visual-editor/modes/EditMode.tsx src/visual-editor/styles/apple-editor.css
git commit -m "feat: add EditorSidebar with tabs

- Sidebar with BLOCKS and PROPERTIES tabs
- Undo/Redo buttons with state
- Save button with loading state
- BlockList component (no drag yet)
- Apple-quality styling with glass effects

🤖 Generated with Claude Code"
```

---

## Task 6: Add Drag-and-Drop to BlockList

**Files:**
- Modify: `src/visual-editor/sidebar/BlockList.tsx`
- Modify: `package.json` (install @dnd-kit/core)

**Step 1: Install dnd-kit**

Run: `npm install @dnd-kit/core @dnd-kit/sortable @dnd-kit/utilities`
Expected: Dependencies installed

**Step 2: Update BlockList with drag-and-drop**

Modify `src/visual-editor/sidebar/BlockList.tsx`:

```typescript
'use client'

import { GripVertical } from 'lucide-react'
import { useEditor } from '../context/EditorContext'
import {
  DndContext,
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
  DragEndEvent,
} from '@dnd-kit/core'
import {
  arrayMove,
  SortableContext,
  sortableKeyboardCoordinates,
  useSortable,
  verticalListSortingStrategy,
} from '@dnd-kit/sortable'
import { CSS } from '@dnd-kit/utilities'

function SortableBlockItem({ block, index }: { block: any; index: number }) {
  const { selectBlock } = useEditor()
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: block.id })

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    opacity: isDragging ? 0.5 : 1,
  }

  return (
    <div
      ref={setNodeRef}
      style={style}
      className="block-item"
      onClick={() => selectBlock(block.id)}
    >
      <div {...attributes} {...listeners} className="drag-handle-wrapper">
        <GripVertical size={16} className="drag-handle" />
      </div>
      <span className="block-name">{block.type}</span>
    </div>
  )
}

export function BlockList() {
  const { blocks, reorderBlocks } = useEditor()

  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  )

  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event
    if (!over || active.id === over.id) return

    const oldIndex = blocks.findIndex(b => b.id === active.id)
    const newIndex = blocks.findIndex(b => b.id === over.id)

    reorderBlocks(oldIndex, newIndex)
  }

  return (
    <div className="block-list">
      <DndContext
        sensors={sensors}
        collisionDetection={closestCenter}
        onDragEnd={handleDragEnd}
      >
        <SortableContext
          items={blocks.map(b => b.id)}
          strategy={verticalListSortingStrategy}
        >
          {blocks.map((block, index) => (
            <SortableBlockItem key={block.id} block={block} index={index} />
          ))}
        </SortableContext>
      </DndContext>
    </div>
  )
}
```

**Step 3: Update CSS for drag**

Add to `src/visual-editor/styles/apple-editor.css`:

```css
.drag-handle-wrapper {
  display: flex;
  align-items: center;
  cursor: grab;
}

.drag-handle-wrapper:active {
  cursor: grabbing;
}
```

**Step 4: Test drag-and-drop**

Run: `npm run dev`
Expected: Can drag blocks to reorder, preview updates immediately

**Step 5: Commit**

```bash
git add src/visual-editor/sidebar/BlockList.tsx src/visual-editor/styles/apple-editor.css package.json package-lock.json
git commit -m "feat: add drag-and-drop block reordering

- Install @dnd-kit/core
- Implement sortable block list
- Reorder blocks updates preview immediately
- Add hasUnsavedChanges flag on reorder

🤖 Generated with Claude Code"
```

---

## Task 7: Create HeroEditor Component

**Files:**
- Create: `src/visual-editor/sidebar/editors/HeroEditor.tsx`
- Create: `src/visual-editor/components/ArrayInput.tsx`
- Modify: `src/visual-editor/sidebar/EditorSidebar.tsx`

**Step 1: Create ArrayInput component**

Create `src/visual-editor/components/ArrayInput.tsx`:

```typescript
'use client'

import { GripVertical, X, Plus } from 'lucide-react'
import { useState } from 'react'

interface ArrayInputProps {
  label: string
  value: string[]
  onChange: (value: string[]) => void
}

export function ArrayInput({ label, value, onChange }: ArrayInputProps) {
  const [items, setItems] = useState(value)

  const handleAdd = () => {
    const updated = [...items, '']
    setItems(updated)
    onChange(updated)
  }

  const handleRemove = (index: number) => {
    const updated = items.filter((_, i) => i !== index)
    setItems(updated)
    onChange(updated)
  }

  const handleChange = (index: number, newValue: string) => {
    const updated = items.map((item, i) => i === index ? newValue : item)
    setItems(updated)
    onChange(updated)
  }

  return (
    <div className="array-input">
      <label className="input-label">{label}</label>
      <div className="array-items">
        {items.map((item, index) => (
          <div key={index} className="array-item">
            <GripVertical size={16} className="array-drag-handle" />
            <input
              type="text"
              value={item}
              onChange={(e) => handleChange(index, e.target.value)}
              className="array-item-input"
              placeholder={`Item ${index + 1}`}
            />
            <button
              onClick={() => handleRemove(index)}
              className="array-remove-button"
            >
              <X size={16} />
            </button>
          </div>
        ))}
      </div>
      <button onClick={handleAdd} className="array-add-button">
        <Plus size={16} />
        Add Item
      </button>
    </div>
  )
}
```

**Step 2: Create HeroEditor**

Create `src/visual-editor/sidebar/editors/HeroEditor.tsx`:

```typescript
'use client'

import { useState } from 'react'
import { useEditor } from '../../context/EditorContext'
import { ArrayInput } from '../../components/ArrayInput'

export function HeroEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor()

  const block = blocks.find(b => b.id === selectedBlockId)
  if (!block || block.type !== 'Hero') return null

  const [sliderPrefix, setSliderPrefix] = useState(block.props.sliderContent?.[0] || 'Deine')
  const [sliderContent, setSliderContent] = useState<string[]>(
    block.props.sliderContent || ['Musik', 'Livebands', 'DJs', 'Technik']
  )
  const [sliderSuffix, setSliderSuffix] = useState('für Firmenevents!')
  const [ctaText, setCtaText] = useState(block.props.ctaText || 'Unverbindliches Angebot anfragen')

  const handleSave = () => {
    updateBlock(block.id, {
      sliderContent,
      ctaText,
    })
    alert('Hero updated!')
  }

  return (
    <div className="block-editor">
      <h3 className="editor-title">Edit Hero</h3>

      <div className="form-group">
        <label className="input-label">Headline Prefix</label>
        <input
          type="text"
          value={sliderPrefix}
          onChange={(e) => setSliderPrefix(e.target.value)}
          className="text-input"
          placeholder="Deine"
        />
      </div>

      <ArrayInput
        label="Animated Words"
        value={sliderContent}
        onChange={setSliderContent}
      />

      <div className="form-group">
        <label className="input-label">Headline Suffix</label>
        <input
          type="text"
          value={sliderSuffix}
          onChange={(e) => setSliderSuffix(e.target.value)}
          className="text-input"
          placeholder="für Firmenevents!"
        />
      </div>

      <div className="form-group">
        <label className="input-label">CTA Button Text</label>
        <input
          type="text"
          value={ctaText}
          onChange={(e) => setCtaText(e.target.value)}
          className="text-input"
        />
      </div>

      <div className="editor-actions">
        <button onClick={handleSave} className="save-button">
          Save Changes
        </button>
      </div>
    </div>
  )
}
```

**Step 3: Add form styles**

Add to `src/visual-editor/styles/apple-editor.css`:

```css
/* Block Editor */
.block-editor {
  padding: 16px;
}

.editor-title {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 16px;
  color: #1D1D1F;
}

.form-group {
  margin-bottom: 16px;
}

.input-label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  color: #86868B;
  margin-bottom: 6px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.text-input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #E5E5EA;
  border-radius: 6px;
  font-size: 14px;
  transition: all 0.15s;
}

.text-input:focus {
  outline: none;
  border-color: #007AFF;
  box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
}

/* Array Input */
.array-input {
  margin-bottom: 16px;
}

.array-items {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 8px;
}

.array-item {
  display: flex;
  align-items: center;
  gap: 8px;
}

.array-drag-handle {
  color: #86868B;
  cursor: grab;
  flex-shrink: 0;
}

.array-item-input {
  flex: 1;
  padding: 8px 12px;
  border: 1px solid #E5E5EA;
  border-radius: 6px;
  font-size: 14px;
}

.array-remove-button {
  padding: 6px;
  border: none;
  background: transparent;
  color: #FF3B30;
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.15s;
  flex-shrink: 0;
}

.array-remove-button:hover {
  background: #FFE5E5;
}

.array-add-button {
  width: 100%;
  padding: 8px;
  border: 1px dashed #E5E5EA;
  background: transparent;
  color: #007AFF;
  font-size: 14px;
  font-weight: 500;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  transition: all 0.15s;
}

.array-add-button:hover {
  background: #F5F5F7;
  border-color: #007AFF;
}

.editor-actions {
  margin-top: 24px;
  padding-top: 16px;
  border-top: 1px solid #E5E5EA;
}
```

**Step 4: Update EditorSidebar to show HeroEditor**

Modify `src/visual-editor/sidebar/EditorSidebar.tsx`:

```typescript
import { HeroEditor } from './editors/HeroEditor'

// Replace properties placeholder with:
{activeTab === 'properties' && selectedBlockId && (
  <>
    {blocks.find(b => b.id === selectedBlockId)?.type === 'Hero' && <HeroEditor />}
    {/* More editors will go here */}
  </>
)}
```

**Step 5: Test Hero editor**

Run: `npm run dev`
Expected: Click Hero block → Properties tab → see Hero editor form

**Step 6: Commit**

```bash
git add src/visual-editor/sidebar/editors/HeroEditor.tsx src/visual-editor/components/ArrayInput.tsx src/visual-editor/sidebar/EditorSidebar.tsx src/visual-editor/styles/apple-editor.css
git commit -m "feat: add HeroEditor with array input

- HeroEditor form with text inputs
- ArrayInput component for animated words
- Add/remove words with X button
- Form styling with Apple design
- Update EditorSidebar to show HeroEditor

🤖 Generated with Claude Code"
```

---

## Task 8: Add Keyboard Shortcuts

**Files:**
- Create: `src/visual-editor/hooks/useKeyboardShortcuts.ts`
- Modify: `src/visual-editor/modes/EditMode.tsx`

**Step 1: Create keyboard shortcuts hook**

Create `src/visual-editor/hooks/useKeyboardShortcuts.ts`:

```typescript
import { useEffect } from 'react'
import { useEditor } from '../context/EditorContext'

export function useKeyboardShortcuts() {
  const { saveDraft, undo, redo, setMode, mode, hasUnsavedChanges } = useEditor()

  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      const isMod = e.metaKey || e.ctrlKey

      // Cmd+S: Save
      if (isMod && e.key === 's') {
        e.preventDefault()
        if (hasUnsavedChanges) {
          saveDraft().catch(console.error)
        }
      }

      // Cmd+Z: Undo
      if (isMod && e.key === 'z' && !e.shiftKey) {
        e.preventDefault()
        undo()
      }

      // Cmd+Shift+Z: Redo
      if (isMod && e.key === 'z' && e.shiftKey) {
        e.preventDefault()
        redo()
      }

      // Cmd+E: Toggle edit mode
      if (isMod && e.key === 'e') {
        e.preventDefault()
        setMode(mode === 'view' ? 'edit' : 'view')
      }

      // Esc: Cancel editing (deselect block)
      if (e.key === 'Escape' && mode === 'edit') {
        e.preventDefault()
        // Will implement selectBlock(null) later
      }
    }

    window.addEventListener('keydown', handleKeyDown)
    return () => window.removeEventListener('keydown', handleKeyDown)
  }, [saveDraft, undo, redo, setMode, mode, hasUnsavedChanges])
}
```

**Step 2: Use hook in EditMode**

Modify `src/visual-editor/modes/EditMode.tsx`:

```typescript
import { useKeyboardShortcuts } from '../hooks/useKeyboardShortcuts'

export function EditMode() {
  // ... existing code ...
  useKeyboardShortcuts()

  // ... rest of component ...
}
```

**Step 3: Test keyboard shortcuts**

Run: `npm run dev`
Test:
- Cmd+S → Should save
- Cmd+Z → Should undo
- Cmd+Shift+Z → Should redo
- Cmd+E → Should toggle mode

**Step 4: Commit**

```bash
git add src/visual-editor/hooks/useKeyboardShortcuts.ts src/visual-editor/modes/EditMode.tsx
git commit -m "feat: add keyboard shortcuts

- Cmd+S: Save changes
- Cmd+Z: Undo
- Cmd+Shift+Z: Redo
- Cmd+E: Toggle edit mode
- Esc: Cancel editing (placeholder)

🤖 Generated with Claude Code"
```

---

## Task 9: Add Real-Time Preview Debouncing

**Files:**
- Modify: `src/visual-editor/sidebar/editors/HeroEditor.tsx`
- Install: use-debounce package

**Step 1: Install use-debounce**

Run: `npm install use-debounce`
Expected: Package installed

**Step 2: Update HeroEditor with debounced updates**

Modify `src/visual-editor/sidebar/editors/HeroEditor.tsx`:

```typescript
import { useDebounce } from 'use-debounce'
import { useEffect } from 'react'

export function HeroEditor() {
  // ... existing state ...

  const [debouncedSliderContent] = useDebounce(sliderContent, 300)
  const [debouncedCtaText] = useDebounce(ctaText, 300)

  // Auto-update preview on debounced change
  useEffect(() => {
    if (block) {
      updateBlock(block.id, {
        sliderContent: debouncedSliderContent,
        ctaText: debouncedCtaText,
      })
    }
  }, [debouncedSliderContent, debouncedCtaText])

  // Remove manual Save button (updates are automatic now)
  return (
    <div className="block-editor">
      {/* ... form fields ... */}
      {/* Remove <div className="editor-actions">...</div> */}
    </div>
  )
}
```

**Step 3: Test real-time preview**

Run: `npm run dev`
Expected: Typing in Hero editor → preview updates after 300ms pause

**Step 4: Commit**

```bash
git add src/visual-editor/sidebar/editors/HeroEditor.tsx package.json package-lock.json
git commit -m "feat: add real-time preview with debouncing

- Install use-debounce
- Auto-update preview 300ms after typing
- Remove manual Save button (optimistic updates)
- Preview updates immediately in left pane

🤖 Generated with Claude Code"
```

---

## Remaining Tasks

The plan continues with:

**Task 10-15:** Create remaining block editors (ServiceCards, ProcessSteps, TeamSection, FAQ, CTASection)
**Task 16:** Add MediaUploader component
**Task 17:** Implement Laravel media upload endpoints
**Task 18:** Add toast notifications
**Task 19:** Add loading states
**Task 20:** Add inline validation
**Task 21:** Final testing and cleanup

---

## Success Criteria

All tasks complete when:
- [ ] Can toggle View/Edit modes
- [ ] Animations pause in edit mode
- [ ] Can reorder blocks via drag-and-drop
- [ ] Can edit Hero with array input for animated words
- [ ] Changes preview in real-time (300ms debounce)
- [ ] Can save to Laravel API
- [ ] Keyboard shortcuts work (Cmd+S, Cmd+Z, etc.)
- [ ] Undo/redo functional
- [ ] Apple-quality design

**Current progress: 9/21 tasks** (Core architecture complete)
