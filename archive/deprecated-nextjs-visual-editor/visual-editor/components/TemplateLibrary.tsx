'use client';

/**
 * Template Library Component
 * 
 * UI for browsing, searching, and selecting block templates.
 * Displays templates in a responsive grid with category filtering.
 */

import { useState, useMemo } from 'react';
import type { BlockTemplate, TemplateCategory } from '../types/blockTemplate';
import { BLOCK_TEMPLATES, searchTemplates, getTemplatesByCategory } from '../data/blockTemplates';
import { TemplatePreviewModal } from './TemplatePreviewModal';

interface TemplateLibraryProps {
  onSelectTemplate: (template: BlockTemplate) => void;
  onClose: () => void;
  customTemplates?: BlockTemplate[];
  onDeleteTemplate?: (templateId: string) => void;
}

const CATEGORIES: { value: TemplateCategory | 'all'; label: string }[] = [
  { value: 'all', label: 'All Templates' },
  { value: 'hero', label: 'Hero Sections' },
  { value: 'features', label: 'Features' },
  { value: 'testimonials', label: 'Testimonials' },
  { value: 'cta', label: 'Call-to-Action' },
  { value: 'custom', label: 'Custom' },
];

export function TemplateLibrary({ 
  onSelectTemplate, 
  onClose,
  customTemplates = [],
  onDeleteTemplate,
}: TemplateLibraryProps) {
  const [selectedCategory, setSelectedCategory] = useState<TemplateCategory | 'all'>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [previewTemplate, setPreviewTemplate] = useState<BlockTemplate | null>(null);

  // Filter templates based on category and search
  const filteredTemplates = useMemo(() => {
    // Combine system and custom templates
    let templates = [...BLOCK_TEMPLATES, ...customTemplates];

    // Filter by category
    if (selectedCategory !== 'all') {
      templates = getTemplatesByCategory(selectedCategory);
    }

    // Filter by search query
    if (searchQuery.trim()) {
      templates = searchTemplates(searchQuery).filter((template) =>
        selectedCategory === 'all' || template.category === selectedCategory
      );
    }

    return templates;
  }, [selectedCategory, searchQuery, customTemplates]);

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50" onClick={onClose}>
      <div
        className="bg-white rounded-lg shadow-xl max-w-5xl w-full max-h-[90vh] overflow-hidden flex flex-col"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Header */}
        <div className="border-b border-gray-200 p-6">
          <div className="flex items-center justify-between mb-4">
            <h2 className="text-2xl font-bold text-gray-900">Template Library</h2>
            <button
              onClick={onClose}
              className="text-gray-400 hover:text-gray-600 transition-colors"
              aria-label="Close template library"
            >
              <svg
                className="w-6 h-6"
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
          </div>

          {/* Search */}
          <div className="mb-4">
            <input
              type="text"
              placeholder="Search templates..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          {/* Category Tabs */}
          <div className="flex gap-2 overflow-x-auto pb-2">
            {CATEGORIES.map((category) => (
              <button
                key={category.value}
                onClick={() => setSelectedCategory(category.value)}
                className={`px-4 py-2 rounded-lg whitespace-nowrap transition-colors ${
                  selectedCategory === category.value
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                }`}
              >
                {category.label}
              </button>
            ))}
          </div>
        </div>

        {/* Template Grid */}
        <div className="flex-1 overflow-y-auto p-6">
          {filteredTemplates.length === 0 ? (
            <div className="text-center py-12">
              <p className="text-gray-500 text-lg">No templates found</p>
              {searchQuery && (
                <p className="text-gray-400 text-sm mt-2">
                  Try adjusting your search or filter
                </p>
              )}
            </div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {filteredTemplates.map((template) => (
                <button
                  key={template.id}
                  onClick={() => setPreviewTemplate(template)}
                  className="group bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-blue-500 hover:shadow-lg transition-all text-left"
                >
                  {/* Preview Image */}
                  <div className="aspect-video bg-gray-100 relative overflow-hidden">
                    {template.preview ? (
                      <img
                        src={template.preview}
                        alt={template.name}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform"
                        onError={(e) => {
                          // Fallback if image doesn't exist
                          e.currentTarget.style.display = 'none';
                        }}
                      />
                    ) : null}
                    <div className="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                      <svg
                        className="w-16 h-16 opacity-50"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                        />
                      </svg>
                    </div>
                  </div>

                  {/* Template Info */}
                  <div className="p-4">
                    <div className="flex items-start justify-between gap-2 mb-1">
                      <h3 className="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors flex-1">
                        {template.name}
                      </h3>
                      {template.isCustom && onDeleteTemplate && (
                        <button
                          onClick={(e) => {
                            e.stopPropagation();
                            if (confirm(`Delete template "${template.name}"?`)) {
                              onDeleteTemplate(template.id);
                            }
                          }}
                          className="text-gray-400 hover:text-red-600 transition-colors flex-shrink-0"
                          title="Delete template"
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
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                          </svg>
                        </button>
                      )}
                    </div>
                    <p className="text-sm text-gray-600 line-clamp-2">
                      {template.description}
                    </p>
                    <div className="mt-3 flex items-center justify-between">
                      <span className="text-xs text-gray-500 capitalize">
                        {template.category} {template.isCustom && '(Custom)'}
                      </span>
                      <span className="text-xs text-gray-500">
                        {template.blocks.length} block{template.blocks.length !== 1 ? 's' : ''}
                      </span>
                    </div>
                  </div>
                </button>
              ))}
            </div>
          )}
        </div>

        {/* Footer */}
        <div className="border-t border-gray-200 p-4 bg-gray-50">
          <p className="text-sm text-gray-600 text-center">
            Click a template to preview and insert it into your page
          </p>
        </div>
      </div>

      {/* Preview Modal */}
      {previewTemplate && (
        <TemplatePreviewModal
          template={previewTemplate}
          onInsert={() => onSelectTemplate(previewTemplate)}
          onClose={() => setPreviewTemplate(null)}
        />
      )}
    </div>
  );
}
