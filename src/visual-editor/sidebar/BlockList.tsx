"use client";

import { GripVertical } from "lucide-react";
import { useEditor } from "../context/EditorContext";
import type { Block } from "../types";
import {
  DndContext,
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
  DragEndEvent,
} from "@dnd-kit/core";
import {
  SortableContext,
  sortableKeyboardCoordinates,
  useSortable,
  verticalListSortingStrategy,
} from "@dnd-kit/sortable";
import { CSS } from "@dnd-kit/utilities";

interface SortableBlockItemProps {
  block: Block;
  index: number;
  onDoubleClick: () => void;
}

function SortableBlockItem({
  block,
  index,
  onDoubleClick,
}: SortableBlockItemProps) {
  const { selectBlock } = useEditor();
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

  return (
    <div
      ref={setNodeRef}
      style={style}
      className="block-item"
      onClick={() => selectBlock(block.id)}
      onDoubleClick={onDoubleClick}
    >
      <div {...attributes} {...listeners} className="drag-handle-wrapper">
        <GripVertical size={16} className="drag-handle" />
      </div>
      <span className="block-name">{block.type}</span>
    </div>
  );
}

interface BlockListProps {
  onBlockDoubleClick?: () => void;
}

export function BlockList({ onBlockDoubleClick }: BlockListProps) {
  const { blocks, reorderBlocks } = useEditor();

  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    }),
  );

  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event;
    if (!over || active.id === over.id) return;

    const oldIndex = blocks.findIndex((b) => b.id === active.id);
    const newIndex = blocks.findIndex((b) => b.id === over.id);

    reorderBlocks(oldIndex, newIndex);
  };

  const handleDoubleClick = () => {
    if (onBlockDoubleClick) {
      onBlockDoubleClick();
    }
  };

  return (
    <div className="block-list">
      <DndContext
        sensors={sensors}
        collisionDetection={closestCenter}
        onDragEnd={handleDragEnd}
      >
        <SortableContext
          items={blocks.map((b) => b.id)}
          strategy={verticalListSortingStrategy}
        >
          {blocks.map((block, index) => (
            <SortableBlockItem
              key={block.id}
              block={block}
              index={index}
              onDoubleClick={handleDoubleClick}
            />
          ))}
        </SortableContext>
      </DndContext>
    </div>
  );
}
