'use client';

import { useState, useEffect } from 'react';
import { errorLogger } from '@/lib/error-logger';

export function DebugPanel() {
  const [isOpen, setIsOpen] = useState(false);
  const [logs, setLogs] = useState<any[]>([]);
  const [filter, setFilter] = useState<'all' | 'error' | 'warning' | 'info'>('all');

  useEffect(() => {
    // Update logs every second
    const interval = setInterval(() => {
      setLogs(errorLogger.getLogs());
    }, 1000);

    return () => clearInterval(interval);
  }, []);

  const filteredLogs = logs.filter(log =>
    filter === 'all' || log.type === filter
  );

  if (process.env.NODE_ENV !== 'development') {
    return null;
  }

  return (
    <>
      {/* Toggle Button */}
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="fixed bottom-4 right-4 z-[10000] bg-red-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-red-700 transition-colors font-mono text-sm"
        title="Open Debug Panel"
      >
        🐛 Errors ({logs.length})
      </button>

      {/* Debug Panel */}
      {isOpen && (
        <div className="fixed inset-0 z-[9999] bg-black/50 backdrop-blur-sm">
          <div className="fixed right-0 top-0 bottom-0 w-[600px] bg-white shadow-2xl flex flex-col">
            {/* Header */}
            <div className="bg-gray-900 text-white p-4 flex items-center justify-between">
              <h2 className="font-bold text-lg">🐛 Debug Panel</h2>
              <div className="flex gap-2">
                <button
                  onClick={() => errorLogger.clearLogs()}
                  className="px-3 py-1 bg-yellow-600 rounded text-sm hover:bg-yellow-700"
                >
                  Clear
                </button>
                <button
                  onClick={() => errorLogger.exportLogs()}
                  className="px-3 py-1 bg-blue-600 rounded text-sm hover:bg-blue-700"
                >
                  Export
                </button>
                <button
                  onClick={() => setIsOpen(false)}
                  className="px-3 py-1 bg-red-600 rounded text-sm hover:bg-red-700"
                >
                  Close
                </button>
              </div>
            </div>

            {/* Filter */}
            <div className="bg-gray-100 p-3 border-b flex gap-2">
              {(['all', 'error', 'warning', 'info'] as const).map(type => (
                <button
                  key={type}
                  onClick={() => setFilter(type)}
                  className={`px-3 py-1 rounded text-sm font-medium ${
                    filter === type
                      ? 'bg-blue-600 text-white'
                      : 'bg-white text-gray-700 hover:bg-gray-200'
                  }`}
                >
                  {type.charAt(0).toUpperCase() + type.slice(1)}
                  {type !== 'all' && ` (${logs.filter(l => l.type === type).length})`}
                </button>
              ))}
            </div>

            {/* Logs */}
            <div className="flex-1 overflow-y-auto p-4 space-y-3 font-mono text-xs">
              {filteredLogs.length === 0 ? (
                <div className="text-center text-gray-500 py-8">
                  No {filter !== 'all' ? filter : ''} logs yet
                </div>
              ) : (
                filteredLogs.map((log, i) => (
                  <div
                    key={i}
                    className={`p-3 rounded border-l-4 ${
                      log.type === 'error'
                        ? 'bg-red-50 border-red-500'
                        : log.type === 'warning'
                        ? 'bg-yellow-50 border-yellow-500'
                        : 'bg-blue-50 border-blue-500'
                    }`}
                  >
                    <div className="flex items-start justify-between mb-2">
                      <span className={`font-bold ${
                        log.type === 'error' ? 'text-red-700' :
                        log.type === 'warning' ? 'text-yellow-700' :
                        'text-blue-700'
                      }`}>
                        {log.type.toUpperCase()}
                      </span>
                      <span className="text-gray-500 text-xs">
                        {new Date(log.timestamp).toLocaleTimeString()}
                      </span>
                    </div>

                    <div className="text-gray-900 mb-2 break-words">
                      {log.message}
                    </div>

                    {log.metadata && (
                      <details className="text-gray-600 text-xs">
                        <summary className="cursor-pointer hover:text-gray-900">
                          Metadata
                        </summary>
                        <pre className="mt-2 p-2 bg-gray-100 rounded overflow-x-auto">
                          {JSON.stringify(log.metadata, null, 2)}
                        </pre>
                      </details>
                    )}

                    {log.stack && (
                      <details className="text-gray-600 text-xs mt-2">
                        <summary className="cursor-pointer hover:text-gray-900">
                          Stack Trace
                        </summary>
                        <pre className="mt-2 p-2 bg-gray-100 rounded overflow-x-auto whitespace-pre-wrap">
                          {log.stack}
                        </pre>
                      </details>
                    )}

                    {log.componentStack && (
                      <details className="text-gray-600 text-xs mt-2">
                        <summary className="cursor-pointer hover:text-gray-900">
                          Component Stack
                        </summary>
                        <pre className="mt-2 p-2 bg-gray-100 rounded overflow-x-auto whitespace-pre-wrap">
                          {log.componentStack}
                        </pre>
                      </details>
                    )}
                  </div>
                ))
              )}
            </div>
          </div>
        </div>
      )}
    </>
  );
}
