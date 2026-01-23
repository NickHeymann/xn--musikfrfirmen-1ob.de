# Visual Editor for musikfuerfirmen.de

**Version:** 1.1.0  
**Status:** Production Ready (Block Templates Added)  
**Last Updated:** 2026-01-23

---

## Overview

Apple-quality inline visual editor for editing the musikfuerfirmen.de homepage. Edit content in real-time with live preview, drag-and-drop reordering, and comprehensive validation.

---

## Features

### Core Functionality

- ✅ **View/Edit Mode Toggle** - Switch between read-only preview and editing
- ✅ **Real-Time Preview** - Changes update with 300ms debounce
- ✅ **Drag & Drop Reordering** - Reorder blocks visually
- ✅ **Undo/Redo** - 10-step history (Cmd+Z / Cmd+Shift+Z)
- ✅ **Keyboard Shortcuts** - Save (Cmd+S), Edit (Cmd+E), Exit (Esc)
- ✅ **Auto-Save Integration** - Save to Laravel API

### Block Editors

- ✅ **HeroEditor** - Edit hero section with animated words
- ✅ **ServiceCardsEditor** - Manage service cards with accordion
- ✅ **ProcessStepsEditor** - Edit process steps with auto-renumbering
- ✅ **TeamSectionEditor** - Manage team members
- ✅ **FAQEditor** - Edit FAQ questions/answers
- ✅ **CTASectionEditor** - Edit CTA section

### User Experience

- ✅ **Loading States** - Skeleton loaders during data fetch
- ✅ **Toast Notifications** - Success, error, warning toasts
- ✅ **Validation** - Required fields, error messages, save blocking
- ✅ **Media Upload** - Image upload with preview
- ✅ **Accessibility** - Keyboard navigation, ARIA labels, screen reader support

### Block Templates (v1.1.0)

- ✅ **Default Templates** - 5 pre-configured layouts (Hero, Features, Testimonials, CTA, Two-Column)
- ✅ **Template Library** - Browse, search, and filter templates
- ✅ **Template Preview** - See details before inserting
- ✅ **Custom Templates** - Save page layouts as reusable templates
- ✅ **localStorage Persistence** - Custom templates persist across sessions

**See:** [Block Templates Documentation](./docs/features/BLOCK-TEMPLATES.md)

---

## Architecture

```
visual-editor/
├── components/         # Reusable UI components
│   ├── ArrayInput.tsx              # Array manipulation (add/remove/reorder)
│   ├── MediaUploader.tsx           # Image upload component
│   ├── ModeToggle.tsx              # View/Edit mode switcher
│   ├── SkeletonLoader.tsx          # Loading states
│   ├── Spinner.tsx                 # Loading spinner
│   ├── Toast.tsx                   # Toast notifications
│   ├── TemplateLibrary.tsx         # Template browser (v1.1.0)
│   ├── TemplatePreviewModal.tsx    # Template preview (v1.1.0)
│   └── SaveTemplateModal.tsx       # Save template form (v1.1.0)
│
├── context/           # React Context providers
│   ├── EditorContext.tsx      # Editor state, undo/redo, save
│   ├── ToastContext.tsx       # Toast notification system
│   └── ValidationContext.tsx  # Form validation
│
├── hooks/             # Custom React hooks
│   ├── useKeyboardShortcuts.ts   # Keyboard shortcuts (Cmd+S, etc.)
│   ├── useValidation.ts          # Validation logic
│   └── useCustomTemplates.ts     # Custom template management (v1.1.0)
│
├── data/              # Static data (v1.1.0)
│   └── blockTemplates.ts         # Default template definitions
│
├── types/             # TypeScript types
│   ├── index.ts                  # Core types
│   └── blockTemplate.ts          # Template types (v1.1.0)
│
├── modes/             # View/Edit mode components
│   ├── ViewMode.tsx           # Read-only preview
│   └── EditMode.tsx           # Split-screen editing
│
├── sidebar/           # Editor sidebar
│   ├── BlockList.tsx          # List of editable blocks
│   ├── EditorSidebar.tsx      # Sidebar container (tabs)
│   └── editors/               # Block-specific editors
│       ├── HeroEditor.tsx
│       ├── ServiceCardsEditor.tsx
│       ├── ProcessStepsEditor.tsx
│       ├── TeamSectionEditor.tsx
│       ├── FAQEditor.tsx
│       └── CTASectionEditor.tsx
│
├── styles/            # CSS styles
│   └── editor.css     # Editor-specific styles
│
└── types.ts           # TypeScript interfaces
```

---

## Usage

### Development

```bash
# 1. Start Laravel backend
cd laravel-backend-files
php artisan serve --port=8001

# 2. Start Next.js dev server (separate terminal)
npm run dev

# 3. Open editor
open http://localhost:3000/admin/editor/home
```

### Quick Test

```bash
# Run quick test (~15 minutes)
# See: src/visual-editor/QUICK-TEST.md
```

### Full Testing

