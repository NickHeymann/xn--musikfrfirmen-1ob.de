# Media API Quick Reference

**Last Updated:** 2026-01-19

---

## Endpoints

| Method | Endpoint | Purpose |
|--------|----------|---------|
| `POST` | `/api/media/upload-temp` | Upload to temp storage |
| `POST` | `/api/media/commit-temp` | Move temp → permanent |
| `DELETE` | `/api/media/temp/{tempId}` | Delete temp file |

---

## Request Examples

### Upload Temp
```bash
curl -X POST http://localhost:8001/api/media/upload-temp \
  -F "file=@image.jpg"
```

**Response:**
```json
{
  "tempId": "xYz789...",
  "tempUrl": "/storage/temp/xYz789....jpg",
  "filename": "image.jpg",
  "size": 245678,
  "type": "image/jpeg"
}
```

### Commit Temp
```bash
curl -X POST http://localhost:8001/api/media/commit-temp \
  -H "Content-Type: application/json" \
  -d '{"tempId": "xYz789...", "path": "hero"}'
```

**Response:**
```json
{
  "url": "/storage/uploads/hero/xYz789....jpg",
  "filename": "xYz789....jpg"
}
```

### Delete Temp
```bash
curl -X DELETE http://localhost:8001/api/media/temp/xYz789...
```

**Response:**
```json
{
  "success": true
}
```

---

## TypeScript Integration

```typescript
// Types
interface TempUploadResponse {
  tempId: string;
  tempUrl: string;
  filename: string;
  size: number;
  type: string;
}

interface CommitResponse {
  url: string;
  filename: string;
}

// Upload to temp
const uploadTemp = async (file: File) => {
  const formData = new FormData();
  formData.append('file', file);

  const res = await fetch('http://localhost:8001/api/media/upload-temp', {
    method: 'POST',
    body: formData,
  });

  return await res.json() as TempUploadResponse;
};

// Commit to permanent
const commitTemp = async (tempId: string, path?: string) => {
  const res = await fetch('http://localhost:8001/api/media/commit-temp', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ tempId, path }),
  });

  return await res.json() as CommitResponse;
};

// Delete temp
const deleteTemp = async (tempId: string) => {
  await fetch(`http://localhost:8001/api/media/temp/${tempId}`, {
    method: 'DELETE',
  });
};
```

---

## Validation Rules

- **Allowed types:** `jpg`, `jpeg`, `png`, `webp`, `mp4`, `webm`
- **Max size:** 10MB
- **Path values:** `hero`, `services`, `team`, `general` (default: `general`)

---

## Storage Paths

| Type | Path |
|------|------|
| Temp | `storage/app/temp/{tempId}.{ext}` |
| Permanent | `storage/app/public/uploads/{path}/{tempId}.{ext}` |
| Legacy | `storage/app/public/editor-images/{timestamp}-{uniqid}.webp` |

**Public URLs:**
- Temp: `http://localhost:8001/storage/temp/{tempId}.{ext}`
- Permanent: `http://localhost:8001/storage/uploads/{path}/{tempId}.{ext}`

---

## Error Responses

| Error | Status | Response |
|-------|--------|----------|
| Invalid file type | 422 | Validation error |
| File too large | 422 | Validation error |
| Temp file not found | 404 | `{ "error": "Temporary file not found" }` |
| Server error | 500 | `{ "error": "...", "message": "..." }` |

---

## Setup

```bash
# 1. Run setup script
cd laravel-backend-files
./setup-local.sh

# 2. Start server
php artisan serve --host=0.0.0.0 --port=8001

# 3. Test endpoints
./test-media-api.sh http://localhost:8001
```

---

## Full Documentation

- **Testing Guide:** `MEDIA-API-TESTING.md`
- **Implementation:** `IMPLEMENTATION-SUMMARY.md`
- **Deployment:** `DEPLOYMENT.md`
- **General Setup:** `README.md`
