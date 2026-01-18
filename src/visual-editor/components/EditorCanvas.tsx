'use client';

import { useEditor } from '../context/EditorContext';
import {
  DndContext,
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
  DragEndEvent,
} from '@dnd-kit/core';
import {
  SortableContext,
  sortableKeyboardCoordinates,
  verticalListSortingStrategy,
} from '@dnd-kit/sortable';
import { SortableBlock } from './SortableBlock';

export function EditorCanvas() {
  const { blocks, moveBlock, selectBlock, selectedBlockId } = useEditor();

  // Configure dnd-kit sensors for optimal performance
  const sensors = useSensors(
    useSensor(PointerSensor, {
      activationConstraint: {
        distance: 8, // Prevent accidental drags
      },
    }),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  );

  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event;

    if (over && active.id !== over.id) {
      const oldIndex = blocks.findIndex((block) => block.id === active.id);
      const newIndex = blocks.findIndex((block) => block.id === over.id);

      moveBlock(oldIndex, newIndex);
    }
  };

  return (
    <div className="min-h-screen bg-white">
      <DndContext
        sensors={sensors}
        collisionDetection={closestCenter}
        onDragEnd={handleDragEnd}
      >
        <SortableContext
          items={blocks.map((b) => b.id)}
          strategy={verticalListSortingStrategy}
        >
          {blocks.length === 0 ? (
            <EmptyState />
          ) : (
            <>
              {blocks.map((block) => (
                <SortableBlock
                  key={block.id}
                  block={block}
                  isSelected={selectedBlockId === block.id}
                  onSelect={() => selectBlock(block.id)}
                />
              ))}
            </>
          )}
        </SortableContext>
      </DndContext>
    </div>
  );
}

function EmptyState() {
  return (
    <div className="flex flex-col items-center justify-center h-96 text-center">
      <h3 className="text-xl font-semibold text-gray-900 mb-2">
        Start building your page
      </h3>
      <p className="text-gray-500 max-w-sm">
        Drag components from the left palette to get started
      </p>
    </div>
  );
}
