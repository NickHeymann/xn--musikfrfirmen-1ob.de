# Inline Editing Visual Editor - Complete Design

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Add professional inline text and image editing to the visual editor with TipTap-powered rich text formatting, drag-and-drop image replacement, and advanced image/video adjustments.

**Architecture:** Dual-mode system (selection vs edit mode) with TipTap for rich text, hybrid CSS/server processing for image adjustments, and comprehensive state management.

**Tech Stack:**
- TipTap 2.x (rich text editing)
- React 19 + Next.js 16
- Laravel 12 API (image processing with Intervention Image)
- Framer Motion (animations)
- dnd-kit (already in use for block drag-drop)

---

## Core Features Summary

### 1. Rich Text Inline Editing
- Double-click any text to edit inline with TipTap editor
- Floating bubble menu with formatting: **Bold**, *Italic*, Link
- Per-field formatting controls (headings get bold/italic, buttons get plain text only)
- Escape or click outside to save
- Edit mode locks block (no drag/delete while editing)

### 2. Image Editing & Upload
- Click image → "Edit image" overlay appears
- Drag-and-drop file onto image OR click to open file picker
- Real-time adjustments: exposure, contrast, highlights, shadows, blacks, opacity
- Hybrid approach: CSS filters for preview, server processing for final delivery
- Automatic responsive image generation (WebP + JPEG fallback, multiple sizes)

### 3. Video Fallback System
- Video poster/fallback image selection
- Three options: auto-generated thumbnail, custom upload, or select from video frames
- Frame scrubber to pick specific moment as poster
- Mobile-friendly (poster always visible until user plays)

### 4. State Management
- EditorContext extended with `editingPath` and `editMode` states
- Clear mode transitions: IDLE → SELECT → EDIT
- Undo/redo system: TipTap handles text, custom stack for block operations
- Support for Cmd+Z (undo) and Cmd+Y / Cmd+Shift+Z (redo)

### 5. Advanced Features
- Auto-save with 2-second debounce
- Keyboard shortcuts (Cmd+S save, Escape exit edit, Delete remove block)
- Mobile responsive (touch interactions, bottom sheet UI)
- Accessibility (WCAG 2.1 AA, screen reader support)
- SEO tools integration (meta fields, readability scoring)
- Version history with visual diff
- Multi-user conflict prevention (page locking)

---

## Architecture Overview

```
Visual Editor
├── EditorCanvas (full-width preview)
│   ├── SortableBlock (selection + drag)
│   │   ├── EditableWrapper (double-click detection)
│   │   │   ├── Component (Hero, ServiceCards, etc.)
│   │   │   │   ├── EditableText (TipTap-powered)
│   │   │   │   └── EditableImage (upload + adjustments)
│   └── EmptyState
├── ComponentPalette (left sidebar)
├── PropertiesPanel (right sidebar)
│   └── ImageAdjustmentModal (when editing image)
└── EditorToolbar (top bar with save/undo/redo)
```

### State Flow
```
User double-clicks text
  → enterEditMode(blockId, propPath)
  → EditorContext.editingPath = "hero-1.heading"
  → EditorContext.editMode = 'edit'
  → TipTap editor activates
  → Drag handles hidden
  → User edits, presses Enter/Escape
  → updateBlock(blockId, { heading: newValue })
  → exitEditMode()
  → EditorContext.editMode = 'select'
```

---

## Component Integration Pattern

### EditableText Component
Wraps any text element to make it inline-editable:

**Props:**
- `value` - Current text content (string or HTML)
- `onChange` - Callback with new value
- `path` - Property path (e.g., "heading", "services.0.title")
- `blockId` - Parent block ID
- `extensions` - TipTap extensions to enable (['bold', 'italic', 'link'])
- `as` - HTML element to render ('h1', 'p', 'span', 'button')
- `placeholder` - Placeholder text when empty

**Behavior:**
- Renders as specified HTML element with current value
- Double-click → becomes contentEditable, shows TipTap bubble menu
- Formatting shortcuts: Cmd+B (bold), Cmd+I (italic), Cmd+K (link)
- Enter/blur → saves, Escape → cancels
- Blue outline when active

### EditableImage Component
Handles image replacement and editing:

**Props:**
- `src` - Current image URL
- `alt` - Alt text
- `onChange` - Callback with new image URL
- `blockId` - Parent block ID
- `aspectRatio` - Optional constraint ("16/9")
- `maxSize` - Max file size in MB (default: 5)

**Behavior:**
- Hover → semi-transparent overlay with upload icon
- Drag file onto image → upload + replace
- Click → "Edit image" button opens modal
- Modal tabs: "Upload" | "Adjustments" | "Fallback" (for videos)
- Adjustments: exposure, contrast, highlights, shadows, blacks, opacity (sliders)
- Real-time CSS filter preview, "Apply" sends to server for final processing

---

