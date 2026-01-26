# Block Templates Feature

## Overview

Block Templates are pre-configured block layouts that users can insert with one click. This feature speeds up page creation by providing ready-to-use designs for common sections like hero sections, feature grids, testimonials, and CTAs.

## Features

### 1. Default Templates

The system includes 5 professionally designed templates:

- **Hero Section**: Full-width hero with headline, subheading, and CTA button
- **Feature Grid**: Three-column grid showcasing key features or services
- **Testimonials**: Customer testimonials with quotes and attribution
- **Call-to-Action Banner**: Eye-catching banner with prominent CTA
- **Two-Column Content**: Balanced two-column layout with text

### 2. Template Library

Access templates through the toolbar button (Layout icon) in the editor sidebar.

**Features:**
- Responsive grid layout showing all available templates
- Category filtering (Hero, Features, Testimonials, CTA, Custom)
- Search functionality by name or description
- Visual preview cards with preview images
- Template metadata (category, block count)

### 3. Template Preview

Click any template to see a detailed preview before inserting:

- Larger preview image
- Full description
- List of included blocks
- Metadata (created date, usage count for custom templates)
- Insert or Cancel buttons

### 4. Custom Templates

Save your current page layout as a reusable template:

**To create a custom template:**
1. Build your desired layout in the editor
2. Click the "Save as Template" button (FileDown icon) in the toolbar
3. Fill in the form:
   - **Name** (required, max 100 characters)
   - **Description** (optional, max 500 characters)
   - **Category** (Hero, Features, Testimonials, CTA, or Custom)
4. Click "Save Template"

**Custom template features:**
- Stored in browser localStorage
- Appear in Template Library under selected category
- Marked with "(Custom)" badge
- Delete button for removal
- Usage tracking

### 5. Template Insertion

When you insert a template:
- All blocks are added to your page
- Unique IDs are generated for each block
- Action is added to undo history
- Success toast notification appears
- You can immediately edit the inserted blocks

## User Interface

### Toolbar Buttons

Located in the editor sidebar header:

1. **Browse Templates** (Layout icon)
   - Opens Template Library modal
   - Shows all system and custom templates

2. **Save as Template** (FileDown icon)
   - Saves current page as custom template
   - Disabled when page is empty

### Template Library Modal

**Header:**
- Title and close button
- Search input field
- Category filter tabs

**Content:**
- Grid of template cards
- Each card shows:
  - Preview image (or gradient placeholder)
  - Template name
  - Description (truncated to 2 lines)
  - Category badge
  - Block count
  - Delete button (custom templates only)

**Footer:**
- Help text about clicking to preview

### Preview Modal

**Header:**
- Template name and category
- Close button

**Content:**
- Large preview image
- Full description
- Numbered list of included blocks
- Metadata section

**Footer:**
- Cancel and Insert Template buttons

## Technical Details

### File Structure

```
src/visual-editor/
├── types/
│   └── blockTemplate.ts          # TypeScript types
├── data/
│   └── blockTemplates.ts         # Default templates
├── components/
│   ├── TemplateLibrary.tsx       # Main library UI
│   ├── TemplatePreviewModal.tsx  # Preview modal
│   └── SaveTemplateModal.tsx     # Save template form
├── hooks/
│   └── useCustomTemplates.ts     # localStorage management
└── context/
    └── EditorContext.tsx         # insertTemplate function
```

### Data Storage

**System Templates:**
- Defined in `src/visual-editor/data/blockTemplates.ts`
- Exported as `BLOCK_TEMPLATES` constant
- Immutable, included with the application

**Custom Templates:**
- Stored in browser localStorage
- Key: `visual-editor-custom-templates`
- Format: JSON array of BlockTemplate objects
- Persists across browser sessions
- Isolated per browser/device

### TypeScript Types

```typescript
interface BlockTemplate {
  id: string;
  name: string;
  description: string;
  category: TemplateCategory;
  blocks: Block[];
  preview: string;
  metadata?: TemplateMetadata;
  isCustom?: boolean;
}

type TemplateCategory = 
  | 'hero' 
  | 'features' 
  | 'testimonials' 
  | 'cta' 
  | 'custom';
```

## Usage Examples

### Browse and Insert Template

1. Click the Layout icon in the toolbar
2. Browse or search for a template
3. Click a template card to preview
4. Click "Insert Template" in the preview modal
5. Template blocks are added to your page
6. Edit blocks as needed

### Create Custom Template

1. Design your layout with multiple blocks
2. Click the FileDown icon in the toolbar
3. Enter template details:
   - Name: "My Homepage Layout"
   - Description: "Custom homepage with hero and features"
   - Category: "Hero"
4. Click "Save Template"
5. Template appears in library under "Hero" category

### Delete Custom Template

1. Open Template Library
2. Find your custom template (has "(Custom)" badge)
3. Click the trash icon on the template card
4. Confirm deletion in the dialog
5. Template is removed from localStorage

## Keyboard Shortcuts

- **ESC**: Close Template Library or Preview Modal
- Template Library respects standard navigation (Tab, Enter, Arrow keys)

## Accessibility

- All modals are keyboard accessible
- ARIA labels on icon buttons
- Focus management when opening/closing modals
- Backdrop click to close modals
- Confirm dialogs for destructive actions

## Performance

- Templates are loaded on demand
- No network requests (localStorage only)
- Debounced search and filtering
- Efficient React rendering with useMemo

## Browser Compatibility

- **localStorage**: All modern browsers
- **Layout**: CSS Grid and Flexbox
- **Icons**: Lucide React icons (lightweight)
- **Responsive**: Mobile, tablet, and desktop

## Limitations

- Custom templates are stored per browser (not synced across devices)
- localStorage has ~5-10MB limit (hundreds of templates possible)
- Preview images for custom templates not auto-generated
- Cannot edit existing templates (delete and recreate instead)

## Future Enhancements

Potential improvements:

- Server-side template storage for cross-device sync
- Template categories management
- Template preview image generation
- Template versioning
- Template sharing/export
- Template analytics (most used, trending)
- Template tags for better organization

## Troubleshooting

### Templates not saving

**Check:**
- Browser localStorage is enabled
- Not in private/incognito mode
- localStorage quota not exceeded

**Fix:**
- Clear old custom templates
- Use different browser
- Check browser settings

### Templates not appearing

**Check:**
- Correct category filter selected
- Search query not too specific
- Custom templates loaded from localStorage

**Fix:**
- Select "All Templates" category
- Clear search query
- Reload page

### Preview not showing

**Check:**
- Template has preview image URL
- Image URL is accessible
- Network connectivity

**Fallback:**
- Gradient placeholder shows automatically

## Related Documentation

- [Visual Editor Architecture](../ARCHITECTURE.md)
- [Component Documentation](../README.md)
- [Testing Guide](../TESTING.md)

## Version History

- **v1.1.0** (2026-01-23): Initial block templates feature
  - 5 default templates
  - Template library UI
  - Custom template saving
  - Preview modal
  - localStorage persistence

---

**Last Updated:** 2026-01-23  
**Feature Status:** Production Ready ✅
