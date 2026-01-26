/**
 * Development Helper Panel
 *
 * Ein schwebendes Debug-Panel für besseres Debugging mit Claude Code.
 * Zeigt Errors, Console Logs, und erstellt Claude-freundliche Error Reports.
 *
 * Installation:
 * 1. Kopiere diese Datei in Dein Projekt
 * 2. Füge in <head> hinzu: <script src="debug-helper.js"></script>
 * 3. Panel erscheint automatisch bei Errors
 *
 * Features:
 * - Automatisches Error Tracking
 * - "Copy for Claude" Button (formatierter Error Report)
 * - Screenshot + Console Logs
 * - User Action Tracking (Breadcrumbs)
 * - Network Request Monitoring
 *
 * @version 1.0.0
 * @author Nick Heymann
 */

(function() {
    'use strict';

    // Nur in Development Mode aktivieren
    const isDev = window.location.hostname === 'localhost' ||
                  window.location.hostname === '127.0.0.1' ||
                  window.location.search.includes('debug=true');

    if (!isDev) return;

    // State
    const state = {
        errors: [],
        breadcrumbs: [],
        networkLogs: [],
        maxBreadcrumbs: 20,
        maxErrors: 10,
        panelVisible: false
    };

    // Init
    function init() {
        createPanel();
        setupErrorHandlers();
        setupBreadcrumbs();
        setupNetworkMonitoring();
        setupKeyboardShortcuts();
        console.log('🐛 Debug Helper Panel aktiviert (Drücke Cmd/Ctrl+Shift+D)');
    }

    // UI Panel erstellen
    function createPanel() {
        const panel = document.createElement('div');
        panel.id = 'debug-helper-panel';
        panel.innerHTML = `
            <div class="debug-panel-header">
                <span class="debug-title">🐛 Debug Assistant</span>
                <button class="debug-close" onclick="window.DebugHelper.toggle()">✕</button>
            </div>
            <div class="debug-panel-body">
                <div class="debug-status">
                    <span class="debug-status-indicator" id="debug-status-indicator">●</span>
                    <span id="debug-status-text">Keine Errors</span>
                </div>
                <div class="debug-stats">
                    <div>Errors: <strong id="debug-error-count">0</strong></div>
                    <div>Actions: <strong id="debug-action-count">0</strong></div>
                    <div>Network: <strong id="debug-network-count">0</strong></div>
                </div>
                <div class="debug-errors" id="debug-errors"></div>
            </div>
            <div class="debug-panel-footer">
                <button class="debug-btn" onclick="window.DebugHelper.copyForClaude()">
                    📋 Copy for Claude
                </button>
                <button class="debug-btn" onclick="window.DebugHelper.screenshot()">
                    📸 Screenshot + Logs
                </button>
                <button class="debug-btn" onclick="window.DebugHelper.clear()">
                    🗑️ Clear
                </button>
            </div>
        `;

        // Styles
        const style = document.createElement('style');
        style.textContent = `
            #debug-helper-panel {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 400px;
                max-height: 600px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 12px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                z-index: 999999;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                color: white;
                display: none;
                flex-direction: column;
                overflow: hidden;
                animation: slideIn 0.3s ease-out;
            }

            #debug-helper-panel.visible {
                display: flex;
            }

            @keyframes slideIn {
                from { transform: translateY(100%); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }

            .debug-panel-header {
                padding: 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: rgba(0,0,0,0.2);
                cursor: move;
            }

            .debug-title {
                font-weight: 600;
                font-size: 16px;
            }

            .debug-close {
                background: transparent;
                border: none;
                color: white;
                font-size: 20px;
                cursor: pointer;
                padding: 0;
                width: 24px;
                height: 24px;
                opacity: 0.7;
                transition: opacity 0.2s;
            }

            .debug-close:hover {
                opacity: 1;
            }

            .debug-panel-body {
                padding: 16px;
                flex: 1;
                overflow-y: auto;
                background: rgba(0,0,0,0.1);
            }

            .debug-status {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 12px;
                padding: 12px;
                background: rgba(255,255,255,0.1);
                border-radius: 8px;
            }

            .debug-status-indicator {
                font-size: 12px;
            }

            .debug-status-indicator.ok { color: #10b981; }
            .debug-status-indicator.error { color: #ef4444; animation: pulse 1s infinite; }

            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }

            .debug-stats {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
                margin-bottom: 16px;
                font-size: 13px;
            }

            .debug-stats > div {
                padding: 8px;
                background: rgba(255,255,255,0.1);
                border-radius: 6px;
                text-align: center;
            }

            .debug-errors {
                max-height: 300px;
                overflow-y: auto;
            }

            .debug-error-item {
                background: rgba(239, 68, 68, 0.2);
                border-left: 3px solid #ef4444;
                padding: 12px;
                margin-bottom: 8px;
                border-radius: 6px;
                font-size: 13px;
                line-height: 1.5;
            }

            .debug-error-file {
                font-weight: 600;
                color: #fbbf24;
                margin-bottom: 4px;
            }

            .debug-error-message {
                margin-bottom: 8px;
            }

            .debug-error-time {
                font-size: 11px;
                opacity: 0.7;
            }

            .debug-panel-footer {
                padding: 12px;
                background: rgba(0,0,0,0.2);
                display: flex;
                gap: 8px;
            }

            .debug-btn {
                flex: 1;
                padding: 10px;
                background: rgba(255,255,255,0.2);
                border: 1px solid rgba(255,255,255,0.3);
                border-radius: 6px;
                color: white;
                font-size: 12px;
                cursor: pointer;
                transition: all 0.2s;
                font-weight: 500;
            }

            .debug-btn:hover {
                background: rgba(255,255,255,0.3);
                transform: translateY(-1px);
            }

            .debug-btn:active {
                transform: translateY(0);
            }

            /* Scrollbar Styling */
            .debug-panel-body::-webkit-scrollbar,
            .debug-errors::-webkit-scrollbar {
                width: 6px;
            }

            .debug-panel-body::-webkit-scrollbar-track,
            .debug-errors::-webkit-scrollbar-track {
                background: rgba(0,0,0,0.1);
            }

            .debug-panel-body::-webkit-scrollbar-thumb,
            .debug-errors::-webkit-scrollbar-thumb {
                background: rgba(255,255,255,0.3);
                border-radius: 3px;
            }
        `;

        document.head.appendChild(style);
        document.body.appendChild(panel);

        // Draggable
        makeDraggable(panel);
    }

    // Draggable Panel
    function makeDraggable(element) {
        let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        const header = element.querySelector('.debug-panel-header');

        header.onmousedown = dragMouseDown;

        function dragMouseDown(e) {
            e.preventDefault();
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e.preventDefault();
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            element.style.top = (element.offsetTop - pos2) + "px";
            element.style.left = (element.offsetLeft - pos1) + "px";
            element.style.bottom = 'auto';
            element.style.right = 'auto';
        }

        function closeDragElement() {
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }

    // Error Handlers
    function setupErrorHandlers() {
        window.addEventListener('error', (event) => {
            const error = {
                type: 'error',
                message: event.message,
                file: event.filename?.split('/').pop() || 'unknown',
                line: event.lineno,
                column: event.colno,
                stack: event.error?.stack,
                timestamp: new Date().toISOString(),
                breadcrumbs: [...state.breadcrumbs]
            };

            addError(error);
        });

        window.addEventListener('unhandledrejection', (event) => {
            const error = {
                type: 'promise',
                message: event.reason?.message || String(event.reason),
                stack: event.reason?.stack,
                timestamp: new Date().toISOString(),
                breadcrumbs: [...state.breadcrumbs]
            };

            addError(error);
        });

        // Console Error Interceptor
        const originalError = console.error;
        console.error = function(...args) {
            const error = {
                type: 'console',
                message: args.join(' '),
                timestamp: new Date().toISOString(),
                breadcrumbs: [...state.breadcrumbs]
            };

            addError(error);
            originalError.apply(console, args);
        };
    }

    // Breadcrumb Tracking (User Actions)
    function setupBreadcrumbs() {
        // Click Tracking
        document.addEventListener('click', (e) => {
            const target = e.target;
            const text = target.textContent?.trim().slice(0, 30) || '';
            const selector = getElementSelector(target);

            addBreadcrumb({
                type: 'click',
                message: `Clicked: ${selector}${text ? ` "${text}"` : ''}`,
                timestamp: new Date().toISOString()
            });
        }, true);

        // Navigation
        let lastUrl = window.location.href;
        setInterval(() => {
            if (window.location.href !== lastUrl) {
                addBreadcrumb({
                    type: 'navigation',
                    message: `Navigated to: ${window.location.pathname}`,
                    timestamp: new Date().toISOString()
                });
                lastUrl = window.location.href;
            }
        }, 500);

        // Input Changes
        document.addEventListener('input', (e) => {
            const target = e.target;
            if (target.type === 'password') return; // Skip passwords

            const selector = getElementSelector(target);
            addBreadcrumb({
                type: 'input',
                message: `Input in: ${selector}`,
                timestamp: new Date().toISOString()
            });
        }, true);
    }

    // Network Monitoring
    function setupNetworkMonitoring() {
        // Fetch Interceptor
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            const url = args[0];
            const startTime = Date.now();

            return originalFetch.apply(this, args)
                .then(response => {
                    const duration = Date.now() - startTime;
                    addNetworkLog({
                        type: 'fetch',
                        url: typeof url === 'string' ? url : url.url,
                        status: response.status,
                        duration,
                        timestamp: new Date().toISOString()
                    });
                    return response;
                })
                .catch(error => {
                    addNetworkLog({
                        type: 'fetch',
                        url: typeof url === 'string' ? url : url.url,
                        status: 'error',
                        error: error.message,
                        timestamp: new Date().toISOString()
                    });
                    throw error;
                });
        };

        // XMLHttpRequest Interceptor
        const originalOpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function(method, url) {
            this._debugUrl = url;
            this._debugStartTime = Date.now();
            return originalOpen.apply(this, arguments);
        };

        const originalSend = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.send = function() {
            this.addEventListener('load', function() {
                const duration = Date.now() - this._debugStartTime;
                addNetworkLog({
                    type: 'xhr',
                    url: this._debugUrl,
                    status: this.status,
                    duration,
                    timestamp: new Date().toISOString()
                });
            });
            return originalSend.apply(this, arguments);
        };
    }

    // Keyboard Shortcuts
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Cmd/Ctrl + Shift + D → Toggle Panel
            if ((e.metaKey || e.ctrlKey) && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                toggle();
            }
        });
    }

    // Helper: Get Element Selector
    function getElementSelector(element) {
        if (element.id) return `#${element.id}`;
        if (element.className) return `.${element.className.split(' ')[0]}`;
        return element.tagName.toLowerCase();
    }

    // Add Error
    function addError(error) {
        state.errors.unshift(error);
        if (state.errors.length > state.maxErrors) {
            state.errors.pop();
        }

        // AUTO-SEND TO CLAUDE DEBUG SERVER (Background)
        sendErrorToServer(error);

        updateUI();
        show(); // Automatisch zeigen bei Error
    }

    // Send Error to Local Debug Server (for Claude MCP)
    async function sendErrorToServer(error) {
        try {
            // Detect project name from URL
            const project = detectProjectName();

            const response = await fetch('http://127.0.0.1:9854/error', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    ...error,
                    project,
                    url: window.location.href,
                    userAgent: navigator.userAgent,
                    viewport: `${window.innerWidth}x${window.innerHeight}`,
                    networkLogs: state.networkLogs.slice(-5) // Last 5 requests
                })
            });

            if (response.ok) {
                console.log('✅ Error sent to Claude Debug Server');
            }
        } catch (err) {
            // Silently fail if server not running (user might not have it started)
            console.log('ℹ️ Debug Server not running (errors only in browser)');
        }
    }

    // Detect project name from URL or path
    function detectProjectName() {
        const host = window.location.hostname;
        const path = window.location.pathname;

        // Try to extract from localhost path
        if (host === 'localhost' || host === '127.0.0.1') {
            // Example: http://localhost:3000/about → musikfuerfirmen (if port 3000)
            const port = window.location.port;
            const projectMap = {
                '3000': 'musikfürfirmen',
                '8080': 'kathrin-coaching',
                '5173': 'dasgrossedatebuch'
            };
            return projectMap[port] || 'localhost-' + port;
        }

        // Try domain
        return host.split('.')[0] || 'unknown';
    }

    // Add Breadcrumb
    function addBreadcrumb(breadcrumb) {
        state.breadcrumbs.push(breadcrumb);
        if (state.breadcrumbs.length > state.maxBreadcrumbs) {
            state.breadcrumbs.shift();
        }
        updateUI();
    }

    // Add Network Log
    function addNetworkLog(log) {
        state.networkLogs.push(log);
        if (state.networkLogs.length > 20) {
            state.networkLogs.shift();
        }
        updateUI();
    }

    // Update UI
    function updateUI() {
        const hasErrors = state.errors.length > 0;

        // Status
        const indicator = document.getElementById('debug-status-indicator');
        const statusText = document.getElementById('debug-status-text');
        if (indicator) {
            indicator.className = `debug-status-indicator ${hasErrors ? 'error' : 'ok'}`;
        }
        if (statusText) {
            statusText.textContent = hasErrors ?
                `${state.errors.length} Error${state.errors.length > 1 ? 's' : ''}` :
                'Keine Errors';
        }

        // Counts
        document.getElementById('debug-error-count').textContent = state.errors.length;
        document.getElementById('debug-action-count').textContent = state.breadcrumbs.length;
        document.getElementById('debug-network-count').textContent = state.networkLogs.length;

        // Error List
        const errorsContainer = document.getElementById('debug-errors');
        errorsContainer.innerHTML = state.errors.slice(0, 5).map(error => `
            <div class="debug-error-item">
                ${error.file ? `<div class="debug-error-file">${error.file}${error.line ? `:${error.line}` : ''}</div>` : ''}
                <div class="debug-error-message">${error.message}</div>
                <div class="debug-error-time">${new Date(error.timestamp).toLocaleTimeString('de-DE')}</div>
            </div>
        `).join('');
    }

    // Public API
    window.DebugHelper = {
        toggle() {
            const panel = document.getElementById('debug-helper-panel');
            panel.classList.toggle('visible');
            state.panelVisible = !state.panelVisible;
        },

        show() {
            const panel = document.getElementById('debug-helper-panel');
            panel.classList.add('visible');
            state.panelVisible = true;
        },

        hide() {
            const panel = document.getElementById('debug-helper-panel');
            panel.classList.remove('visible');
            state.panelVisible = false;
        },

        clear() {
            state.errors = [];
            state.breadcrumbs = [];
            state.networkLogs = [];
            updateUI();
        },

        copyForClaude() {
            const report = generateClaudeReport();
            navigator.clipboard.writeText(report).then(() => {
                alert('✅ Debug Report kopiert!\n\nJetzt zu Claude Code senden.');
            });
        },

        screenshot() {
            alert('📸 Screenshot-Feature kommt bald!\n\nAktuell: Nutze Cmd+Shift+4 (Mac) oder Snipping Tool (Windows)');
        },

        getState() {
            return { ...state };
        }
    };

    // Generate Claude-friendly Report
    function generateClaudeReport() {
        const latestError = state.errors[0];

        if (!latestError) {
            return 'Keine Errors zum Reporten.';
        }

        const report = `
=== DEBUG REPORT FÜR CLAUDE CODE ===

🔴 ERROR:
${latestError.message}

📁 FILE: ${latestError.file || 'unknown'}${latestError.line ? ':' + latestError.line : ''}

📚 STACK TRACE:
${latestError.stack || 'No stack trace available'}

🥖 USER ACTIONS (Breadcrumbs):
${state.breadcrumbs.slice(-10).map(b => `  ${new Date(b.timestamp).toLocaleTimeString('de-DE')} - ${b.message}`).join('\n')}

🌐 NETWORK REQUESTS:
${state.networkLogs.slice(-5).map(n => `  ${n.type.toUpperCase()} ${n.url} → ${n.status} (${n.duration || 0}ms)`).join('\n')}

ℹ️ CONTEXT:
- Browser: ${navigator.userAgent.split(' ').slice(-2).join(' ')}
- Page: ${window.location.pathname}
- Time: ${new Date(latestError.timestamp).toLocaleString('de-DE')}
- Total Errors: ${state.errors.length}

🎯 BITTE:
Analysiere den Error und zeige mir die betroffene Code-Stelle in der Datei.
Schlage eine Lösung vor.
`.trim();

        return report;
    }

    // Auto-Init
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Show/Hide Helper Functions
    function show() {
        window.DebugHelper.show();
    }

    function toggle() {
        window.DebugHelper.toggle();
    }

})();
