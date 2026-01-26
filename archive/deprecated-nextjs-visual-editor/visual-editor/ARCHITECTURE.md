# Visual Editor Architecture

> Complete architectural documentation for the musikfürfirmen.de visual editor

## Overview

The visual editor is a lightweight, extensible CMS built with Next.js 16, TypeScript, and React. It follows a **functional core / imperative shell** architecture pattern.

### Key Features

- **Block-based editing** - Modular content blocks (Hero, FAQ, Team, etc.)
- **Real-time preview** - See changes instantly with debounced updates
- **Undo/Redo** - Full history management
- **Type-safe** - TypeScript throughout, no `any` types
- **Drag-and-drop** - Reorder blocks with @dnd-kit
- **Auto-save** - Debounced saves to prevent data loss
- **Validation** - Rule-based validation with useValidation hook

---

## Directory Structure

```
src/visual-editor/
├── components/          # Reusable UI components
│   ├── ArrayInput.tsx      # Dynamic array editing
│   ├── LoadingStates.tsx   # Loading skeletons
│   ├── MediaUploader.tsx   # Image/video upload
│   └── Toast.tsx           # Notifications
├── context/             # React Context
│   └── EditorContext.tsx   # Global editor state
├── hooks/               # Custom React hooks
│   ├── useAutoSave.ts      # Auto-save logic
│   ├── useKeyboardShortcuts.ts  # Keyboard navigation
│   └── useValidation.ts    # Form validation
├── modes/               # View/Edit modes
│   ├── EditMode.tsx        # Edit interface
│   └── ViewMode.tsx        # Preview
├── sidebar/             # Editor sidebar
│   ├── BlockList.tsx       # Block list with drag-and-drop
│   ├── Sidebar.tsx         # Main sidebar
│   └── editors/            # Block-specific editors
│       ├── HeroEditor.tsx
│       ├── FAQEditor.tsx
│       └── TeamEditor.tsx
├── styles/              # Global styles
│   └── editor.css          # Editor-specific CSS
├── types.ts             # TypeScript interfaces
└── VisualEditor.tsx     # Main entry point
```

---

## Component Hierarchy

```
VisualEditor (root)
├── EditorProvider (context)
│   ├── ViewMode
│   │   ├── Header
│   │   ├── {blocks.map(Block)}  # Hero, FAQ, Team, etc.
│   │   └── Footer
│   └── EditMode
│       ├── Sidebar
│       │   ├── BlockList (drag-and-drop)
│       │   └── {BlockEditor}  # Dynamic editor for selected block
│       └── ViewMode (preview)
```

---

## Data Flow

### State Management

```
EditorContext (single source of truth)
├── blocks: Block[]              # Current page content
├── debouncedBlocks: Block[]     # Debounced for preview (300ms)
├── history: Block[][]           # Undo/redo stack
├── selectedBlockId: string      # Currently selected block
├── mode: 'view' | 'edit'        # Current mode
└── hasUnsavedChanges: boolean   # Dirty flag
```

### Update Flow

```
User Input
  → updateBlock(blockId, props)
  → setBlocks(updated)
  → addToHistory(updated)
  → setHasUnsavedChanges(true)
  → [debounce 300ms]
  → debouncedBlocks updated
  → Preview re-renders
```

### Save Flow

```
User Action (Cmd+S / Save button)
  → saveDraft()
  → setIsSaving(true)
  → fetch PUT /api/pages/:slug
  → setHasUnsavedChanges(false)
  → setIsSaving(false)
  → Toast notification
```

---

## Block System

### Block Interface

```typescript
interface Block {
  id: string; // Unique identifier (nanoid)
  type: string; // Component name (Hero, FAQ, etc.)
  props: Record<string, unknown>; // Component props
}
```

### Block Registry

Blocks are registered in `ViewMode.tsx`:

```typescript
const componentRegistry: Record<string, FC<Record<string, unknown>>> = {
  Hero,
  ServiceCards,
  ProcessSteps,
  TeamSection,
  FAQ,
  CTASection,
};
```

### Adding a New Block Editor

1. **Create editor** in `sidebar/editors/`:

   ```typescript
   // sidebar/editors/NewBlockEditor.tsx
   export function NewBlockEditor() {
     const { selectedBlockId, updateBlock } = useEditor();
     // ... editor UI
   }
   ```

2. **Register in Sidebar**:

   ```typescript
   // sidebar/Sidebar.tsx
   const editors: Record<string, FC> = {
     Hero: HeroEditor,
     FAQ: FAQEditor,
     NewBlock: NewBlockEditor, // Add here
   };
   ```

