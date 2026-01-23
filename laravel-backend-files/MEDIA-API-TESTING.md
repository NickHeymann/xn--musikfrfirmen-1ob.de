# Media API Testing Guide

**Date:** 2026-01-19
**Endpoints:** Temporary Upload Workflow

---

## Overview

The Media API supports a two-step upload workflow:
1. **Upload to temporary storage** - preview before committing
2. **Commit to permanent storage** - or cancel and delete temp file

This prevents orphaned files if users cancel during editing.

---

## API Endpoints

### 1. Upload Temporary File

Upload file to temp storage for preview.

```bash
POST /api/media/upload-temp
Content-Type: multipart/form-data
```

**Request:**
```bash
curl -X POST http://localhost:8001/api/media/upload-temp \
  -F "file=@/path/to/image.jpg"
```

**Response (200 OK):**
```json
{
  "tempId": "abc123xyz456...",
  "tempUrl": "/storage/temp/abc123xyz456....jpg",
  "filename": "background.jpg",
  "size": 245678,
  "type": "image/jpeg"
}
```

**Response (422 Validation Error):**
```json
{
  "message": "The file field must be a file of type: jpg, jpeg, png, webp, mp4, webm.",
  "errors": {
    "file": ["The file field must be a file of type: jpg, jpeg, png, webp, mp4, webm."]
  }
}
```

**Supported Formats:**
- Images: `.jpg`, `.jpeg`, `.png`, `.webp`
- Videos: `.mp4`, `.webm`

**Max File Size:** 10MB

---

### 2. Commit Temporary File

Move temp file to permanent storage.

```bash
POST /api/media/commit-temp
Content-Type: application/json
```

**Request:**
```bash
curl -X POST http://localhost:8001/api/media/commit-temp \
  -H "Content-Type: application/json" \
  -d '{
    "tempId": "abc123xyz456...",
    "path": "hero"
  }'
```

**Request Body:**
```json
{
  "tempId": "abc123xyz456...",  // Required: from upload-temp response
  "path": "hero"                // Optional: hero, services, team, general (default: general)
}
```

**Response (200 OK):**
```json
{
  "url": "/storage/uploads/hero/abc123xyz456....jpg",
  "filename": "abc123xyz456....jpg"
}
```

**Response (404 Not Found):**
```json
{
  "error": "Temporary file not found"
}
```

---

### 3. Delete Temporary File

Delete temp file if user cancels.

```bash
DELETE /api/media/temp/{tempId}
```

**Request:**
```bash
curl -X DELETE http://localhost:8001/api/media/temp/abc123xyz456...
```

**Response (200 OK):**
```json
{
  "success": true
}
```

**Response (404 Not Found):**
```json
{
  "error": "Temporary file not found"
}
```

---

## Complete Workflow Example

### Step 1: Upload to Temp
```bash
# Upload image
curl -X POST http://localhost:8001/api/media/upload-temp \
  -F "file=@hero-background.jpg"

# Response
{
  "tempId": "xYz789aBc123dEf456gHi789jKl012mN",
  "tempUrl": "/storage/temp/xYz789aBc123dEf456gHi789jKl012mN.jpg",
  "filename": "hero-background.jpg",
  "size": 1245678,
  "type": "image/jpeg"
}
```

### Step 2: Preview (Frontend)
```typescript
// Frontend shows preview using tempUrl
<img src={`http://localhost:8001${tempUrl}`} alt="Preview" />
```

### Step 3a: Commit (User Saves)
```bash
# User clicks "Save"
curl -X POST http://localhost:8001/api/media/commit-temp \
  -H "Content-Type: application/json" \
  -d '{
    "tempId": "xYz789aBc123dEf456gHi789jKl012mN",
    "path": "hero"
  }'

# Response
{
  "url": "/storage/uploads/hero/xYz789aBc123dEf456gHi789jKl012mN.jpg",
  "filename": "xYz789aBc123dEf456gHi789jKl012mN.jpg"
}
```

### Step 3b: Delete (User Cancels)
```bash
# User clicks "Cancel"
curl -X DELETE http://localhost:8001/api/media/temp/xYz789aBc123dEf456gHi789jKl012mN

# Response
{
  "success": true
}
```

---

## File Storage Structure

```
storage/
├── app/
│   ├── temp/                           # Temporary uploads (preview)
│   │   └── xYz789aBc123....jpg
│   └── public/
│       ├── editor-images/              # Legacy uploads
│       │   └── timestamp-uniqid.webp
│       └── uploads/                    # Permanent uploads
│           ├── hero/
│           │   └── xYz789aBc123....jpg
│           ├── services/
│           ├── team/
│           └── general/
```

**Access via URL:**
- Temp: `http://localhost:8001/storage/temp/{filename}`
- Permanent: `http://localhost:8001/storage/uploads/{path}/{filename}`

