# TipTap Visual Editor Implementation Design

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build a visual editor for musikfuerfirmen.de with Edit/View mode toggle, TipTap integration, hierarchical drag-and-drop, and optimistic updates.

**Architecture:** Separate Edit and View modes with sidebar editor (70% preview / 30% sidebar), block-specific editors, array inputs for animated content, media upload with preview capability.

**Tech Stack:** Next.js 16, React 19, TypeScript, TipTap, Tailwind CSS 4, Laravel 11 API, Framer Motion

---

## 1. Core Architecture: Edit/View Mode Toggle

**Problem with Inline Editing:**
- Conflicts with animations (pause/play)
- Scrolling issues with event handlers
- Button onClick vs contentEditable conflicts
- Complex DOM manipulation unreliable

**Solution: Two Separate Modes**

### View Mode
- Normal website behavior
- Animations work naturally
- All interactions functional
- Clean separation from editor

### Edit Mode
- Pause all animations with CSS
- Show "Edit" button overlay on block hover
- Click block → open sidebar editor
- Real-time preview on left (70%)
- Editor sidebar on right (30%)

**Benefits:**
- No animation conflicts (paused in edit mode)
- No scrolling issues (normal behavior)
- Clear editing workflow
- Better UX with real-time preview

---

## 2. Animation Text Editing

**Problem:** Animation cycles through words (Musik → Livebands → DJs → Technik) making inline editing impossible.

**Solution: Array Input in Sidebar**

When editing Hero block:
```typescript
sliderContent: ['Musik', 'Livebands', 'DJs', 'Technik']
```

User sees array input with drag handles:
```
Animated Words:
[≡] Musik              [×]
[≡] Livebands          [×]
[≡] DJs                [×]
[≡] Technik            [×]
[+ Add Word]
```

- Edit all words at once (no waiting for animation)
- Drag to reorder
- Add/remove words
- Save → animation uses new array

---

## 3. Block-Specific Editors

Each block type (Hero, ServiceCards, ProcessSteps, etc.) gets custom editor form:

**HeroEditor:**
- Text input: "Headline Prefix" (Deine)
- Array input: "Animated Words" (Musik, Livebands, DJs, Technik)
- Text input: "Headline Suffix" (für Firmenevents!)
- TipTap editor: "Features" (3 bullet points)
- Media uploader: "Background Video"
- Text input: "CTA Button Text"

**ServiceCardsEditor:**
- Array of cards with TipTap for each card:
  - Title
  - Description
  - Icon (dropdown)
- Drag handles for reordering

**ProcessStepsEditor:**
- Array of steps with:
  - Step number
  - Title
  - Description
- Add/remove steps
- Drag to reorder

---

## 4. Editor Sidebar (70/30 Split)

**Layout:**
```
┌─────────────────────────────┬───────────────────┐
│                             │                   │
│                             │   BLOCKS          │
│                             │   PROPERTIES      │
│     Live Preview            │                   │
│     (70%)                   │   [Editor Form]   │
│                             │   (30%)           │
│                             │                   │
│                             │   [Cancel][Save]  │
└─────────────────────────────┴───────────────────┘
```

**Why Sidebar?**
- More modern UX (Webflow, Framer, Figma pattern)
- Real-time preview as you type
- No context switching (modal covers preview)
- Better for multi-property editing
- Keyboard shortcuts work naturally

**Tabs:**
- **BLOCKS Tab:** Drag-and-drop page structure (reorder blocks)
- **PROPERTIES Tab:** Edit selected block with forms/TipTap

---

## 5. Technical Implementation

### File Structure
```
src/visual-editor/
├── modes/
│   ├── ViewMode.tsx           # Normal website view
│   └── EditMode.tsx           # Edit mode with sidebar
├── sidebar/
│   ├── EditorSidebar.tsx      # Main sidebar with tabs
│   ├── BlockList.tsx          # BLOCKS tab (drag-and-drop)
│   └── editors/
│       ├── HeroEditor.tsx     # Hero block form
│       ├── ServiceCardsEditor.tsx
│       ├── ProcessStepsEditor.tsx
│       └── ... (one per block type)
├── components/
│   ├── MediaUploader.tsx      # Image/video upload
│   ├── TipTapEditor.tsx       # Rich text wrapper
│   ├── ArrayInput.tsx         # For lists with drag
│   └── FormControls.tsx       # Inputs, buttons
└── styles/
    └── apple-editor.css       # Apple-quality design
```

### State Management

**Global State (React Context):**
```typescript
interface EditorState {
  mode: 'view' | 'edit'
  selectedBlockId: string | null
  blocks: Block[]
  hasUnsavedChanges: boolean
  isSaving: boolean
}
```

**Actions:**
```typescript
- setMode(mode)
- selectBlock(blockId)
- updateBlock(blockId, props)
- reorderBlocks(sourceIndex, targetIndex)
- saveDraft()
- publishChanges()
```

### API Integration

