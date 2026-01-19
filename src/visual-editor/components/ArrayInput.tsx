'use client'

import { useState } from 'react'
import { X, Plus, GripVertical } from 'lucide-react'
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
  SortableContext,
  sortableKeyboardCoordinates,
  useSortable,
  verticalListSortingStrategy,
  arrayMove,
} from '@dnd-kit/sortable'
import { CSS } from '@dnd-kit/utilities'

interface ArrayInputProps {
  label: string
  items: string[]
  onChange: (items: string[]) => void
  placeholder?: string
  maxItems?: number
}

function SortableItem({
  item,
  index,
  onRemove,
  onChange,
}: {
  item: string
  index: number
  onRemove: () => void
  onChange: (value: string) => void
}) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: `item-${index}` })

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    opacity: isDragging ? 0.5 : 1,
  }

  return (
    <div ref={setNodeRef} style={style} className="array-input-item">
      <div {...attributes} {...listeners} className="array-drag-handle">
        <GripVertical size={16} />
      </div>
      <input
        type="text"
        value={item}
        onChange={(e) => onChange(e.target.value)}
        className="array-input-field"
      />
      <button
        type="button"
        onClick={onRemove}
        className="array-remove-button"
      >
        <X size={16} />
      </button>
    </div>
  )
}

export function ArrayInput({
  label,
  items,
  onChange,
  placeholder = 'Add item',
  maxItems,
}: ArrayInputProps) {
  const [newItemValue, setNewItemValue] = useState('')

  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  )

  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event
    if (!over || active.id === over.id) return

    const oldIndex = parseInt(active.id.toString().replace('item-', ''))
    const newIndex = parseInt(over.id.toString().replace('item-', ''))

    onChange(arrayMove(items, oldIndex, newIndex))
  }

  const handleAdd = () => {
    if (!newItemValue.trim()) return
    if (maxItems && items.length >= maxItems) return

    onChange([...items, newItemValue.trim()])
    setNewItemValue('')
  }

  const handleRemove = (index: number) => {
    onChange(items.filter((_, i) => i !== index))
  }

  const handleUpdate = (index: number, value: string) => {
    const updated = [...items]
    updated[index] = value
    onChange(updated)
  }

  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter') {
      e.preventDefault()
      handleAdd()
    }
  }

  return (
    <div className="array-input-container">
      <label className="array-input-label">{label}</label>

      <DndContext
        sensors={sensors}
        collisionDetection={closestCenter}
        onDragEnd={handleDragEnd}
      >
        <SortableContext
          items={items.map((_, i) => `item-${i}`)}
          strategy={verticalListSortingStrategy}
        >
          {items.map((item, index) => (
            <SortableItem
              key={`item-${index}`}
              item={item}
              index={index}
              onRemove={() => handleRemove(index)}
              onChange={(value) => handleUpdate(index, value)}
            />
          ))}
        </SortableContext>
      </DndContext>

      <div className="array-add-row">
        <input
          type="text"
          value={newItemValue}
          onChange={(e) => setNewItemValue(e.target.value)}
          onKeyDown={handleKeyDown}
          placeholder={placeholder}
          className="array-new-input"
          disabled={maxItems ? items.length >= maxItems : false}
        />
        <button
          type="button"
          onClick={handleAdd}
          disabled={!newItemValue.trim() || (maxItems ? items.length >= maxItems : false)}
          className="array-add-button"
        >
          <Plus size={16} />
          <span>Add</span>
        </button>
      </div>

      {maxItems && (
        <p className="array-hint">
          {items.length} / {maxItems} items
        </p>
      )}
    </div>
  )
}
