"use client";

import { EditorAuthProvider } from '@/contexts/EditorAuthContext';
import type { ReactNode } from 'react';
import '@/visual-editor/styles/apple-editor.css';

export default function AdminLayout({ children }: { children: ReactNode }) {
  return (
    <EditorAuthProvider>
      {children}
    </EditorAuthProvider>
  );
}