3. **Add to component registry**:
   ```typescript
   // modes/ViewMode.tsx
   const componentRegistry = {
     NewBlock: NewBlockComponent, // Add here
   };
   ```

---

## Validation System

### Rule-based Validation

Uses `useValidation` hook with declarative rules:

```typescript
const { error, validate } = useValidation(heading, [
  { type: "required", message: "Heading is required" },
  { type: "maxLength", max: 100 },
]);
```

### Available Rules

| Rule        | Description       | Example                                       |
| ----------- | ----------------- | --------------------------------------------- |
| `required`  | Non-empty value   | `{ type: 'required' }`                        |
| `minLength` | Min string length | `{ type: 'minLength', min: 10 }`              |
| `maxLength` | Max string length | `{ type: 'maxLength', max: 100 }`             |
| `minItems`  | Min array length  | `{ type: 'minItems', min: 1 }`                |
| `maxItems`  | Max array length  | `{ type: 'maxItems', max: 5 }`                |
| `pattern`   | Regex match       | `{ type: 'pattern', pattern: /^[A-Z]/ }`      |
| `custom`    | Custom validator  | `{ type: 'custom', validator: (v) => v > 0 }` |

---

## Auto-Save System

### Implementation

```typescript
// hooks/useAutoSave.ts
useAutoSave({
  data: blocks, // Data to save
  save: saveDraft, // Save function
  interval: 30000, // 30 seconds
  enabled: hasUnsavedChanges,
});
```

### Behavior

- **Debounced**: Only saves after 30s of inactivity
- **Conditional**: Only when `hasUnsavedChanges === true`
- **Error handling**: Catches and logs errors, shows toast
- **Manual override**: Cmd+S triggers immediate save

---

## Keyboard Shortcuts

### Implementation

```typescript
// hooks/useKeyboardShortcuts.ts
useKeyboardShortcuts({
  onSave: saveDraft,
  onUndo: undo,
  onRedo: redo,
});
```

### Shortcuts

| Shortcut                 | Action     |
| ------------------------ | ---------- |
| `Cmd+S` / `Ctrl+S`       | Save draft |
| `Cmd+Z` / `Ctrl+Z`       | Undo       |
| `Cmd+Shift+Z` / `Ctrl+Y` | Redo       |

---

## Drag-and-Drop System

### Library

Uses **@dnd-kit** (modern, accessible DnD library)

### Implementation

```typescript
// sidebar/BlockList.tsx
<DndContext onDragEnd={handleDragEnd}>
  <SortableContext items={blocks.map(b => b.id)}>
    {blocks.map(block => (
      <SortableBlockItem key={block.id} block={block} />
    ))}
  </SortableContext>
</DndContext>
```

### Features

- **Drag handle**: Only draggable via GripVertical icon
- **Visual feedback**: Opacity change during drag
- **History tracking**: Reorders add to undo/redo stack

---

## Media Upload System

### Component

```typescript
<MediaUploader
  type="image"
  currentUrl={image}
  onUploadComplete={(url) => updateBlock(blockId, { image: url })}
/>
```

### Features

- **Type-specific**: Image or video
- **Validation**: File type and size checks
- **Progress**: Real-time upload progress
- **Preview**: Shows current media with remove button

### API Integration

```typescript
// POST /api/upload (Laravel backend)
const formData = new FormData();
formData.append("file", file);

const response = await fetch("http://localhost:8001/api/upload", {
  method: "POST",
  body: formData,
});
```

---

## Loading States

### Skeleton Components

```typescript
<LoadingStates.HeroSkeleton />      // Hero loading state
<LoadingStates.FAQSkeleton />       // FAQ loading state
<LoadingStates.TeamSkeleton />      // Team loading state
```

### Usage

```typescript
{isLoading ? (
  <LoadingStates.HeroSkeleton />
) : (
  <Hero {...heroData} />
)}
```

---

## Toast Notifications

### API

```typescript
import { toast } from "../components/Toast";

toast.success("Saved successfully!");
toast.error("Save failed");
toast.info("Processing...");
```

### Features

- **Auto-dismiss**: 3 seconds (configurable)
- **Queue**: Multiple toasts stack
- **Animations**: Smooth enter/exit
- **Accessibility**: ARIA labels

---

## TypeScript Types

### Core Types

