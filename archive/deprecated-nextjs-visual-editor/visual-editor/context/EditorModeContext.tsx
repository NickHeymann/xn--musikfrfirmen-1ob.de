'use client';

import { createContext, useContext, ReactNode } from 'react';

interface EditorModeContextValue {
  isEditorMode: boolean;
}

const EditorModeContext = createContext<EditorModeContextValue>({
  isEditorMode: false,
});

export function EditorModeProvider({ children }: { children: ReactNode }) {
  return (
    <EditorModeContext.Provider value={{ isEditorMode: true }}>
      {children}
    </EditorModeContext.Provider>
  );
}

export function useEditorMode() {
  return useContext(EditorModeContext);
}
