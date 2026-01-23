'use client';

/**
 * Template Preview Modal Component
 * 
 * Shows a larger preview of a template before inserting it.
 * Displays preview image, description, and list of included blocks.
 */

import type { BlockTemplate } from '../types/blockTemplate';

interface TemplatePreviewModalProps {
  template: BlockTemplate;
  onInsert: () => void;
  onClose: () => void;
}

export function TemplatePreviewModal({ template, onInsert, onClose }: TemplatePreviewModalProps) {
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50" onClick={onClose}>
      <div
        className="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Header */}
        <div className="border-b border-gray-200 p-6">
          <div className="flex items-center justify-between">
            <div>
              <h2 className="text-2xl font-bold text-gray-900">{template.name}</h2>
              <p className="text-sm text-gray-500 mt-1 capitalize">
                {template.category} {template.isCustom && '· Custom Template'}
              </p>
            </div>
            <button
              onClick={onClose}
              className="text-gray-400 hover:text-gray-600 transition-colors"
              aria-label="Close preview"
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
        </div>

        {/* Content */}
        <div className="flex-1 overflow-y-auto p-6">
          {/* Preview Image */}
          <div className="mb-6">
            <div className="aspect-video bg-gray-100 rounded-lg overflow-hidden relative">
              {template.preview ? (
                <img
                  src={template.preview}
                  alt={template.name}
                  className="w-full h-full object-cover"
                  onError={(e) => {
                    e.currentTarget.style.display = 'none';
                  }}
                />
              ) : null}
              <div className="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                <svg
                  className="w-24 h-24 opacity-50"
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
          </div>

          {/* Description */}
          <div className="mb-6">
            <h3 className="text-lg font-semibold text-gray-900 mb-2">Description</h3>
            <p className="text-gray-700">{template.description}</p>
          </div>

          {/* Included Blocks */}
          <div>
            <h3 className="text-lg font-semibold text-gray-900 mb-3">
              Included Blocks ({template.blocks.length})
            </h3>
            <div className="space-y-2">
              {template.blocks.map((block, index) => (
                <div
                  key={index}
                  className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg"
                >
                  <div className="w-8 h-8 bg-blue-100 text-blue-600 rounded flex items-center justify-center font-semibold text-sm">
                    {index + 1}
                  </div>
                  <div className="flex-1">
                    <p className="font-medium text-gray-900">{block.type}</p>
                    {typeof block.props.title === 'string' ? (
                      <p className="text-sm text-gray-600">
                        {block.props.title}
                      </p>
                    ) : null}
                  </div>
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
                      d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                    />
                  </svg>
                </div>
              ))}
            </div>
          </div>

          {/* Metadata */}
          {template.metadata && (
            <div className="mt-6 pt-6 border-t border-gray-200">
              <div className="flex gap-6 text-sm text-gray-600">
                {template.metadata.createdAt && (
                  <div>
                    <span className="font-medium">Created:</span>{' '}
                    {new Date(template.metadata.createdAt).toLocaleDateString()}
                  </div>
                )}
                {template.metadata.usageCount !== undefined && (
                  <div>
                    <span className="font-medium">Used:</span>{' '}
                    {template.metadata.usageCount} time{template.metadata.usageCount !== 1 ? 's' : ''}
                  </div>
                )}
              </div>
            </div>
          )}
        </div>

        {/* Footer */}
        <div className="border-t border-gray-200 p-6 bg-gray-50">
          <div className="flex gap-3 justify-end">
            <button
              onClick={onClose}
              className="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
              onClick={() => {
                onInsert();
                onClose();
              }}
              className="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors font-medium"
            >
              Insert Template
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
