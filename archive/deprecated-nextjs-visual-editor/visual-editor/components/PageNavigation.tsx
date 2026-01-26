'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { FileText, ChevronDown, AlertCircle } from 'lucide-react';
import { useEditor } from '../context/EditorContext';

interface Page {
  slug: string;
  title: string;
  updated_at: string;
}

interface PageNavigationProps {
  currentSlug: string;
}

// Pages that should appear in the navigation dropdown
// Only show utility pages (ueber-uns, impressum, datenschutz) - exclude main content pages
const EXCLUDED_SLUGS: string[] = ['home', 'services', 'faq', 'about'];

export function PageNavigation({ currentSlug }: PageNavigationProps) {
  const [pages, setPages] = useState<Page[]>([]);
  const [isOpen, setIsOpen] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const router = useRouter();
  const { hasUnsavedChanges } = useEditor();

  useEffect(() => {
    // Fetch available pages
    async function loadPages() {
      try {
        const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8001/api';
        const response = await fetch(`${apiUrl}/pages`);
        const data = await response.json();

        // Filter out excluded pages
        const filteredPages = data.filter((page: Page) =>
          !EXCLUDED_SLUGS.includes(page.slug)
        );

        setPages(filteredPages);
      } catch (error) {
        console.error('Failed to load pages:', error);
      }
    }
    loadPages();
  }, []);

  const currentPage = pages.find(p => p.slug === currentSlug);

  const handlePageChange = (slug: string) => {
    if (slug === currentSlug) {
      setIsOpen(false);
      return;
    }

    // Warn if there are unsaved changes
    if (hasUnsavedChanges) {
      const confirmed = window.confirm(
        'You have unsaved changes. They will be saved as draft. Continue?'
      );
      if (!confirmed) {
        setIsOpen(false);
        return;
      }
    }

    setIsLoading(true);
    router.push(`/admin/editor/${slug}`);
  };

  return (
    <div className="relative">
      {/* Page Selector Dropdown */}
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="flex items-center gap-3 px-4 py-2.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors min-w-[250px]"
        disabled={isLoading}
      >
        <FileText size={18} className="text-gray-600" />
        <div className="flex-1 text-left">
          <div className="text-sm font-medium text-gray-900">
            {currentPage?.title || currentSlug}
          </div>
          <div className="text-xs text-gray-500">
            {pages.length} pages available
          </div>
        </div>
        {hasUnsavedChanges && (
          <div className="flex items-center gap-1 text-xs text-amber-600">
            <AlertCircle size={14} />
            <span>Draft</span>
          </div>
        )}
        <ChevronDown
          size={16}
          className={`text-gray-400 transition-transform ${isOpen ? 'rotate-180' : ''}`}
        />
      </button>

      {/* Dropdown Menu */}
      {isOpen && (
        <>
          {/* Backdrop */}
          <div
            className="fixed inset-0 z-40"
            onClick={() => setIsOpen(false)}
          />

          {/* Menu */}
          <div className="absolute top-full left-0 mt-2 w-[250px] bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-[400px] overflow-y-auto">
            <div className="p-2">
              <div className="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Switch Page
              </div>
              {pages.map((page) => {
                const isActive = page.slug === currentSlug;
                return (
                  <button
                    key={page.slug}
                    onClick={() => handlePageChange(page.slug)}
                    className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-left transition-colors ${
                      isActive
                        ? 'bg-blue-50 text-blue-700'
                        : 'hover:bg-gray-50 text-gray-700'
                    }`}
                  >
                    <FileText size={16} className={isActive ? 'text-blue-600' : 'text-gray-400'} />
                    <div className="flex-1 min-w-0">
                      <div className={`text-sm font-medium truncate ${
                        isActive ? 'text-blue-700' : 'text-gray-900'
                      }`}>
                        {page.title}
                      </div>
                      <div className="text-xs text-gray-500 truncate">
                        /{page.slug}
                      </div>
                    </div>
                    {isActive && (
                      <div className="w-2 h-2 bg-blue-600 rounded-full" />
                    )}
                  </button>
                );
              })}
            </div>
          </div>
        </>
      )}
    </div>
  );
}
