'use client';

import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { componentRegistry } from '../registry';
import { useEditor } from '../context/EditorContext';
import type { Block } from '@/types/visual-editor';
import { Trash2, GripVertical } from 'lucide-react';

interface SortableBlockProps {
  block: Block;
  isSelected: boolean;
  onSelect: () => void;
}

export function SortableBlock({ block, isSelected, onSelect }: SortableBlockProps) {
  const { deleteBlock } = useEditor();
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: block.id });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    opacity: isDragging ? 0.5 : 1,
  };

  const config = componentRegistry[block.type];
  if (!config) return null;

  const Component = config.component;

  return (
    <div
      ref={setNodeRef}
      style={style}
      className={`
        relative group transition-all duration-200
        ${isSelected ? 'ring-2 ring-blue-500 ring-offset-4' : 'hover:ring-1 hover:ring-gray-300'}
      `}
      onClick={onSelect}
    >
      {/* Drag Handle - Only show when selected */}
      {isSelected && (
        <div className="absolute -left-16 top-4 z-50 flex flex-col gap-2">
          <button
            {...attributes}
            {...listeners}
            className="p-2 bg-white border-2 border-blue-500 rounded-lg hover:bg-blue-50 cursor-grab active:cursor-grabbing shadow-lg"
            title="Drag to reorder"
          >
            <GripVertical className="w-5 h-5 text-blue-600" />
          </button>
          <button
            onClick={(e) => {
              e.stopPropagation();
              if (confirm('Delete this block?')) {
                deleteBlock(block.id);
              }
            }}
            className="p-2 bg-white border-2 border-red-500 rounded-lg hover:bg-red-50 shadow-lg"
            title="Delete block"
          >
            <Trash2 className="w-5 h-5 text-red-600" />
          </button>
        </div>
      )}

      {/* Component renders with full styling */}
      <div className={isDragging ? 'opacity-50' : ''}>
        <Component {...block.props} />
      </div>

      {/* Label when selected */}
      {isSelected && (
        <div className="absolute -top-3 left-4 px-3 py-1 bg-blue-500 text-white text-sm font-medium rounded shadow-lg z-50">
          {config.label}
        </div>
      )}
    </div>
  );
}
