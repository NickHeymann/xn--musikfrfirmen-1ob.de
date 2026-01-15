"use client";

import { useEffect, useState } from 'react';
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
          Available Components
        </h3>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
          {componentRegistry.map((item) => (
            <button
              key={item.config.name}
              className="flex flex-col items-center p-3 border border-gray-200 rounded-lg hover:border-[#2DD4A8] hover:bg-[#f0fdf9] transition-colors"
              onClick={() => {
                // For POC: just log, will implement drag-and-drop in next task
                console.log('Add component:', item.config.name);
              }}
            >
              <span className="text-2xl mb-1">{item.config.icon}</span>
              <span className="text-xs text-center text-gray-700">
                {item.config.label}
              </span>
            </button>
          ))}
        </div>
      </div>

      {/* Content Preview */}
      <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 className="text-sm font-semibold text-gray-700 mb-4">
          Page Preview
        </h3>

        {/* Render components based on content */}
        <div className="space-y-6">
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
            return <Component key={child.id || index} />;
          })}
        </div>
      </div>
    </div>
  );
}
