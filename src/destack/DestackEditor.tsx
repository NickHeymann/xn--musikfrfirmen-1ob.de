"use client";

import { useEffect, useState } from 'react';
import { DragDropContext, Droppable, Draggable, DropResult } from '@hello-pangea/dnd';
import { componentRegistry } from './registry';
import { RootContent, ContentNode } from '@/types';

// POC: Simple content storage in localStorage
const STORAGE_KEY = 'destack_editor_content';

interface DestackEditorProps {
  onSave?: (content: RootContent) => void;
}

export default function DestackEditor({ onSave }: DestackEditorProps) {
  const [content, setContent] = useState<RootContent | null>(null);
  const [loading, setLoading] = useState(true);
  const [isSaving, setIsSaving] = useState(false);

  // Load content on mount
  useEffect(() => {
    const savedContent = localStorage.getItem(STORAGE_KEY);
    if (savedContent) {
      try {
        setContent(JSON.parse(savedContent));
      } catch (e) {
        console.error('Failed to parse saved content:', e);
        setContent(getDefaultContent());
      }
    } else {
      setContent(getDefaultContent());
    }
    setLoading(false);
  }, []);

  const getDefaultContent = (): RootContent => ({
    type: 'root',
    children: [
      {
        type: 'component',
        name: 'ServiceCards',
        id: 'service-cards-1'
      }
    ]
  });

  const handleSave = async () => {
    setIsSaving(true);

    try {
      // Save to localStorage
      localStorage.setItem(STORAGE_KEY, JSON.stringify(content));

      // Call onSave callback if provided
      if (onSave && content) {
        await onSave(content);
      }

      setTimeout(() => {
        setIsSaving(false);
        alert('Content saved successfully!');
      }, 500);
    } catch (error) {
      console.error('Failed to save content:', error);
      setIsSaving(false);
      alert('Failed to save content. Storage may be full.');
    }
  };

  const handleContentChange = (newContent: RootContent) => {
    setContent(newContent);
    // Auto-save after 2 seconds of no changes (debounced)
    // For POC: manual save only
  };

  const handleAddComponent = (componentName: string) => {
    const newComponent: ContentNode = {
      type: 'component',
      name: componentName,
      id: `${componentName}-${crypto.randomUUID()}`,
    };

    setContent((prev) => {
      if (!prev) return prev;
      return {
        ...prev,
        children: [...prev.children, newComponent],
      };
    });
  };

  const handleDeleteComponent = (componentId: string) => {
    setContent((prev) => {
      if (!prev) return prev;
      return {
        ...prev,
        children: prev.children.filter((child) => child.id !== componentId),
      };
    });
  };

  const handleDragEnd = (result: DropResult) => {
    if (!result.destination || !content) return;

    const items = Array.from(content.children);
    const [reorderedItem] = items.splice(result.source.index, 1);
    items.splice(result.destination.index, 0, reorderedItem);

    setContent({
      ...content,
      children: items,
    });
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-96">
        <div className="text-gray-600">Loading editor...</div>
      </div>
    );
  }

  return (
    <div className="destack-editor-container">
      {/* Editor Controls */}
      <div className="flex items-center gap-3 mb-4">
        <button
          onClick={handleSave}
          disabled={isSaving}
          className="px-4 py-2 bg-[#2DD4A8] text-white rounded-md hover:bg-[#22a883] disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {isSaving ? 'Saving...' : 'Save'}
        </button>

        <div className="text-sm text-gray-500">
          {componentRegistry.length} components available
        </div>
      </div>

      {/* Component Palette */}
      <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
        <h3 className="text-sm font-semibold text-gray-700 mb-3">
          Available Components (Click to Add)
        </h3>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
          {componentRegistry.map((item) => (
            <button
              key={item.config.name}
              className="flex flex-col items-center p-3 border border-gray-200 rounded-lg hover:border-[#2DD4A8] hover:bg-[#f0fdf9] transition-colors"
              onClick={() => handleAddComponent(item.config.name)}
            >
              <span className="text-2xl mb-1">{item.config.icon}</span>
              <span className="text-xs text-center text-gray-700">
                {item.config.label}
              </span>
            </button>
          ))}
        </div>
      </div>

      {/* Content Preview with Drag-and-Drop */}
      <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 className="text-sm font-semibold text-gray-700 mb-4">
          Page Preview (Drag to Reorder)
        </h3>

        <DragDropContext onDragEnd={handleDragEnd}>
          <Droppable droppableId="page-content">
            {(provided) => (
              <div
                {...provided.droppableProps}
                ref={provided.innerRef}
                className="space-y-6 min-h-[200px]"
              >
                {content?.children?.map((child: ContentNode, index: number) => {
                  const registryItem = componentRegistry.find(
                    item => item.config.name === child.name
                  );

                  if (!registryItem) {
                    return (
                      <div key={index} className="text-red-500">
                        Component not found: {child.name}
                      </div>
                    );
                  }

                  const Component = registryItem.component;

                  return (
                    <Draggable
                      key={child.id}
                      draggableId={child.id}
                      index={index}
                    >
                      {(provided, snapshot) => (
                        <div
                          ref={provided.innerRef}
                          {...provided.draggableProps}
                          className={`relative group ${
                            snapshot.isDragging ? 'opacity-50' : ''
                          }`}
                        >
                          {/* Drag Handle */}
                          <div
                            {...provided.dragHandleProps}
                            className="absolute -left-8 top-4 opacity-0 group-hover:opacity-100 transition-opacity cursor-move"
                          >
                            <svg
                              className="w-5 h-5 text-gray-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M4 8h16M4 16h16"
                              />
                            </svg>
                          </div>

                          {/* Delete Button */}
                          <button
                            onClick={() => handleDeleteComponent(child.id)}
                            className="absolute -right-8 top-4 opacity-0 group-hover:opacity-100 transition-opacity text-red-500 hover:text-red-700"
                          >
                            <svg
                              className="w-5 h-5"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M6 18L18 6M6 6l12 12"
                              />
                            </svg>
                          </button>

                          {/* Component */}
                          <div className="border border-dashed border-transparent group-hover:border-[#2DD4A8] rounded-lg p-2 -m-2">
                            <Component />
                          </div>
                        </div>
                      )}
                    </Draggable>
                  );
                })}
                {provided.placeholder}

                {(!content?.children || content.children.length === 0) && (
                  <div className="text-center py-12 text-gray-400">
                    Click a component above to add it to the page
                  </div>
                )}
              </div>
            )}
          </Droppable>
        </DragDropContext>
      </div>
    </div>
  );
}