```bash
# Run comprehensive test suite
# See: src/visual-editor/TESTING.md
```

---

## API Integration

### Laravel Backend

The visual editor communicates with a Laravel backend API:

**Base URL:** `http://localhost:8001/api` (dev) | `https://api.musikfuerfirmen.de/api` (prod)

**Endpoints:**

1. **Get Page Data:**

   ```
   GET /api/pages/:slug
   Response: { id, slug, content: { blocks: [...] } }
   ```

2. **Save Page Data:**

   ```
   POST /api/pages/:slug
   Body: { content: { blocks: [...] } }
   Response: { success: true, data: {...} }
   ```

3. **Upload Media:**
   ```
   POST /api/media/upload
   Body: FormData with 'file' field
   Response: { url: 'https://...' }
   ```

**CORS:** Laravel backend must have CORS enabled for `localhost:3000` (dev) and production domain.

---

## Configuration

### Environment Variables

```env
# .env.local
NEXT_PUBLIC_API_URL=http://localhost:8001/api
```

### Laravel Backend Setup

```bash
cd laravel-backend-files

# Install dependencies
composer install

# Run migrations
php artisan migrate

# Start server
php artisan serve --port=8001
```

---

## Keyboard Shortcuts

| Shortcut      | Action           |
| ------------- | ---------------- |
| `Cmd+E`       | Toggle Edit Mode |
| `Cmd+S`       | Save changes     |
| `Cmd+Z`       | Undo last change |
| `Cmd+Shift+Z` | Redo change      |
| `Esc`         | Exit Edit Mode   |

---

## Component Documentation

### ArrayInput

Reusable component for managing arrays (add, remove, reorder items).

**Usage:**

```tsx
<ArrayInput
  items={animatedWords}
  onChange={setAnimatedWords}
  renderItem={(word, index) => (
    <input value={word} onChange={(e) => handleChange(index, e.target.value)} />
  )}
  addButtonText="Add Word"
/>
```

**See:** `components/ArrayInput.tsx`

---

### MediaUploader

Image upload component with preview, validation, and remove functionality.

**Usage:**

```tsx
<MediaUploader
  value={imageUrl}
  onChange={setImageUrl}
  label="Hero Image"
  accept="image/jpeg,image/png"
  maxSize={5 * 1024 * 1024} // 5MB
/>
```

**See:** `components/MediaUploader.tsx`, `components/MediaUploader.README.md`

---

### Toast Notifications

Global toast system via context.

**Usage:**

```tsx
import { useToast } from "@/visual-editor/context/ToastContext";

function MyComponent() {
  const { addToast } = useToast();

  const handleSave = () => {
    addToast("success", "Saved successfully!");
  };
}
```

**See:** `context/ToastContext.tsx`, `components/Toast.tsx`

---

### Validation

Form validation with error messages.

**Usage:**

```tsx
import { useValidation } from "@/visual-editor/context/ValidationContext";

function MyEditor() {
  const { registerField, errors } = useValidation();

  return (
    <input
      {...registerField("heading", { required: true })}
      className={errors.heading ? "border-red-500" : ""}
    />
  );
}
```

**See:** `context/ValidationContext.tsx`, `hooks/useValidation.ts`

---

## Testing

### Documentation

- **Quick Test:** `QUICK-TEST.md` - 15-minute rapid test
- **Full Test Plan:** `TESTING.md` - Comprehensive test scenarios
- **Test Report:** `TEST-REPORT.md` - Test results and findings
- **Known Issues:** `KNOWN-ISSUES.md` - Documented issues and limitations

### Running Tests

```bash
# TypeScript type checking
npx tsc --noEmit

# ESLint
npm run lint

# Production build test
npm run build

# Manual testing
npm run dev
# Open http://localhost:3000/admin/editor/home
```

---

## Deployment

See: `DEPLOYMENT-CHECKLIST.md`

**Quick Deployment Steps:**

1. Run tests: `npm run lint && npx tsc --noEmit && npm run build`
2. Create backup tag: `git tag pre-deployment-$(date +%Y%m%d-%H%M%S)`
3. Deploy to production: `git push origin main` (Vercel auto-deploys)
4. Verify: `curl https://musikfuerfirmen.de/admin/editor/home`
5. Run smoke tests (see checklist)

---

## Performance

### Metrics

- **Lighthouse Performance:** Target >90
- **Lighthouse Accessibility:** Target >95
- **Bundle Size:** ~145KB additional (acceptable)
- **Initial Load:** <2 seconds
- **Animation FPS:** 60fps (smooth)

### Optimizations

- **Debounced Updates:** 300ms delay reduces re-renders
- **Code Splitting:** Editor chunks loaded on-demand
- **Lazy Loading:** Heavy components loaded when needed
- **Memoization:** React.memo on expensive components

---

## Browser Support

### Fully Supported

- Chrome 120+
- Safari 17+
- Firefox 121+
- Edge (Chromium)

### Not Supported

- Internet Explorer (EOL)
- Safari <16
- Mobile browsers (by design - desktop editor only)