**Laravel Endpoints:**
```
GET  /api/pages/{slug}           → Load page data
PUT  /api/pages/{slug}           → Save draft
POST /api/pages/{slug}/publish   → Publish to live
POST /api/media/upload-temp      → Upload temp media
POST /api/media/commit-temp      → Move temp to permanent
```

**Optimistic Updates:**
1. User edits block in sidebar
2. Update local state immediately
3. Update preview (left side) in real-time
4. On "Save" → persist to API
5. On error → rollback to previous state
6. Show toast notification

---

## 6. Hierarchical Drag-and-Drop

### BLOCKS Tab (Page Structure)

Drag-and-drop for reordering entire blocks:
```
BLOCKS
━━━━━━━━━━━━━━━━━━━━━━
[≡] Hero
[≡] ServiceCards
[≡] ProcessSteps
[≡] TeamSection
[≡] FAQ
[≡] CTASection
```

- Drag handle [≡] on left
- Click block → switches to PROPERTIES tab
- Reorder → auto-save after drop

### PROPERTIES Tab (Block Items)

When editing block with items (ServiceCards, ProcessSteps), show nested drag-and-drop:
```
PROPERTIES: ServiceCards
━━━━━━━━━━━━━━━━━━━━━━
Services:
  [≡] Card 1: Livebands    [Edit][×]
  [≡] Card 2: DJs          [Edit][×]
  [≡] Card 3: Technik      [Edit][×]
  [+ Add Service]
```

- Drag to reorder items within block
- Click [Edit] → expand form for that item
- No need to drag text, just hierarchical object

---

## 7. Data Persistence & Error Handling

### Save Flow

**Manual Save (not auto-save):**
1. User makes changes in sidebar
2. Preview updates in real-time (optimistic)
3. "Unsaved Changes" indicator appears
4. User clicks "Save" button
5. API call persists changes
6. Success toast: "Changes saved"
7. Clear unsaved state

**Why Manual?**
- Clear save points
- User control over commits
- Can cancel/revert easily
- Prevents accidental overwrites

### Error Handling

**Network Errors:**
```typescript
try {
  await api.updatePage(slug, blocks)
  toast.success('Changes saved')
} catch (error) {
  // Rollback to previous state
  setBlocks(previousBlocks)
  toast.error('Failed to save: ' + error.message)
}
```

**Validation Errors:**
- Validate before save (required fields, formats)
- Show inline errors in sidebar
- Prevent save until fixed

**Concurrent Edits:**
- Add `updated_at` timestamp to API
- Before save, check if page was modified by someone else
- If yes, show conflict resolution dialog

---

## 8. User-Friendliness Enhancements

### Real-Time Preview
- Typing in sidebar → preview updates instantly (debounced 300ms)
- No need to save to see changes
- What-you-see-is-what-you-get

### Keyboard Shortcuts
```
Cmd+S     → Save changes
Cmd+Z     → Undo
Cmd+Shift+Z → Redo
Esc       → Cancel editing
Cmd+E     → Toggle edit mode
```

### Undo/Redo
- Track history of changes in memory
- 20 steps back
- Undo/redo buttons in toolbar
- Visual indicator when at start/end of history

### Inline Validation
- Show errors immediately (red border + message)
- Examples:
  - "Title required"
  - "Maximum 3 animated words"
  - "CTA text must be < 50 characters"

### Loading States
- Show spinner while saving
- Disable form during save
- Progress indicator for media uploads
- Skeleton loaders when switching blocks

### Accessibility
- Focus management (sidebar form → preview)
- Keyboard navigation (Tab, Arrow keys)
- Screen reader labels
- ARIA attributes

---

## 9. Media Upload Preview & Rollback

When users upload new media, they can preview before committing:

### Upload Flow

1. **Upload to Temporary Location**
   - User clicks "Change Image/Video" in block editor
   - File uploads to `POST /api/media/upload-temp`
   - Server stores in `storage/app/temp/` with unique ID
   - Returns: `{"tempUrl": "/storage/temp/abc123.jpg", "tempId": "abc123"}`

2. **Optimistic Preview**
   - Component state updates with tempUrl immediately
   - Left preview shows new media in real-time
   - Original media URL stored in `originalMediaUrl` state

3. **Preview Controls**
   ```typescript
   <MediaUploader
     currentUrl={backgroundVideo}
     originalUrl={originalBackgroundVideo}
     onUpload={(tempUrl, tempId) => {
       setBackgroundVideo(tempUrl)
       setTempMediaId(tempId)
       setHasUnsavedMedia(true)
     }}
     onRevert={() => {
       setBackgroundVideo(originalBackgroundVideo)
       api.delete(`/api/media/temp/${tempMediaId}`)
       setTempMediaId(null)
     }}
   />
   ```

4. **Save Behavior**
   - User clicks "Save" in block editor
   - If `tempMediaId` exists, move temp file to permanent
   - API: `POST /api/media/commit-temp` with tempId
   - Server moves file from `temp/` to `public/uploads/`
   - Returns permanent URL
   - Update block data with permanent URL

5. **Cancel/Revert**
   - User clicks "Cancel" or "Revert to Original"
   - Restore original media URL in preview
   - Delete temp file via API
   - Clear temp state

