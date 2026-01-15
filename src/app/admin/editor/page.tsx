"use client";

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { useEditorAuth } from '@/hooks/useEditorAuth';

export default function VisualEditorPage() {
  const { isAuthenticated, logout } = useEditorAuth();
  const router = useRouter();
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  useEffect(() => {
    if (mounted && !isAuthenticated) {
      router.push('/admin/login');
    }
  }, [isAuthenticated, mounted, router]);

  if (!mounted || !isAuthenticated) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-gray-600">Loading editor...</div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Editor Toolbar */}
      <div className="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
        <div className="flex items-center gap-4">
          <h1 className="text-lg font-semibold text-gray-900">
            Visual Page Editor
          </h1>
          <span className="text-sm text-gray-500">
            musikfürfirmen.de
          </span>
        </div>

        <div className="flex items-center gap-3">
          <button
            onClick={logout}
            className="px-4 py-2 text-sm text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded"
          >
            Logout
          </button>
        </div>
      </div>

      {/* Editor Area - Will add Destack here in next task */}
      <div className="p-8">
        <div className="bg-white rounded-lg shadow-sm p-6 text-center">
          <p className="text-gray-600">
            Destack editor will be loaded here...
          </p>
        </div>
      </div>
    </div>
  );
}
