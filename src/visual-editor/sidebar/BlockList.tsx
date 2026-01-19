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
