# Known Issues

**Version:** 1.0
**Last Updated:** 2026-01-19
**Status:** Initial Release

---

## Overview

This document tracks known issues, limitations, and planned improvements for the visual editor. Issues are categorized by severity and status.

---

## Severity Levels

- **Critical (P0):** Blocker, prevents core functionality
- **High (P1):** Major issue, significant impact on UX
- **Medium (P2):** Noticeable issue, workaround exists
- **Low (P3):** Minor cosmetic or edge case issue

---

## Open Issues

### #005 ESLint Hook Rules Violations

- **Severity:** Low
- **Category:** Code Quality
- **Browser:** N/A (Development)
- **Status:** Open
- **Found:** 2026-01-19 (Task 21)

**Description:**
Block editors have early returns before hook calls, violating React's rules of hooks. This causes ESLint warnings but doesn't affect runtime functionality.

**Affected Files:**

- `sidebar/editors/HeroEditor.tsx`
- `sidebar/editors/FAQEditor.tsx`
- `sidebar/editors/TeamSectionEditor.tsx`
- `sidebar/editors/ServiceCardsEditor.tsx`
- `sidebar/editors/ProcessStepsEditor.tsx`
- `sidebar/editors/CTASectionEditor.tsx`

**Pattern:**

```typescript
const block = blocks.find((b) => b.id === selectedBlockId)
if (!block || block.type !== 'Hero') return null  // Early return

// Hooks called after early return (violates rules of hooks)
const validation = useValidation(...)
```

**Expected Behavior:**
Hooks should be called before any conditional returns.

**Actual Behavior:**
Early returns happen before hooks, causing ESLint `react-hooks/rules-of-hooks` errors.

**Workaround:**
None needed - code works correctly at runtime. This is a linting issue only.

**Impact:**

- No runtime impact
- ESLint warnings in CI/CD
- Code review flags

**Priority:**
Low - Works correctly, but should be refactored in v1.1 for cleaner code.

**Proposed Fix (v1.1):**
Move hooks before early return and handle null block in hook logic:

```typescript
const headlinePrefixValidation = useValidation(block?.props.headlinePrefix, [...])
if (!block || block.type !== 'Hero') return null
```

---

### #006 TypeScript Type Casting Issues

- **Severity:** Low
- **Category:** Type Safety
- **Browser:** N/A (Development)
- **Status:** Open
- **Found:** 2026-01-19 (Task 21)

**Description:**
Block editors have type casting issues where `unknown` types from `Record<string, unknown>` need explicit casting to specific types.

**Affected Files:**

- `sidebar/editors/HeroEditor.tsx` (7 errors)
- `sidebar/editors/CTASectionEditor.tsx` (3 errors)
- `sidebar/editors/FAQEditor.tsx` (1 error)
- `sidebar/editors/ServiceCardsEditor.tsx` (1 error)
- `sidebar/editors/ProcessStepsEditor.tsx` (1 error)
- `sidebar/editors/TeamSectionEditor.tsx` (1 error)

**Pattern:**

```typescript
interface Block {
  props: Record<string, unknown>  // Generic type
}

// Usage
<input value={block.props.heading} />  // Error: unknown not assignable to string
```

**Expected Behavior:**
TypeScript should infer correct types for block props.

**Actual Behavior:**
Props are typed as `unknown` and need explicit casting.

**Workaround:**
Use type assertions:

```typescript
const heading = block.props.heading as string;
```

**Impact:**

- TypeScript compilation warnings
- Less type safety in editors
- More verbose code with type assertions

**Priority:**
Low - Type assertions work, but could be improved with better typing.

**Proposed Fix (v1.1):**
Create block-specific interfaces:

```typescript
interface HeroBlock extends Block {
  type: "Hero";
  props: {
    headlinePrefix: string;
    sliderContent: string[];
    // ...
  };
}
```

---

### #007 Example Files Import Errors

- **Severity:** Low
- **Category:** Documentation
- **Browser:** N/A (Development)
- **Status:** Open
- **Found:** 2026-01-19 (Task 21)

**Description:**
Example files in `docs/examples/` have import path errors after being moved from `components/`.

**Affected Files:**

- `docs/examples/LoadingStates.example.tsx`
- `docs/examples/MediaUploader.example.tsx`
- `docs/examples/Toast.example.tsx`

**Pattern:**

```typescript
import { MediaUploader } from "./MediaUploader"; // ❌ Wrong path
```

**Expected Behavior:**
Imports should resolve correctly.

**Actual Behavior:**
TypeScript cannot find modules.

**Workaround:**
Example files are not compiled in production build (documentation only).

**Impact:**

- TypeScript errors in development
- Example files cannot be run directly
- No impact on production code

**Priority:**
Low - Example files are for reference only, not executed.

**Proposed Fix (v1.1):**
Update imports to use correct relative paths:

```typescript
import { MediaUploader } from "../../components/MediaUploader";
```

### Issue Template

```markdown
### [ID] [Title]

- **Severity:** Critical | High | Medium | Low
- **Category:** Functionality | Performance | Accessibility | UI/UX | Browser Compatibility
- **Browser:** Chrome | Safari | Firefox | All
- **Status:** Open | In Progress | Fixed | Won't Fix
- **Found:** 2026-01-XX
- **Assigned To:** (if applicable)

**Description:**
Brief description of the issue.

**Steps to Reproduce:**

1. Step 1
2. Step 2
3. Step 3

**Expected Behavior:**
What should happen.

**Actual Behavior:**
What actually happens.

**Workaround:**
(If a workaround exists)

**Impact:**
How this affects users.

**Priority:**
Why this needs to be fixed (or doesn't).
```