---

## Accessibility

- ✅ Keyboard navigation (Tab, Enter, Space)
- ✅ Screen reader support (VoiceOver tested)
- ✅ ARIA labels on interactive elements
- ✅ Focus indicators visible
- ✅ Error messages announced
- ✅ WCAG AA compliance

---

## Troubleshooting

### Editor Not Loading

**Symptom:** Blank page or loading forever

**Check:**

1. Is Laravel backend running? `curl http://localhost:8001/api/pages/home`
2. CORS enabled? Check Laravel backend CORS config
3. Console errors? Open DevTools and check Console/Network tabs

**Solution:**

```bash
# Restart Laravel backend
cd laravel-backend-files
php artisan serve --port=8001

# Check CORS in config/cors.php
```

---

### Changes Not Saving

**Symptom:** Click Save, no toast appears

**Check:**

1. Network tab in DevTools - is POST request sent?
2. API response - is it 200 or error?
3. Validation errors - check for red borders on inputs

**Solution:**

- If network error: Restart Laravel backend
- If validation error: Fix required fields
- If 500 error: Check Laravel logs

---

### Images Not Uploading

**Symptom:** Image upload fails or doesn't appear

**Check:**

1. File size <5MB?
2. File type JPEG or PNG?
3. Upload endpoint working? `curl -X POST http://localhost:8001/api/media/upload`
4. Storage directory writable? `storage/app/public/media/`

**Solution:**

```bash
# Make storage writable
cd laravel-backend-files
chmod -R 775 storage
php artisan storage:link
```

---

### Drag-and-Drop Not Working

**Symptom:** Can't reorder blocks

**Check:**

1. Browser: Safari sometimes has drag issues
2. Console errors?
3. Try in Chrome

**Solution:**

- Use Chrome for drag-and-drop
- Or use arrow buttons (if implemented)

---

## Contributing

For detailed contribution guidelines, see [CONTRIBUTING.md](./CONTRIBUTING.md).

**Quick Links:**

- [Architecture Documentation](./ARCHITECTURE.md)
- [Contribution Guide](./CONTRIBUTING.md)
- [Testing Documentation](./TESTING.md)

### Code Style

- Use TypeScript strict mode
- Follow existing patterns (EditorContext, validation, etc.)
- Add ARIA labels for accessibility
- Write JSDoc comments for complex functions
- Keep components <300 lines (split if larger)

---

## Future Improvements (v1.2+)

- [ ] Add more block types (Gallery, Video, etc.)
- [ ] Mobile editing support (responsive design)
- [ ] Revision history (version control)
- [ ] Collaborative editing (WebSockets)
- [ ] Image editing (crop, resize, filters)
- [ ] Undo/Redo beyond 10 steps
- [ ] Keyboard shortcuts help modal
- [ ] Template preview image generation
- [ ] Server-side template storage (cross-device sync)
- [ ] A/B testing integration

---

## Support

**Developer:** Nick Heymann
**AI Assistant:** Claude Code (Anthropic)
**Repository:** github.com/NickHeymann/musikfuerfirmen
**Project:** musikfuerfirmen.de

**Issues:** Report via GitHub Issues or KNOWN-ISSUES.md

---

## License

Proprietary - musikfuerfirmen.de

---

## Changelog

### v1.1.0 (2026-01-23)

**Block Templates Feature**

- ✅ 5 default templates (Hero, Features, Testimonials, CTA, Two-Column)
- ✅ Template Library UI with search and filtering
- ✅ Template Preview Modal with detailed information
- ✅ Custom template creation and management
- ✅ localStorage persistence for custom templates
- ✅ Delete functionality for custom templates
- ✅ Toast notifications for template actions
- ✅ Comprehensive documentation

**Files Added:**
- `types/blockTemplate.ts` - TypeScript types
- `data/blockTemplates.ts` - Default templates
- `components/TemplateLibrary.tsx` - Main library UI
- `components/TemplatePreviewModal.tsx` - Preview modal
- `components/SaveTemplateModal.tsx` - Save form
- `hooks/useCustomTemplates.ts` - localStorage management
- `docs/features/BLOCK-TEMPLATES.md` - Full documentation

**Changes:**
- Updated `EditorContext` with `insertTemplate` function
- Updated `EditorSidebar` with template buttons
- Updated `README.md` with v1.1.0 features

### v1.0.0 (2026-01-19)

- Initial release
- All core features implemented (Tasks 1-20)
- Code cleanup and documentation complete (Task 21)
- Production ready

**Changes in Task 21:**

- Removed console.log statements (except error logging)
- Moved example files to docs/examples/
- Fixed all 'any' types with proper TypeScript interfaces
- Removed unused imports (arrayMove)
- Added JSDoc comments to public APIs (EditorContext, useEditor)
- Created ARCHITECTURE.md (complete system documentation)
- Created CONTRIBUTING.md (contribution guidelines)
- Updated README.md with documentation links

---

**Status:** Production Ready  
**Next Step:** Deploy v1.1.0 to production
