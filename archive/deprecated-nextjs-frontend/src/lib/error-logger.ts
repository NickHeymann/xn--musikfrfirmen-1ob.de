/**
 * Client-Side Error Logger
 * Sends errors to a local endpoint for debugging
 */

type ErrorLog = {
  timestamp: string;
  type: 'error' | 'warning' | 'info';
  message: string;
  stack?: string;
  componentStack?: string;
  url?: string;
  userAgent?: string;
  metadata?: Record<string, any>;
};

class ErrorLogger {
  private logs: ErrorLog[] = [];
  private maxLogs = 100;

  constructor() {
    if (typeof window !== 'undefined') {
      this.setupGlobalErrorHandlers();
    }
  }

  private setupGlobalErrorHandlers() {
    // Capture unhandled errors
    window.addEventListener('error', (event) => {
      this.logError({
        message: event.message,
        stack: event.error?.stack,
        metadata: {
          filename: event.filename,
          lineno: event.lineno,
          colno: event.colno,
        },
      });
    });

    // Capture unhandled promise rejections
    window.addEventListener('unhandledrejection', (event) => {
      this.logError({
        message: `Unhandled Promise Rejection: ${event.reason}`,
        stack: event.reason?.stack,
      });
    });

    // DO NOT intercept console.error - causes infinite loops!
  }

  logError(error: Partial<ErrorLog>) {
    const log: ErrorLog = {
      timestamp: new Date().toISOString(),
      type: 'error',
      message: error.message || 'Unknown error',
      stack: error.stack,
      componentStack: error.componentStack,
      url: typeof window !== 'undefined' ? window.location.href : undefined,
      userAgent: typeof navigator !== 'undefined' ? navigator.userAgent : undefined,
      metadata: error.metadata,
    };

    this.logs.push(log);

    // Keep only last N logs
    if (this.logs.length > this.maxLogs) {
      this.logs = this.logs.slice(-this.maxLogs);
    }

    // DON'T send to server automatically (causes loops)
    // Only manual export via Debug Panel
    
    // DON'T log to console either (can cause loops if console.error is intercepted elsewhere)
  }

  logWarning(message: string, metadata?: Record<string, any>) {
    this.logError({
      type: 'warning',
      message,
      metadata,
    });
  }

  logInfo(message: string, metadata?: Record<string, any>) {
    const log: ErrorLog = {
      timestamp: new Date().toISOString(),
      type: 'info',
      message,
      metadata,
    };
    this.logs.push(log);
    console.info('[ErrorLogger]', log);
  }

  private sendingToServer = false;

  private async sendToServer(log: ErrorLog) {
    // Prevent infinite loops
    if (this.sendingToServer) return;

    try {
      this.sendingToServer = true;

      // Send to a local logging endpoint with timeout
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 2000);

      await fetch('/api/log-error', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(log),
        signal: controller.signal,
      }).catch(() => {
        // Silently fail if endpoint doesn't exist
      }).finally(() => {
        clearTimeout(timeoutId);
      });
    } catch (e) {
      // Don't log errors about logging
    } finally {
      this.sendingToServer = false;
    }
  }

  getLogs() {
    return this.logs;
  }

  clearLogs() {
    this.logs = [];
  }

  // Export logs as JSON
  exportLogs() {
    const blob = new Blob([JSON.stringify(this.logs, null, 2)], {
      type: 'application/json',
    });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `error-logs-${Date.now()}.json`;
    a.click();
    URL.revokeObjectURL(url);
  }
}

// Singleton instance
export const errorLogger = new ErrorLogger();

// Helper function for React Error Boundaries
export function logReactError(error: Error, errorInfo: { componentStack: string }) {
  errorLogger.logError({
    message: error.message,
    stack: error.stack,
    componentStack: errorInfo.componentStack,
    metadata: { source: 'ErrorBoundary' },
  });
}