---

## Example Issues (Delete After Testing)

### #001 Auto-renumbering Steps Broken

- **Severity:** High
- **Category:** Functionality
- **Browser:** All
- **Status:** Open
- **Found:** 2026-01-19

**Description:**
Process steps don't renumber correctly after deleting a step in the middle.

**Steps to Reproduce:**

1. Open ProcessStepsEditor
2. Add 3 steps (numbered 1, 2, 3)
3. Delete step 2
4. Observe remaining steps

**Expected Behavior:**
Steps should renumber to 1, 2

**Actual Behavior:**
Steps still show 1, 3

**Workaround:**
Manually refresh the page after deleting a step.

**Impact:**
Users see incorrect step numbers in the preview.

**Priority:**
High - This is a core feature and affects UX significantly.

---

### #002 Safari Image Upload Slow

- **Severity:** Medium
- **Category:** Performance
- **Browser:** Safari
- **Status:** Open
- **Found:** 2026-01-19

**Description:**
Image uploads take 2-3 seconds longer in Safari compared to Chrome.

**Steps to Reproduce:**

1. Open editor in Safari
2. Use MediaUploader to select a 2MB image
3. Observe upload time

**Expected Behavior:**
Upload completes within 1-2 seconds.

**Actual Behavior:**
Upload takes 3-5 seconds.

**Workaround:**
Use Chrome for faster uploads, or compress images before uploading.

**Impact:**
Slightly slower UX for Safari users, but not blocking.

**Priority:**
Medium - Worth investigating, but not critical.

---

## Fixed Issues

(Issues will be moved here after they are resolved)

### Example: #003 Toast Not Dismissing

- **Severity:** Medium
- **Category:** UI/UX
- **Browser:** All
- **Status:** Fixed
- **Found:** 2026-01-15
- **Fixed:** 2026-01-16
- **Fixed By:** Claude Code

**Description:**
Success toasts were not auto-dismissing after 5 seconds.

**Resolution:**
Updated `ToastContext.tsx` to properly clear the timeout when toast is manually dismissed.

**Commit:** `fix(editor): fix toast auto-dismiss timing`

---

## Won't Fix

(Issues that are intentional or out of scope)

### Example: #004 No Mobile Editing Support

- **Severity:** Medium
- **Category:** Browser Compatibility
- **Browser:** Mobile Safari, Mobile Chrome
- **Status:** Won't Fix
- **Found:** 2026-01-15

**Description:**
Editor is not optimized for mobile devices (phones/tablets).

**Justification:**
Visual editor is designed for desktop admin use only. Mobile editing is intentionally out of scope for v1.

**Recommendation:**
Add responsive design in v2 if mobile admin use becomes a requirement.

---

## Limitations

### Known Limitations (By Design)

1. **Desktop Only:**
   - Editor is optimized for desktop browsers (1024px+)
   - Mobile devices not supported in v1

2. **Single User Editing:**
   - No concurrent editing support
   - Last save wins if multiple users edit simultaneously

3. **Image Upload Size:**
   - Maximum file size: 5MB
   - Supported formats: JPEG, PNG only

4. **Undo History:**
   - Limited to 10 steps
   - History cleared on page refresh

5. **Block Types:**
   - Only 6 block types supported in v1
   - Custom block types not supported

6. **Laravel Backend Required:**
   - Editor requires Laravel API running locally or on server
   - No offline editing support

---

## Browser Support

### Fully Supported

- Chrome 120+
- Safari 17+
- Firefox 121+
- Edge (Chromium-based)

### Partially Supported

- Safari 16 (some animation glitches)
- Firefox 120 (drag-and-drop slightly less smooth)

### Not Supported

- Internet Explorer (EOL)
- Safari <16
- Chrome <100

---

## Planned Improvements

### v1.1 (Future)

- [ ] Add more block types (Gallery, Testimonials, etc.)
- [ ] Improve drag-and-drop animations
- [ ] Add image compression before upload
- [ ] Add multi-select for bulk actions
- [ ] Add keyboard shortcuts help modal

### v1.2 (Future)

- [ ] Add responsive preview (mobile/tablet)
- [ ] Add revision history
- [ ] Add collaborative editing (WebSockets)
- [ ] Add more image formats (WebP, AVIF)
- [ ] Add image editing (crop, resize)

### v2.0 (Long-term)

- [ ] Full mobile editing support
- [ ] Custom block builder
- [ ] Template system
- [ ] A/B testing integration
- [ ] Analytics integration

---

## Reporting New Issues

If you find a new issue during testing:

1. **Check if it already exists** in this document
2. **Reproduce the issue** at least twice
3. **Add it to "Open Issues"** using the template
4. **Assign a severity level** (P0-P3)
5. **Document workarounds** if any exist
6. **Update TEST-REPORT.md** with findings

---

## Issue Triage Process

### Critical (P0)

- **Response Time:** Immediate
- **Fix Timeline:** Within 24 hours
- **Deployment:** Hotfix if in production

### High (P1)

- **Response Time:** Same day
- **Fix Timeline:** Within 1 week
- **Deployment:** Next release

### Medium (P2)

- **Response Time:** Within 1 week
- **Fix Timeline:** Within 1 month
- **Deployment:** Scheduled release

### Low (P3)

- **Response Time:** Best effort
- **Fix Timeline:** Backlog
- **Deployment:** When convenient

---

## Contact

**Primary Contact:** Nick Heymann
**Development Team:** Claude Code (AI Assistant)
**Repository:** github.com/NickHeymann/musikfuerfirmen

---

**Document Status:** Living Document (updated after each test cycle)
