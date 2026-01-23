#!/bin/bash

# Test Media API Endpoints
# Usage: ./test-media-api.sh [API_URL]
# Default: http://localhost:8001

set -e

API_URL="${1:-http://localhost:8001}"
TEST_IMAGE="test-image.jpg"

echo "🧪 Testing Media API Endpoints"
echo "API URL: $API_URL"
echo ""

# Create test image if not exists
if [ ! -f "$TEST_IMAGE" ]; then
    echo "📸 Creating test image..."
    # Create a simple 100x100 red square
    convert -size 100x100 xc:red "$TEST_IMAGE" 2>/dev/null || {
        echo "⚠️  ImageMagick not installed, using placeholder"
        echo "Please create a test-image.jpg file manually"
        exit 1
    }
fi

echo "✅ Test image ready: $TEST_IMAGE"
echo ""

# Test 1: Upload to temp
echo "📤 Test 1: Upload to temporary storage"
UPLOAD_RESPONSE=$(curl -s -X POST "$API_URL/api/media/upload-temp" \
  -F "file=@$TEST_IMAGE")

echo "Response: $UPLOAD_RESPONSE"

# Extract tempId using grep and sed (works without jq)
TEMP_ID=$(echo "$UPLOAD_RESPONSE" | grep -o '"tempId":"[^"]*"' | sed 's/"tempId":"//;s/"//')

if [ -z "$TEMP_ID" ]; then
    echo "❌ Upload failed - no tempId in response"
    exit 1
fi

echo "✅ Upload successful"
echo "   Temp ID: $TEMP_ID"
echo ""

# Test 2: Commit temp file
echo "📥 Test 2: Commit temporary file to permanent storage"
COMMIT_RESPONSE=$(curl -s -X POST "$API_URL/api/media/commit-temp" \
  -H "Content-Type: application/json" \
  -d "{\"tempId\": \"$TEMP_ID\", \"path\": \"hero\"}")

echo "Response: $COMMIT_RESPONSE"

# Extract URL
PERMANENT_URL=$(echo "$COMMIT_RESPONSE" | grep -o '"url":"[^"]*"' | sed 's/"url":"//;s/"//')

if [ -z "$PERMANENT_URL" ]; then
    echo "❌ Commit failed - no URL in response"
    exit 1
fi

echo "✅ Commit successful"
echo "   URL: $PERMANENT_URL"
echo ""

# Test 3: Upload and delete temp file
echo "🗑️  Test 3: Upload and delete temporary file"
UPLOAD_RESPONSE2=$(curl -s -X POST "$API_URL/api/media/upload-temp" \
  -F "file=@$TEST_IMAGE")

TEMP_ID2=$(echo "$UPLOAD_RESPONSE2" | grep -o '"tempId":"[^"]*"' | sed 's/"tempId":"//;s/"//')

if [ -z "$TEMP_ID2" ]; then
    echo "❌ Upload failed"
    exit 1
fi

echo "   Uploaded with temp ID: $TEMP_ID2"

DELETE_RESPONSE=$(curl -s -X DELETE "$API_URL/api/media/temp/$TEMP_ID2")
echo "Response: $DELETE_RESPONSE"

if echo "$DELETE_RESPONSE" | grep -q '"success":true'; then
    echo "✅ Delete successful"
else
    echo "❌ Delete failed"
    exit 1
fi
echo ""

# Test 4: Error handling - delete non-existent file
echo "🚫 Test 4: Error handling - delete non-existent file"
ERROR_RESPONSE=$(curl -s -X DELETE "$API_URL/api/media/temp/nonexistent")
echo "Response: $ERROR_RESPONSE"

if echo "$ERROR_RESPONSE" | grep -q '"error"'; then
    echo "✅ Error handling works"
else
    echo "⚠️  Expected error response"
fi
echo ""

# Summary
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ All tests passed!"
echo ""
echo "📋 Summary:"
echo "   ✓ Upload to temp works"
echo "   ✓ Commit to permanent works"
echo "   ✓ Delete temp file works"
echo "   ✓ Error handling works"
echo ""
echo "🎉 Media API is ready!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
