import { EditorAuthProvider } from '@/contexts/EditorAuthContext';
import type { ReactNode } from 'react';

export default function AdminLayout({ children }: { children: ReactNode }) {
  return (
    <EditorAuthProvider>
      {children}
    </EditorAuthProvider>
  );
}
