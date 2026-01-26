'use client';

import { useEffect } from 'react';
import { errorLogger } from '@/lib/error-logger';

export function ErrorLoggerInit() {
  useEffect(() => {
    // Logger is initialized automatically on import
    // This component just ensures it's loaded on client side
    errorLogger.logInfo('ErrorLogger initialized');
  }, []);

  return null;
}
