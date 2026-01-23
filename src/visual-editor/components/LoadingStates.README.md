# Loading States - Spinner & SkeletonLoader

Apple-quality loading indicators for async operations in the visual editor.

## Components

### 1. Spinner

Circular loading indicator for async operations.

**Props:**

- `size?: 'sm' | 'md' | 'lg'` - Size: 16px, 24px, 32px (default: `md`)
- `color?: string` - Color (default: `#007AFF`)
- `className?: string` - Additional CSS classes

**Usage:**

```tsx
import { Spinner } from '@/visual-editor/components/Spinner'

// Small spinner in button
<button disabled={isLoading}>
  {isLoading ? (
    <>
      <Spinner size="sm" />
      Saving...
    </>
  ) : (
    'Save'
  )}
</button>

// Medium spinner (standalone)
<div style={{ display: 'flex', justifyContent: 'center' }}>
  <Spinner size="md" />
</div>

// Large spinner with custom color
<Spinner size="lg" color="#FF3B30" />
```

### 2. SkeletonLoader

Placeholder component with shimmer animation for content loading.

**Props:**

- `width?: string | number` - CSS width (default: `'100%'`)
- `height?: string | number` - CSS height (default: `200`)
- `rounded?: boolean` - Rounded corners (default: `false`)
- `animate?: boolean` - Shimmer animation (default: `true`)
- `className?: string` - Additional CSS classes

**Usage:**

```tsx
import { SkeletonLoader } from '@/visual-editor/components/SkeletonLoader'

// Hero block loading
<SkeletonLoader height={400} rounded animate />

// Service card loading
<SkeletonLoader height={300} rounded animate />

// Multiple skeletons (page load)
<div>
  <SkeletonLoader height={400} rounded animate />
  <div style={{ marginTop: '20px' }}>
    <SkeletonLoader height={300} rounded animate />
  </div>
  <div style={{ marginTop: '20px' }}>
    <SkeletonLoader height={200} rounded animate />
  </div>
</div>
```

## Integration Points

### 1. Save Button (EditorSidebar)

**Location:** `src/visual-editor/sidebar/EditorSidebar.tsx`

```tsx
import { Spinner } from "../components/Spinner";

<button onClick={handleSave} disabled={isSaving} className="save-button">
  {isSaving ? (
    <>
      <Spinner size="sm" />
      Saving...
    </>
  ) : (
    <>
      <Save size={16} />
      Save
    </>
  )}
</button>;
```

**State:** `isSaving` from EditorContext

### 2. Page Load (EditMode)

**Location:** `src/visual-editor/modes/EditMode.tsx`

```tsx
import { SkeletonLoader } from "../components/SkeletonLoader";

{
  isLoading ? (
    <div className="skeleton-container">
      <SkeletonLoader height={400} rounded animate />
      <SkeletonLoader height={300} rounded animate />
      <SkeletonLoader height={200} rounded animate />
    </div>
  ) : (
    <PageContent blocks={blocks} />
  );
}
```

**State:** `isLoading` from EditorContext

### 3. Media Upload (Future Enhancement)

**Location:** `src/visual-editor/components/MediaUploader.tsx`

```tsx
{
  isUploading ? (
    <div className="upload-progress">
      <Spinner size="md" />
      <span>Uploading {uploadProgress}%...</span>
    </div>
  ) : (
    <input type="file" />
  );
}
```

**State:** Local `isUploading` state

## CSS Classes

### Spinner Styles

```css
/* src/visual-editor/styles/apple-editor.css */

.spinner {
  border: 2px solid rgba(0, 122, 255, 0.2);
  border-top-color: #007aff;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
  display: inline-block;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
```

### Skeleton Styles

```css
.skeleton {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  border-radius: 4px;
}

.skeleton.skeleton-animate {
  animation: shimmer 1.5s ease-in-out infinite;
}

.skeleton.skeleton-rounded {
  border-radius: 8px;
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}
```

## Accessibility

### Spinner

- `role="status"` - Indicates loading state
- `aria-label="Loading"` - Screen reader text

```tsx
<div role="status" aria-label="Loading" className="spinner" />
```

### SkeletonLoader

- `aria-busy="true"` - Indicates content is loading
- `aria-label="Loading content"` - Screen reader text

```tsx
<div aria-busy="true" aria-label="Loading content" className="skeleton" />
```

## Performance

### CSS Animations (GPU-Accelerated)

- Uses `transform` for 60fps animations
- `will-change: transform` for smooth rendering
- No JavaScript animation loops

### Debounced Preview

The editor uses debounced blocks to avoid rapid re-renders:

```tsx
const [debouncedBlocks] = useDebounce(blocks, 300);
```

This prevents flickering while typing.

## Dark Mode Support

```css
@media (prefers-color-scheme: dark) {
  .skeleton {
    background: linear-gradient(90deg, #2c2c2e 25%, #3a3a3c 50%, #2c2c2e 75%);
  }
}
```

## Examples

### Save Flow

```
User clicks "Save"
→ setIsSaving(true)
→ Show spinner in button
→ Disable button
→ API call (POST /api/pages/[slug])
→ setIsSaving(false)
→ Show success toast
→ Button returns to normal
```

### Page Load Flow

```
User navigates to /admin/editor/[slug]
→ setIsLoading(true)
→ Show skeleton loaders
→ Fetch page data (GET /api/pages/[slug])
→ setIsLoading(false)
→ Smooth transition to content
```

### Media Upload Flow (Future)

```
User selects file
→ setIsUploading(true)
→ Show spinner + progress
→ Upload to storage (POST /api/media)
→ setIsUploading(false)
→ Show preview
```

## Testing

### Visual Testing

```bash
npm run dev
# Navigate to /admin/editor/[slug]
# Test loading states:
# 1. Click Save → spinner appears
# 2. Refresh page → skeletons appear
# 3. Type in editor → preview updates smoothly
```

### Performance Testing

```bash
# Check for 60fps animations
# Open Chrome DevTools → Performance
# Record interaction → verify smooth rendering
```

## Troubleshooting

### Spinner Not Spinning

**Problem:** Spinner appears but doesn't rotate

**Solution:** Ensure CSS is loaded:

```tsx
import "@/visual-editor/styles/apple-editor.css";
```

### Skeleton Not Shimmering

**Problem:** Skeleton appears but no animation

**Solution:** Add `animate` prop:

```tsx
<SkeletonLoader animate />
```

### Build Errors

**Problem:** TypeScript errors about missing props

**Solution:** Check EditorState interface:

```tsx
// src/visual-editor/types.ts
export interface EditorState {
  // ... other props
  isSaving: boolean;
  isLoading: boolean;
}
```

## Future Enhancements

### Upload Progress

```tsx
interface UploadState {
  isUploading: boolean;
  progress: number; // 0-100
}

<ProgressBar value={progress} />;
```

### Optimistic UI

```tsx
// Save optimistically
saveDraft();
showToast("success", "Saved!")
  // If fails, revert
  .catch(() => {
    revertChanges();
    showToast("error", "Failed to save");
  });
```

### Skeleton Matching Layout

```tsx
// Different skeletons per block type
{
  blockType === "Hero" && <HeroSkeleton />;
}
{
  blockType === "ServiceCards" && <ServiceCardsSkeleton />;
}
```

## Resources

- [Apple HIG - Progress Indicators](https://developer.apple.com/design/human-interface-guidelines/progress-indicators)
- [CSS-Tricks - Skeleton Screens](https://css-tricks.com/building-skeleton-screens-css-custom-properties/)
- [Web.dev - Loading Performance](https://web.dev/articles/optimize-cls)
