"use client";

import { createContext, useContext, useState, useEffect, ReactNode } from 'react';

interface EditorAuthContextType {
  isAuthenticated: boolean;
  login: (password: string) => boolean;
  logout: () => void;
}

const EditorAuthContext = createContext<EditorAuthContextType | undefined>(undefined);

// POC: Simple password authentication (will be replaced with Laravel Sanctum in Phase 2)
const EDITOR_PASSWORD = process.env.NEXT_PUBLIC_EDITOR_PASSWORD || 'admin123';

export function EditorAuthProvider({ children }: { children: ReactNode }) {
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  // Check authentication on mount
  useEffect(() => {
    const authToken = localStorage.getItem('editor_auth_token');
    if (authToken === EDITOR_PASSWORD) {
      setIsAuthenticated(true);
    }
  }, []);

  const login = (password: string): boolean => {
    if (password === EDITOR_PASSWORD) {
      localStorage.setItem('editor_auth_token', password);
      setIsAuthenticated(true);
      return true;
    }
    return false;
  };

  const logout = () => {
    localStorage.removeItem('editor_auth_token');
    setIsAuthenticated(false);
  };

  return (
    <EditorAuthContext.Provider value={{ isAuthenticated, login, logout }}>
      {children}
    </EditorAuthContext.Provider>
  );
}

export function useEditorAuth() {
  const context = useContext(EditorAuthContext);
  if (context === undefined) {
    throw new Error('useEditorAuth must be used within EditorAuthProvider');
  }
  return context;
}
