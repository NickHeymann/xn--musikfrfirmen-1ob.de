"use client";
"use client";

import { useEditor, EditorContent } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Underline from '@tiptap/extension-underline';
import { TextStyle } from '@tiptap/extension-text-style';
import { Color } from '@tiptap/extension-color';
import { FontFamily } from '@tiptap/extension-font-family';
import TextAlign from '@tiptap/extension-text-align';
import Highlight from '@tiptap/extension-highlight';
import { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

interface RichTextEditorProps {
  value: string;
  onChange: (html: string) => void;
  placeholder?: string;
  label?: string;
}

const FONT_FAMILIES = [
  { label: 'Default', value: 'inherit' },
  { label: 'Poppins', value: "'Poppins', sans-serif" },
  { label: 'Inter', value: "'Inter', sans-serif" },
  { label: 'Georgia', value: "'Georgia', serif" },
  { label: 'Monaco', value: "'Monaco', monospace" },
];

const FONT_SIZES = [
  { label: 'Small', value: '0.875rem', class: 'text-sm' },
  { label: 'Normal', value: '1rem', class: 'text-base' },
  { label: 'Large', value: '1.125rem', class: 'text-lg' },
  { label: 'XL', value: '1.25rem', class: 'text-xl' },
  { label: '2XL', value: '1.5rem', class: 'text-2xl' },
];

const TEXT_COLORS = [
  { label: 'Default', value: null },
  { label: 'Black', value: '#000000' },
  { label: 'Gray', value: '#6B7280' },
  { label: 'Blue', value: '#3B82F6' },
  { label: 'Green', value: '#10B981' },
  { label: 'Red', value: '#EF4444' },
  { label: 'Orange', value: '#F59E0B' },
];

export function RichTextEditor({ value, onChange, placeholder, label }: RichTextEditorProps) {
  const [showLinkInput, setShowLinkInput] = useState(false);
  const [showColorPicker, setShowColorPicker] = useState(false);
  const [linkUrl, setLinkUrl] = useState('');

  const editor = useEditor({
    immediatelyRender: false, // Fix SSR hydration error
    extensions: [
      StarterKit,
      Link.configure({
        openOnClick: false,
        HTMLAttributes: {
          class: 'text-blue-600 underline cursor-pointer',
        },
      }),
      Underline,
      TextStyle,
      Color,
      FontFamily,
      TextAlign.configure({
        types: ['heading', 'paragraph'],
      }),
      Highlight.configure({
        multicolor: true,
      }),
    ],
    content: value,
    onUpdate: ({ editor }) => {
      onChange(editor.getHTML());
    },
    editorProps: {
      attributes: {
        class: 'prose prose-sm max-w-none focus:outline-none min-h-[120px] px-4 py-3',
      },
    },
  });

  useEffect(() => {
    if (editor && value !== editor.getHTML()) {
      editor.commands.setContent(value);
    }
  }, [value, editor]);

  const setLink = () => {
    if (!editor) return;

    if (linkUrl === '') {
      editor.chain().focus().extendMarkRange('link').unsetLink().run();
    } else {
      editor.chain().focus().extendMarkRange('link').setLink({ href: linkUrl }).run();
    }

    setShowLinkInput(false);
    setLinkUrl('');
  };

  if (!editor) return null;

  return (
    <div className="rich-text-editor">
      {label && (
        <label className="block text-sm font-medium text-gray-700 mb-2">
          {label}
        </label>
      )}

      <div className="relative border border-gray-300 rounded-lg bg-white overflow-hidden transition-all duration-200 hover:border-gray-400 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20">
        {/* Floating Toolbar - Always Visible */}
        <div className="sticky top-0 z-10 bg-gradient-to-b from-gray-50 to-white border-b border-gray-200 px-3 py-2 flex flex-wrap items-center gap-1 shadow-sm">
              {/* Font Family */}
              <select
                className="text-xs px-2 py-1 border border-gray-300 rounded hover:border-gray-400 focus:outline-none focus:border-blue-500 bg-white"
                onChange={(e) => {
                  const value = e.target.value;
                  if (value === 'inherit') {
                    editor.chain().focus().unsetFontFamily().run();
                  } else {
                    editor.chain().focus().setFontFamily(value).run();
                  }
                }}
                title="Font Family"
              >
                {FONT_FAMILIES.map((font) => (
                  <option key={font.value} value={font.value}>
                    {font.label}
                  </option>
                ))}
              </select>

              {/* Font Size */}
              <select
                className="text-xs px-2 py-1 border border-gray-300 rounded hover:border-gray-400 focus:outline-none focus:border-blue-500 bg-white"
                onChange={(e) => {
                  const fontSize = e.target.value;
                  if (fontSize === '1rem') {
                    editor.chain().focus().unsetMark('textStyle').run();
                  } else {
                    editor.chain().focus().setMark('textStyle', { fontSize }).run();
                  }
                }}
                title="Font Size"
              >
                {FONT_SIZES.map((size) => (
                  <option key={size.value} value={size.value}>
                    {size.label}
                  </option>
                ))}
              </select>

              <div className="w-px h-6 bg-gray-300 mx-1" />

              {/* Bold */}
              <button
                type="button"
                onClick={() => editor.chain().focus().toggleBold().run()}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive('bold') ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Bold (Cmd+B)"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5 3a1 1 0 000 2h1v10H5a1 1 0 100 2h10a1 1 0 100-2h-1V5h1a1 1 0 100-2H5zm4 2h2a2 2 0 110 4H9V5zm2 6a3 3 0 110 6H9v-6h2z" />
                </svg>
              </button>

              {/* Italic */}
              <button
                type="button"
                onClick={() => editor.chain().focus().toggleItalic().run()}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive('italic') ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Italic (Cmd+I)"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1z" transform="skewX(-15)" />
                </svg>
              </button>

              {/* Underline */}
              <button
                type="button"
                onClick={() => editor.chain().focus().toggleUnderline().run()}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive('underline') ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Underline (Cmd+U)"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 2a1 1 0 011 1v8a3 3 0 11-6 0V3a1 1 0 112 0v8a1 1 0 102 0V3a1 1 0 011-1zm-6 16a1 1 0 100-2h12a1 1 0 100 2H4z" />
                </svg>
              </button>

              <div className="w-px h-6 bg-gray-300 mx-1" />

              {/* Link */}
              <button
                type="button"
                onClick={() => {
                  setShowLinkInput(!showLinkInput);
                  setLinkUrl(editor.getAttributes('link').href || '');
                }}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive('link') ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Add Link"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" />
                </svg>
              </button>

              {/* Bullet List */}
              <button
                type="button"
                onClick={() => editor.chain().focus().toggleBulletList().run()}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive('bulletList') ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Bullet List"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                </svg>
              </button>

              {/* Ordered List */}
              <button
                type="button"
                onClick={() => editor.chain().focus().toggleOrderedList().run()}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive('orderedList') ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Numbered List"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 9a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 14a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                </svg>
              </button>

              <div className="w-px h-6 bg-gray-300 mx-1" />

              {/* Text Color */}
              <button
                type="button"
                onClick={() => setShowColorPicker(!showColorPicker)}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  showColorPicker ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Text Color"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fillRule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm6 13.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm0-7a1 1 0 100-2 1 1 0 000 2z" clipRule="evenodd" />
                </svg>
              </button>

              {/* Highlight */}
              <button
                type="button"
                onClick={() => editor.chain().focus().toggleHighlight({ color: '#FEF3C7' }).run()}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive('highlight') ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Highlight"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fillRule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clipRule="evenodd" />
                </svg>
              </button>

              <div className="w-px h-6 bg-gray-300 mx-1" />

              {/* Align Left */}
              <button
                type="button"
                onClick={() => editor.chain().focus().setTextAlign('left').run()}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive({ textAlign: 'left' }) ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Align Left"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fillRule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1z" clipRule="evenodd" />
                </svg>
              </button>

              {/* Align Center */}
              <button
                type="button"
                onClick={() => editor.chain().focus().setTextAlign('center').run()}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive({ textAlign: 'center' }) ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Align Center"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fillRule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm2 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm-2 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clipRule="evenodd" />
                </svg>
              </button>

              {/* Align Right */}
              <button
                type="button"
                onClick={() => editor.chain().focus().setTextAlign('right').run()}
                className={`p-1.5 rounded hover:bg-gray-200 transition-colors ${
                  editor.isActive({ textAlign: 'right' }) ? 'bg-gray-200 text-blue-600' : 'text-gray-700'
                }`}
                title="Align Right"
              >
                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fillRule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm4 4a1 1 0 011-1h8a1 1 0 110 2H8a1 1 0 01-1-1zm-4 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm4 4a1 1 0 011-1h8a1 1 0 110 2H8a1 1 0 01-1-1z" clipRule="evenodd" />
                </svg>
              </button>
        </div>

        {/* Link Input Popup */}
        <AnimatePresence>
          {showLinkInput && (
            <motion.div
              initial={{ opacity: 0, height: 0 }}
              animate={{ opacity: 1, height: 'auto' }}
              exit={{ opacity: 0, height: 0 }}
              className="border-b border-gray-200 bg-blue-50 px-4 py-3"
            >
              <div className="flex items-center gap-2">
                <input
                  type="url"
                  value={linkUrl}
                  onChange={(e) => setLinkUrl(e.target.value)}
                  placeholder="https://example.com"
                  className="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                  onKeyDown={(e) => {
                    if (e.key === 'Enter') {
                      e.preventDefault();
                      setLink();
                    }
                  }}
                  autoFocus
                />
                <button
                  type="button"
                  onClick={setLink}
                  className="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                >
                  Set
                </button>
                <button
                  type="button"
                  onClick={() => {
                    setShowLinkInput(false);
                    setLinkUrl('');
                  }}
                  className="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors"
                >
                  Cancel
                </button>
              </div>
            </motion.div>
          )}
        </AnimatePresence>

        {/* Color Picker Popup */}
        <AnimatePresence>
          {showColorPicker && (
            <motion.div
              initial={{ opacity: 0, height: 0 }}
              animate={{ opacity: 1, height: 'auto' }}
              exit={{ opacity: 0, height: 0 }}
              className="border-b border-gray-200 bg-gray-50 px-4 py-3"
            >
              <div className="flex items-center gap-2 flex-wrap">
                <span className="text-xs font-medium text-gray-700 mr-2">Text Color:</span>
                {TEXT_COLORS.map((color) => (
                  <button
                    key={color.label}
                    type="button"
                    onClick={() => {
                      if (color.value) {
                        editor.chain().focus().setColor(color.value).run();
                      } else {
                        editor.chain().focus().unsetColor().run();
                      }
                      setShowColorPicker(false);
                    }}
                    className="flex items-center gap-1.5 px-2 py-1 text-xs border border-gray-300 rounded hover:border-gray-400 transition-colors bg-white"
                    title={color.label}
                  >
                    {color.value ? (
                      <div
                        className="w-4 h-4 rounded border border-gray-300"
                        style={{ backgroundColor: color.value }}
                      />
                    ) : (
                      <div className="w-4 h-4 rounded border border-gray-300 bg-white" />
                    )}
                    <span>{color.label}</span>
                  </button>
                ))}
              </div>
            </motion.div>
          )}
        </AnimatePresence>

        {/* Editor Content */}
        <EditorContent
          editor={editor}
          className="editor-content"
          placeholder={placeholder}
        />
      </div>
    </div>
  );
}