```typescript
// types.ts
interface Block {
  id: string;
  type: string;
  props: Record<string, unknown>;
}

interface EditorState {
  mode: "view" | "edit";
  selectedBlockId: string | null;
  blocks: Block[];
  debouncedBlocks: Block[];
  hasUnsavedChanges: boolean;
  isSaving: boolean;
  isLoading: boolean;
  history: Block[][];
  historyIndex: number;
}

interface EditorActions {
  setMode: (mode: "view" | "edit") => void;
  selectBlock: (blockId: string | null) => void;
  updateBlock: (blockId: string, props: Record<string, unknown>) => void;
  reorderBlocks: (sourceIndex: number, targetIndex: number) => void;
  saveDraft: () => Promise<void>;
  undo: () => void;
  redo: () => void;
}
```

---

## API Integration

### Endpoints

| Method | Endpoint           | Description         |
| ------ | ------------------ | ------------------- |
| `GET`  | `/api/pages/:slug` | Fetch page data     |
| `PUT`  | `/api/pages/:slug` | Update page content |
| `POST` | `/api/upload`      | Upload media file   |

### Request/Response

```typescript
// PUT /api/pages/home
Request:
{
  content: {
    blocks: Block[]
  }
}

Response:
{
  success: true,
  page: {
    id: 1,
    slug: 'home',
    content: { blocks: Block[] }
  }
}
```

---

## Performance Optimizations

### Debouncing

- **Preview updates**: 300ms debounce prevents excessive re-renders
- **Auto-save**: 30s debounce reduces API calls

### Memoization

- **useCallback**: All editor actions memoized
- **Block rendering**: Key-based reconciliation

### Code Splitting

- **Lazy loading**: Editors loaded on demand
- **Tree shaking**: Unused code eliminated

---

## Error Handling

### Validation Errors

```typescript
// Inline error display
{error && <span className="error">{error}</span>}
```

### API Errors

```typescript
try {
  await saveDraft();
  toast.success("Saved!");
} catch (error) {
  console.error("Save error:", error);
  toast.error("Save failed");
}
```

### Type Errors

- **Strict TypeScript**: No `any` types
- **Runtime checks**: Fallbacks for missing components

---

## Testing Strategy

### Unit Tests

- **Validation rules**: `useValidation.test.ts`
- **Utilities**: Pure functions

### Integration Tests

- **Editor workflows**: Create, edit, save blocks
- **Drag-and-drop**: Block reordering

### E2E Tests

- **Full user flows**: Login → Edit → Save → Publish

---

## Security Considerations

### Input Sanitization

- **XSS prevention**: All user input escaped
- **Type validation**: TypeScript + runtime checks

### Authentication

- **Protected routes**: Admin-only access
- **CSRF protection**: Laravel CSRF tokens

### File Upload

- **Type validation**: Only images/videos allowed
- **Size limits**: Max file size enforced
- **Secure storage**: Files stored outside public root

---

## Future Enhancements

### Planned Features

- [ ] Block templates library
- [ ] Version history (restore previous states)
- [ ] Collaborative editing (multiple users)
- [ ] Custom CSS per block
- [ ] A/B testing support
- [ ] SEO preview
- [ ] Scheduled publishing

### Technical Debt

- [ ] Add unit tests for all hooks
- [ ] Add E2E tests with Playwright
- [ ] Improve error boundaries
- [ ] Add telemetry/analytics

---

## Development Workflow

### Local Development

```bash
# Start Next.js dev server
npm run dev

# Start Laravel backend (port 8001)
cd tall-stack && php artisan serve --port=8001
```

### Building

```bash
# Production build
npm run build

# Preview production build
npm run start
```

### Linting

```bash
# Run ESLint
npm run lint

# Auto-fix issues
npm run lint -- --fix
```

---

## Troubleshooting

### Common Issues

**Issue**: "useEditor must be used within EditorProvider"
**Solution**: Wrap component tree with `<EditorProvider>`

**Issue**: Blocks not saving
**Solution**: Check Laravel backend is running on port 8001

**Issue**: Drag-and-drop not working
**Solution**: Ensure @dnd-kit packages installed

**Issue**: TypeScript errors
**Solution**: Run `npm run build` to check types

---

## References

### Dependencies

- **Next.js 16**: React framework
- **TypeScript**: Type safety
- **@dnd-kit**: Drag-and-drop
- **use-debounce**: Debouncing
- **nanoid**: Unique IDs
- **lucide-react**: Icons

### Related Docs

- [README.md](./README.md) - Overview and quick start
- [CONTRIBUTING.md](./CONTRIBUTING.md) - Contribution guide
- [TESTING.md](./TESTING.md) - Testing documentation
- [DEPLOYMENT-CHECKLIST.md](./DEPLOYMENT-CHECKLIST.md) - Production checklist

---

**Last Updated**: 2026-01-19
**Version**: 1.0.0
**Status**: Production Ready
