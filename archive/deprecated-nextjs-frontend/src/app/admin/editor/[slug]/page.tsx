'use client';

/**
 * Visual Editor Main Page - Apple-Quality Inline Editing
 * Default: View Mode (read-only preview)
 * Click Edit Mode: Live editing with inline controls
 */

import { useState, useEffect } from 'react';
import { useParams } from 'next/navigation';
import { api } from '@/lib/api/client';
import { ViewMode } from '@/visual-editor/modes/ViewMode';
import { EditMode } from '@/visual-editor/modes/EditMode';
import { ModeToggle } from '@/visual-editor/components/ModeToggle';
import { EditorProvider, useEditor } from '@/visual-editor/context/EditorContext';
import { ToastProvider } from '@/visual-editor/context/ToastContext';
import { ValidationProvider } from '@/visual-editor/context/ValidationContext';
import { EditorModeProvider } from '@/visual-editor/context/EditorModeContext';
import { enrichBlocksWithDefaults } from '@/visual-editor/lib/defaultBlockData';
import type { PageData } from '@/types/visual-editor';

export default function VisualEditorPage() {
  const params = useParams();
  const slug = params.slug as string;
  const [pageData, setPageData] = useState<PageData | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    async function loadPage() {
      try {
        const data = await api.pages.get(slug);

        // Enrich blocks with default content from component files
        // This ensures editor shows actual website content when API doesn't have overrides
        const enrichedData = {
          ...data,
          content: {
            ...data.content,
            blocks: enrichBlocksWithDefaults(data.content.blocks),
          },
        };

        setPageData(enrichedData);
      } catch (err) {
        console.error('Failed to load page:', err);
        setError('Failed to load page. Make sure the Laravel backend is running.');
      } finally {
        setIsLoading(false);
      }
    }
    loadPage();
  }, [slug]);

  if (isLoading) {
    return <EditorSkeleton />;
  }

  if (error) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-neutral-50">
        <div className="text-center max-w-md p-8 bg-white rounded-xl shadow-lg border border-neutral-200">
          <div className="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
            <svg className="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h2 className="text-xl font-semibold text-neutral-900 mb-2">Error Loading Page</h2>
          <p className="text-neutral-600 mb-4">{error}</p>
          <p className="text-sm text-neutral-500">
            Check that the Laravel API is running at:{' '}
            <code className="bg-neutral-100 px-2 py-1 rounded text-xs font-mono">
              {process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8001/api'}
            </code>
          </p>
        </div>
      </div>
    );
  }

  if (!pageData) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-neutral-50">
        <div className="text-center">
          <h2 className="text-xl font-semibold text-neutral-900 mb-2">Page Not Found</h2>
          <p className="text-neutral-600">
            The page &quot;{slug}&quot; doesn&apos;t exist.
          </p>
        </div>
      </div>
    );
  }

  return (
    <EditorModeProvider>
      <ToastProvider>
        <EditorProvider initialBlocks={pageData.content.blocks} slug={slug}>
          <ValidationProvider>
            <EditorModeRouter />
          </ValidationProvider>
        </EditorProvider>
      </ToastProvider>
    </EditorModeProvider>
  );
}

/**
 * EditorModeRouter - Switches between View and Edit modes
 */
function EditorModeRouter() {
  const { mode } = useEditor();

  return (
    <>
      <ModeToggle />
      {mode === 'view' ? <ViewMode /> : <EditMode />}
    </>
  );
}

/**
 * Loading Skeleton - Apple-style minimal animation
 */
function EditorSkeleton() {
  return (
    <div className="min-h-screen bg-white">
      {/* Toolbar skeleton */}
      <div className="h-[52px] border-b border-neutral-200 animate-pulse">
        <div className="flex items-center justify-between h-full px-4">
          <div className="w-24 h-6 bg-neutral-200 rounded" />
          <div className="w-48 h-6 bg-neutral-200 rounded" />
          <div className="flex gap-3">
            <div className="w-20 h-8 bg-neutral-200 rounded" />
            <div className="w-20 h-8 bg-neutral-200 rounded" />
          </div>
        </div>
      </div>

      {/* Content skeleton */}
      <div className="p-8 space-y-8 animate-pulse">
        <div className="h-96 bg-neutral-100 rounded-lg" />
        <div className="h-64 bg-neutral-100 rounded-lg" />
        <div className="h-48 bg-neutral-100 rounded-lg" />
      </div>
    </div>
  );
}