## Implementation Phases

### Phase 1: TipTap Integration & Basic Inline Editing
- Install TipTap dependencies (`@tiptap/react`, `@tiptap/starter-kit`, `@tiptap/extension-link`)
- Create `EditableText` component with double-click activation
- Implement bubble menu with bold, italic, link formatting
- Add edit mode state to EditorContext
- Test on Hero heading (simple single-field edit)

### Phase 2: Image Upload & Replacement
- Create `EditableImage` component with hover overlay
- Implement drag-and-drop file handling (HTML5 File API)
- Build Laravel API endpoint `/api/pages/media` for uploads
- Add file validation (type, size) client and server-side
- Implement progress indicator during upload
- Test on Hero background image

### Phase 3: Image Adjustments (Hybrid Approach)
- Build ImageAdjustmentModal with slider controls
- Implement CSS filter preview (real-time as user adjusts)
- Add "Apply" button that sends adjustments to Laravel
- Server-side processing with Intervention Image (exposure, contrast, etc.)
- Generate optimized outputs: WebP, JPEG, multiple sizes
- Store adjustment metadata with image
- "Reset to original" button reprocesses from source

### Phase 4: Video Fallback & Poster Selection
- Extend EditableImage for video support
- Add "Fallback image" tab to adjustment modal
- Implement video frame scrubber (canvas-based frame extraction)
- "Set as poster" captures current frame
- Upload custom poster option
- Store posterUrl alongside videoUrl in props

### Phase 5: Undo/Redo System
- Create history stack in EditorContext
- Capture snapshots for: block add/delete, reorder, prop updates
- Implement undo (Cmd+Z) and redo (Cmd+Y, Cmd+Shift+Z)
- TipTap handles text-level undo automatically
- Add undo/redo buttons to toolbar (enabled/disabled based on stack)

### Phase 6: Component Integration
- Integrate EditableText into Hero (heading, CTA text)
- Integrate EditableImage into Hero (background video)
- Integrate into ServiceCards (title, description, image per card)
- Integrate into ProcessSteps (heading, step titles/descriptions)
- Integrate into Footer (leave email/phone in properties panel - needs validation)
- Test all components with inline editing

### Phase 7: Polish & Advanced Features
- Add keyboard shortcuts (full list: Escape, Cmd+S, Delete, arrows)
- Implement auto-save with debounce (2 seconds)
- Add "Saving..." / "Saved" indicator to toolbar
- Mobile responsive adaptations (touch events, bottom sheet UI)
- Accessibility: ARIA labels, screen reader announcements, keyboard navigation
- Performance optimizations (memoization, virtual scrolling for large pages)

---

## Key Technical Decisions

### Why TipTap?
- Built on battle-tested ProseMirror
- Best React integration with hooks
- 100+ extensions available
- Better DX than Lexical (which needs more maturity)
- Supports bubble menus, slash commands, collaboration (future)

### Why Hybrid Image Processing?
- CSS filters give instant feedback (no server delay)
- Server processing ensures production quality
- Original always preserved for re-editing
- Best of both worlds: speed + quality

### Why Dual-Mode (Select vs Edit)?
- Prevents accidental operations while editing
- Clear visual state (blue ring vs text cursor)
- Matches industry standards (Figma, Canva, Webflow)
- Easy to implement Escape/click-outside exit

### Why Double-Click to Edit?
- Familiar pattern (not accidental)
- Single click selects block (needed for drag/delete)
- Mobile equivalent: double-tap

---

## File Structure

```
src/
├── visual-editor/
│   ├── components/
│   │   ├── EditorCanvas.tsx (existing, updated)
│   │   ├── SortableBlock.tsx (existing, updated for edit mode)
│   │   ├── EditableText.tsx (NEW)
│   │   ├── EditableImage.tsx (NEW)
│   │   ├── ImageAdjustmentModal.tsx (NEW)
│   │   └── VideoFrameScrubber.tsx (NEW)
│   ├── context/
│   │   └── EditorContext.tsx (existing, extended with editingPath/editMode)
│   ├── hooks/
│   │   ├── useEditableText.ts (NEW)
│   │   ├── useImageUpload.ts (NEW)
│   │   └── useUndoRedo.ts (NEW)
│   └── utils/
│       ├── image-filters.ts (NEW - CSS filter generation)
│       └── video-frame-extractor.ts (NEW - canvas-based frame extraction)
├── components/
│   ├── Hero.tsx (updated with EditableText/EditableImage)
│   ├── ServiceCards.tsx (updated)
│   ├── ProcessSteps.tsx (updated)
│   └── Footer.tsx (minimal updates)
└── lib/
    └── api/
        └── client.ts (add media upload methods)

tall-stack/ (Laravel)
├── app/
│   └── Http/
│       └── Controllers/
│           └── MediaController.php (NEW - handles uploads + processing)
├── config/
│   └── filesystems.php (configure storage)
└── routes/
    └── api.php (add /pages/media endpoint)
```

