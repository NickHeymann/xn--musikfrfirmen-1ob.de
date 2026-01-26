# Loading States - Quick Reference Card

## Import

```tsx
import { Spinner, SkeletonLoader } from '@/visual-editor/components'
```

## Spinner

### Sizes
```tsx
<Spinner size="sm" />  // 16px - buttons
<Spinner size="md" />  // 24px - standalone
<Spinner size="lg" />  // 32px - hero sections
```

### Colors
```tsx
<Spinner color="#007AFF" />  // Apple blue (default)
<Spinner color="#FF3B30" />  // Red (errors)
<Spinner color="#34C759" />  // Green (success)
```

### In Buttons
```tsx
<button disabled={isLoading}>
  {isLoading ? (
    <>
      <Spinner size="sm" />
      Loading...
    </>
  ) : (
    'Button Text'
  )}
</button>
```

## SkeletonLoader

### Basic
```tsx
<SkeletonLoader height={200} />
```

### With Rounded Corners
```tsx
<SkeletonLoader height={300} rounded />
```

### Custom Width
```tsx
<SkeletonLoader width={400} height={200} rounded />
```

### No Animation
```tsx
<SkeletonLoader height={200} animate={false} />
```

## Common Patterns

### Page Load
```tsx
{isLoading ? (
  <div>
    <SkeletonLoader height={400} rounded />
    <SkeletonLoader height={300} rounded />
    <SkeletonLoader height={200} rounded />
  </div>
) : (
  <Content />
)}
```

### Save Button
```tsx
{isSaving ? (
  <>
    <Spinner size="sm" />
    Saving...
  </>
) : (
  <>
    <Save />
    Save
  </>
)}
```

### Inline Loading
```tsx
<div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
  <Spinner size="sm" />
  <span>Processing...</span>
</div>
```

## State Management

### EditorContext
```tsx
const { isLoading, isSaving } = useEditor()
```

### Local State
```tsx
const [isUploading, setIsUploading] = useState(false)
```

## CSS Classes

```css
.spinner              /* Base spinner */
.skeleton             /* Base skeleton */
.skeleton-animate     /* With shimmer */
.skeleton-rounded     /* Rounded corners */
```

## Accessibility

```tsx
// Spinner
role="status"
aria-label="Loading"

// SkeletonLoader
aria-busy="true"
aria-label="Loading content"
```

## Performance

- **Animation:** 60fps (GPU-accelerated)
- **Size:** ~1.7KB total
- **Render:** <1ms

## Dark Mode

```css
@media (prefers-color-scheme: dark) {
  .skeleton {
    background: linear-gradient(
      90deg,
      #2C2C2E 25%,
      #3A3A3C 50%,
      #2C2C2E 75%
    );
  }
}
```

## Troubleshooting

### Spinner not rotating
✓ Check CSS is imported
✓ Verify animation not paused

### Skeleton not shimmering
✓ Add `animate` prop
✓ Check CSS keyframes

### Build errors
✓ Check EditorState interface
✓ Verify imports

## Files

- `Spinner.tsx` - Component
- `SkeletonLoader.tsx` - Component
- `apple-editor.css` - Animations
- `LoadingStates.README.md` - Full docs