6. **Visual Indicators**
   - Badge: "Preview (not saved)" on changed media
   - Save button highlights when media has changes
   - Warning on navigation: "You have unsaved media changes"

**Benefits:**
- Safe preview without losing original
- Clear visual feedback
- Explicit commit action
- No accidental overwrites

---

## 10. Apple-Quality Design

### Visual Style
- Translucent glass effects (backdrop-filter: blur)
- Subtle shadows and borders
- Smooth animations (Framer Motion)
- Native macOS feel

### Colors
```css
--editor-background: rgba(255, 255, 255, 0.98);
--editor-border: #E5E5EA;
--editor-hover: #F5F5F7;
--editor-active: #007AFF;
--editor-text: #1D1D1F;
--editor-text-secondary: #86868B;
```

### Typography
- SF Pro Display for headings
- SF Pro Text for body
- System font stack fallback

### Interactions
- Hover states (150ms transition)
- Focus rings (blue outline)
- Smooth scroll
- Haptic feedback (vibration API)

### Animations
```typescript
// Block edit button fade-in
<motion.button
  initial={{ opacity: 0, y: -10 }}
  animate={{ opacity: 1, y: 0 }}
  exit={{ opacity: 0, y: -10 }}
  transition={{ duration: 0.15 }}
/>

// Sidebar slide-in
<motion.div
  initial={{ x: 420 }}
  animate={{ x: 0 }}
  exit={{ x: 420 }}
  transition={{ type: 'spring', damping: 25 }}
/>
```

---

## Implementation Checklist

### Phase 1: Core Architecture (2-3 hours)
- [ ] Create ViewMode.tsx (simple wrapper)
- [ ] Create EditMode.tsx (preview + sidebar layout)
- [ ] Add CSS for edit mode (pause animations, block hover)
- [ ] Implement mode toggle button
- [ ] Test switching between modes

### Phase 2: Sidebar Structure (2-3 hours)
- [ ] Create EditorSidebar.tsx with tabs
- [ ] Create BlockList.tsx (BLOCKS tab)
- [ ] Implement drag-and-drop with dnd-kit
- [ ] Add block selection logic
- [ ] Style sidebar with Apple design

### Phase 3: Block Editors (4-5 hours)
- [ ] Create HeroEditor.tsx (text inputs + array input)
- [ ] Create ArrayInput.tsx component
- [ ] Integrate TipTap for features
- [ ] Create ServiceCardsEditor.tsx
- [ ] Create ProcessStepsEditor.tsx
- [ ] Create editors for remaining blocks

### Phase 4: Media Upload (2-3 hours)
- [ ] Create MediaUploader.tsx component
- [ ] Implement temp upload API endpoint
- [ ] Implement commit-temp API endpoint
- [ ] Add preview controls (Revert button)
- [ ] Add visual indicators for unsaved media
- [ ] Test upload/revert flow

### Phase 5: State Management (2-3 hours)
- [ ] Set up EditorContext
- [ ] Implement updateBlock action
- [ ] Implement reorderBlocks action
- [ ] Add optimistic updates
- [ ] Add undo/redo logic

### Phase 6: API Integration (2-3 hours)
- [ ] Connect to Laravel endpoints
- [ ] Implement save flow with error handling
- [ ] Add loading states
- [ ] Add toast notifications
- [ ] Test save/rollback

### Phase 7: Polish (2-3 hours)
- [ ] Add keyboard shortcuts
- [ ] Add inline validation
- [ ] Add accessibility attributes
- [ ] Add animations (Framer Motion)
- [ ] Test user flows end-to-end

### Phase 8: Testing & Cleanup (2-3 hours)
- [ ] Test all block editors
- [ ] Test drag-and-drop
- [ ] Test media upload/revert
- [ ] Test save/cancel/undo
- [ ] Remove old PreviewModeSimple.tsx
- [ ] Update route to use new EditMode

---

## Success Criteria

**Must Work:**
- [ ] Can toggle between View and Edit modes
- [ ] Animations pause in edit mode, work in view mode
- [ ] Can edit all animated words in Hero without waiting
- [ ] Can reorder blocks via drag-and-drop
- [ ] Can edit each block with appropriate form
- [ ] Changes preview in real-time on left
- [ ] Can save changes to Laravel API
- [ ] Can upload media and preview before saving
- [ ] Can revert media changes
- [ ] Can undo/redo changes
- [ ] Keyboard shortcuts work
- [ ] Looks Apple-quality (glass effects, smooth animations)

**User Experience:**
- [ ] No scrolling issues
- [ ] No animation conflicts
- [ ] Clear visual feedback
- [ ] Fast and responsive
- [ ] Intuitive workflow
- [ ] Safe media preview

---

## Notes

- Follow TALL stack conventions where applicable
- Use existing TipTap installation from package.json
- Keep Hero.tsx animation logic intact for View mode
- Use Framer Motion for animations
- Follow Apple HIG for design patterns
- Test on Chrome, Safari, Firefox

**Estimated Total Time:** 16-24 hours
