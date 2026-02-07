#!/bin/bash

# Start Laravel API for Visual Editor
# This script ensures Laravel starts from the correct directory

# Get the directory where this script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Change to the script directory (tall-stack)
cd "$SCRIPT_DIR" || exit 1

# Verify we're in the right place
if [ ! -f "artisan" ]; then
    echo "ERROR: artisan file not found in $SCRIPT_DIR"
    echo "This script must be in the tall-stack directory"
    exit 1
fi

# Kill any existing Laravel servers
echo "Stopping existing Laravel servers..."
lsof -ti:8000 | xargs kill -9 2>/dev/null || true
sleep 1

# Start Laravel server
echo "Starting Laravel API on port 8000..."
echo "Directory: $SCRIPT_DIR"
php artisan serve --host=0.0.0.0 --port=8000 > /tmp/laravel-api.log 2>&1 &

# Wait for server to start
sleep 3

# Test if server is running
if curl -s http://localhost:8000/api/pages >/dev/null 2>&1; then
    echo "✅ Laravel API started successfully!"
    echo "   URL: http://localhost:8000"
    echo "   Logs: /tmp/laravel-api.log"
    echo ""
    echo "Test API:"
    curl -s http://localhost:8000/api/pages | head -c 200
    echo ""
else
    echo "❌ Laravel API failed to start"
    echo "Check logs: tail -50 /tmp/laravel-api.log"
    exit 1
fi