---

## API Endpoints

### POST /api/pages/media
Upload and process image/video.

**Request:** `multipart/form-data`
```
file: File (required)
adjustments: JSON (optional) { exposure: 20, contrast: -10, ... }
```

**Response:**
```json
{
  "url": "/storage/images/abc123.jpg",
  "thumbnail": "/storage/images/abc123-thumb.jpg",
  "sizes": {
    "1x": "/storage/images/abc123-1x.webp",
    "2x": "/storage/images/abc123-2x.webp"
  },
  "original": "/storage/images/abc123-original.jpg"
}
```

### POST /api/pages/media/adjust
Apply adjustments to existing image.

**Request:**
```json
{
  "url": "/storage/images/abc123-original.jpg",
  "adjustments": {
    "exposure": 20,
    "contrast": -10,
    "highlights": 5,
    "shadows": 10,
    "blacks": 0,
    "opacity": 100
  }
}
```

**Response:** Same as upload response with new processed URLs.

---

## Testing Strategy

### Unit Tests
- EditableText component (mount, edit, save, cancel)
- EditableImage component (upload, drag-drop, adjustments)
- EditorContext (state transitions, undo/redo)
- Image filter utilities (CSS generation, validation)

### Integration Tests
- Full edit flow: double-click → edit → save → verify update
- Image upload flow: drag file → upload → replace → verify
- Undo/redo: make changes → undo → verify state restored
- Mode transitions: select → edit → select (with all triggers)

### E2E Tests (Playwright)
- Complete editing session: load page → add block → edit text → upload image → publish
- Mobile editing: tap interactions, bottom sheet UI
- Keyboard navigation: all operations accessible via keyboard
- Error scenarios: network failure during upload, large file rejection

---

## Performance Targets

- **Initial load:** Editor JS bundle <200KB gzipped
- **TipTap lazy load:** <50ms to activate editor on double-click
- **Image upload:** Progress indicator updates every 100ms
- **Auto-save latency:** <500ms from edit to server confirmation
- **Undo/redo:** <16ms (instant, no flicker)
- **CSS filter preview:** 60fps during slider adjustment

---

## Security Considerations

- **XSS prevention:** TipTap sanitizes all HTML output
- **File upload validation:** MIME type + magic number check (not just extension)
- **File size limits:** Client (5MB) and server (strict enforcement)
- **Path traversal:** Sanitize filenames before storage
- **CSRF protection:** Laravel tokens on all mutations
- **SQL injection:** Eloquent parameterized queries only
- **Content Security Policy:** No inline scripts, only contentEditable

---

## Accessibility Requirements

- **Keyboard navigation:** All edit operations accessible without mouse
- **Screen reader:** Announce mode changes ("Entering edit mode for heading")
- **Focus management:** Auto-focus text field when entering edit mode
- **ARIA labels:** All interactive elements properly labeled
- **Color contrast:** WCAG AAA (7:1 ratio) for editor UI
- **Alt text validation:** Warn if image published without alt text

---

## Mobile Adaptations

- **Touch interactions:** Double-tap to edit (not double-click)
- **Toolbar positioning:** Floating above keyboard (not blocked)
- **Image upload:** "Take photo" vs "Choose from library" options
- **No drag handles:** Up/down arrow buttons instead
- **Simplified formatting:** Only bold, italic, link (no advanced options)
- **Bottom sheet UI:** Properties panel as bottom sheet, not sidebar

---

## Future Enhancements (Not MVP)

- Real-time collaboration (TipTap Collaboration extension + Yjs)
- Slash commands for quick block insertion
- AI-powered text improvements (grammar, tone, SEO)
- Advanced image editing (crop, rotate, filters beyond exposure/contrast)
- Video trimming and editing
- Animation controls for block transitions
- Conditional content (show/hide based on rules)
- A/B testing variants

---

## Success Criteria

✅ **User can double-click any heading and edit inline with formatting**
✅ **User can drag an image onto existing image to replace it**
✅ **User can adjust image exposure/contrast with real-time preview**
✅ **User can select video fallback poster from video frames**
✅ **Auto-save works reliably (no data loss scenarios)**
✅ **Undo/redo works for all operations**
✅ **Keyboard shortcuts work (Escape, Cmd+Z, Cmd+Y, Cmd+S)**
✅ **Mobile users can edit text and upload images (touch-friendly)**
✅ **All operations accessible via keyboard (no mouse-only features)**
✅ **Published pages have optimized images (WebP, responsive sizes)**

---

**Design Status:** ✅ Complete and validated
**Next Step:** Use superpowers:executing-plans or superpowers:subagent-driven-development to implement
**Estimated Complexity:** High (7 major phases, new dependencies, full-stack feature)
**Estimated Timeline:** 2-3 weeks for full implementation + testing