---

## Testing Checklist

### ✅ Basic Upload
- [ ] Upload JPG image → Success
- [ ] Upload PNG image → Success
- [ ] Upload WebP image → Success
- [ ] Upload MP4 video → Success
- [ ] Upload WebM video → Success
- [ ] Upload unsupported format (e.g., `.txt`) → 422 Error
- [ ] Upload file >10MB → 422 Error

### ✅ Commit Workflow
- [ ] Commit to `hero` path → File in `storage/app/public/uploads/hero/`
- [ ] Commit to `services` path → File in `storage/app/public/uploads/services/`
- [ ] Commit to `team` path → File in `storage/app/public/uploads/team/`
- [ ] Commit without path → File in `storage/app/public/uploads/general/`
- [ ] Commit with invalid tempId → 404 Error
- [ ] Commit already committed file → 404 Error

### ✅ Delete Workflow
- [ ] Delete temp file → File removed from `storage/app/temp/`
- [ ] Delete with invalid tempId → 404 Error
- [ ] Delete already deleted file → 404 Error

### ✅ CORS
- [ ] Request from `http://localhost:3000` → Allowed
- [ ] Request from other origin → Blocked (if CORS strict)

---

## Common Issues

### Issue: "Temporary file not found" (404)

**Possible Causes:**
1. File was already committed
2. File was already deleted
3. Invalid tempId
4. File expired (if auto-cleanup enabled)

**Solution:**
- Check `storage/app/temp/` directory
- Verify tempId matches exactly

### Issue: "CORS error"

**Solution:**
```bash
# Check .env
CORS_ALLOWED_ORIGINS=http://localhost:3000

# Clear cache
php artisan config:clear
```

### Issue: "Storage link not found"

**Solution:**
```bash
# Create storage link
php artisan storage:link

# Create directories
mkdir -p storage/app/temp
mkdir -p storage/app/public/uploads/{hero,services,team,general}

# Set permissions
chmod -R 755 storage/app/temp
chmod -R 755 storage/app/public/uploads
```

### Issue: File uploaded but not accessible via URL

**Solution:**
```bash
# Check symlink
ls -la public/storage

# Should point to: ../storage/app/public
# If not:
rm public/storage
php artisan storage:link
```

---

## Integration with Frontend

### TypeScript Example

```typescript
// Upload temp file
async function uploadTempFile(file: File): Promise<TempUploadResponse> {
  const formData = new FormData();
  formData.append('file', file);

  const response = await fetch('http://localhost:8001/api/media/upload-temp', {
    method: 'POST',
    body: formData,
  });

  if (!response.ok) {
    throw new Error('Upload failed');
  }

  return await response.json();
}

// Commit temp file
async function commitTempFile(tempId: string, path?: string): Promise<CommitResponse> {
  const response = await fetch('http://localhost:8001/api/media/commit-temp', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ tempId, path }),
  });

  if (!response.ok) {
    throw new Error('Commit failed');
  }

  return await response.json();
}

// Delete temp file
async function deleteTempFile(tempId: string): Promise<void> {
  const response = await fetch(`http://localhost:8001/api/media/temp/${tempId}`, {
    method: 'DELETE',
  });

  if (!response.ok) {
    throw new Error('Delete failed');
  }
}
```

### Usage in Component

```typescript
// 1. User selects file
const handleFileSelect = async (file: File) => {
  const { tempId, tempUrl } = await uploadTempFile(file);
  setPreviewUrl(`http://localhost:8001${tempUrl}`);
  setTempId(tempId);
};

// 2. User clicks "Save"
const handleSave = async () => {
  const { url } = await commitTempFile(tempId, 'hero');
  onSave(url); // Save permanent URL to block props
};

// 3. User clicks "Cancel"
const handleCancel = async () => {
  await deleteTempFile(tempId);
  setPreviewUrl(null);
};
```

---

## Next Steps

1. **Test all endpoints** with curl (see examples above)
2. **Integrate with MediaUploader component** (frontend)
3. **Add cleanup job** for old temp files (optional)
4. **Enable authentication** if needed (add middleware)

---

**Created:** 2026-01-19
**Status:** Ready for testing
**Laravel Version:** 11+
**PHP Version:** 8.2+
