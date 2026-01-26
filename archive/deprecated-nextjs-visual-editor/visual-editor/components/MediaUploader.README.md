# MediaUploader Component

A reusable media upload component with preview capability, following Apple's design system.

## Features

- **Image & Video Support**: Upload both images and videos with type-specific previews
- **File Validation**: Automatic size and type validation with clear error messages
- **Drag & Drop**: Intuitive drag-and-drop support for better UX
- **Preview**: Shows current media with thumbnail (images) or icon (videos)
- **Change/Remove**: Easy actions to update or clear media
- **Apple Design**: Follows the established Apple-quality design system
- **Accessible**: Proper ARIA labels and keyboard navigation

## Installation

The component is located at `src/visual-editor/components/MediaUploader.tsx` and uses:

- `lucide-react` for icons
- Apple editor CSS from `src/visual-editor/styles/apple-editor.css`

## Usage

### Basic Image Upload

```tsx
import { MediaUploader } from "@/visual-editor/components";

function MyComponent() {
  const [imageUrl, setImageUrl] = useState<string | undefined>();

  const handleImageChange = (file: File | null) => {
    if (file) {
      // Upload to server and get URL
      const url = await uploadToServer(file);
      setImageUrl(url);
    } else {
      setImageUrl(undefined);
    }
  };

  return (
    <MediaUploader
      label="Profile Image"
      value={imageUrl}
      onChange={handleImageChange}
      accept="image/*"
      maxSizeMB={5}
      type="image"
    />
  );
}
```

### Video Upload

```tsx
<MediaUploader
  label="Background Video"
  value={videoUrl}
  onChange={handleVideoChange}
  accept="video/*"
  maxSizeMB={50}
  type="video"
/>
```

### Specific File Types

```tsx
<MediaUploader
  label="Thumbnail (JPG/PNG only)"
  value={imageUrl}
  onChange={handleImageChange}
  accept="image/jpeg, image/png"
  maxSizeMB={2}
  type="image"
/>
```

## Props

| Prop        | Type                           | Required | Default     | Description                               |
| ----------- | ------------------------------ | -------- | ----------- | ----------------------------------------- |
| `label`     | `string`                       | Yes      | -           | Label text displayed above the uploader   |
| `value`     | `string`                       | No       | `undefined` | Current media URL for preview             |
| `onChange`  | `(file: File \| null) => void` | Yes      | -           | Callback when file is selected or removed |
| `accept`    | `string`                       | No       | `'image/*'` | Accepted file types (MIME types)          |
| `maxSizeMB` | `number`                       | No       | `10`        | Maximum file size in megabytes            |
| `type`      | `'image' \| 'video'`           | No       | `'image'`   | Media type for different preview styles   |

## File Validation

The component validates files on two criteria:

1. **File Type**: Checks against the `accept` prop
   - Supports wildcards (e.g., `image/*`)
   - Supports specific MIME types (e.g., `image/jpeg, image/png`)

2. **File Size**: Checks against the `maxSizeMB` prop
   - Displays error if file exceeds limit

## Error Handling

Validation errors are displayed below the upload area with:

- Red text color (#FF3B30)
- Subtle background (rgba(255, 59, 48, 0.1))
- Left border accent
- Clear error messages

Common error messages:

- "Invalid file type. Accepted: image/\*"
- "File too large. Maximum size: 10MB"

## Styling

All styles are in `src/visual-editor/styles/apple-editor.css` under the "Media Uploader Component" section.

### CSS Classes

- `.media-uploader-container` - Main wrapper
- `.media-uploader-label` - Label text
- `.media-upload-zone` - Drag-and-drop upload area
- `.media-preview-container` - Preview wrapper
- `.media-preview-image-wrapper` - Image preview container
- `.media-preview-video-info` - Video preview container
- `.media-change-button` - Change media button
- `.media-remove-button` - Remove media button
- `.media-upload-error` - Error message container

### Customization

You can customize colors and spacing by modifying CSS variables in `apple-editor.css`:

```css
:root {
  --apple-blue: #007aff;
  --neutral-200: #e5e5ea;
  --space-3: 0.75rem;
  /* ... */
}
```

## Drag & Drop Behavior

1. **Drag Enter**: Border changes to blue, background becomes subtle blue
2. **Drag Over**: Maintains drag state
3. **Drag Leave**: Resets to default state
4. **Drop**: Validates and processes the file

Visual feedback:

- Dashed border becomes solid blue
- Background changes to `var(--apple-blue-subtle)`
- Icon color changes to blue
- Slight scale transformation (1.02)

## Accessibility

- Hidden file input with proper ARIA label
- Keyboard accessible (click buttons to open file picker)
- Clear visual feedback for all states
- Descriptive error messages
- Alt text for preview images

## Integration with Laravel Backend

The component handles local file selection only. For server upload, wrap the `onChange` callback:

```tsx
const handleImageChange = async (file: File | null) => {
  if (!file) {
    setImageUrl(undefined);
    return;
  }

  const formData = new FormData();
  formData.append("file", file);

  try {
    const response = await fetch("/api/upload", {
      method: "POST",
      body: formData,
    });
    const { url } = await response.json();
    setImageUrl(url);
  } catch (error) {
    console.error("Upload failed:", error);
  }
};
```

## Design Principles

Following Apple's design system:

- **Minimal**: Clean interface without clutter
- **Subtle Hover States**: 0.15s transitions
- **Blue Accent**: #007AFF for primary actions
- **Gray Borders**: #E5E5EA for neutral states
- **Clear Hierarchy**: Label → Upload/Preview → Actions
- **Feedback**: Immediate visual response to interactions

## Browser Compatibility

Supports all modern browsers:

- Chrome/Edge 90+
- Firefox 88+
- Safari 14+

Uses standard HTML5 File API and drag-and-drop events.

## Examples

See `MediaUploader.example.tsx` for complete usage examples:

1. Simple image upload
2. Video upload
3. Specific file types (JPG/PNG only)
4. Server upload integration
5. Within a block editor form

## Future Enhancements

Potential improvements for Task 16:

- Image cropping/resizing before upload
- Upload progress indicator
- Multiple file upload support
- Image optimization (compression, format conversion)
- Integration with media library
