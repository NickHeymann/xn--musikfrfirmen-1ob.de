# Visual Editor Testing Plan

**Version:** 1.0
**Date:** 2026-01-19
**Status:** Comprehensive QA Phase

---

## Overview

This document provides a complete testing plan for the visual editor for musikfürfirmen.de.de. All features from Tasks 1-19 are implemented and require verification.

---

## Test Environment Setup

### Prerequisites

```bash
# 1. Install dependencies
npm install

# 2. Start Laravel backend
cd laravel-backend-files
php artisan serve --port=8001

# 3. Start Next.js dev server (separate terminal)
npm run dev

# 4. Access editor
open http://localhost:3000/admin/editor/home
```

### Environment Variables

Verify `.env.local` exists:

```env
NEXT_PUBLIC_API_URL=http://localhost:8001/api
```

---

## 1. Core Editor Functionality

### 1.1 Mode Toggle

| Test Case                | Steps                     | Expected Result                         | Status |
| ------------------------ | ------------------------- | --------------------------------------- | ------ |
| Switch to Edit Mode      | Click "Edit Mode" button  | Sidebar appears, blocks become editable | ⬜     |
| Switch to View Mode      | Click "View Mode" button  | Sidebar hidden, preview-only display    | ⬜     |
| Keyboard shortcut Cmd+E  | Press Cmd+E               | Toggle between modes                    | ⬜     |
| Escape key in Edit Mode  | Press Esc in Edit Mode    | Return to View Mode                     | ⬜     |
| Mode persists on refresh | Refresh page in Edit Mode | Should return to View Mode (default)    | ⬜     |

### 1.2 Block Selection (BLOCKS Tab)

| Test Case                | Steps                               | Expected Result                              | Status |
| ------------------------ | ----------------------------------- | -------------------------------------------- | ------ |
| Click Hero block         | Click "Hero" in BLOCKS tab          | Hero block selected, properties editor shows | ⬜     |
| Click ServiceCards block | Click "Service Cards" in BLOCKS tab | ServiceCards selected, editor switches       | ⬜     |
| Visual feedback          | Click any block                     | Active block highlighted in sidebar          | ⬜     |
| Scroll to block          | Click block in BLOCKS tab           | Preview scrolls to block smoothly            | ⬜     |
| Select all 6 blocks      | Click each block sequentially       | All blocks selectable without errors         | ⬜     |

### 1.3 Block Reordering (Drag & Drop)

| Test Case                    | Steps                          | Expected Result                      | Status |
| ---------------------------- | ------------------------------ | ------------------------------------ | ------ |
| Drag Hero below ServiceCards | Drag Hero block down           | Order updates in preview immediately | ⬜     |
| Drag CTA to top              | Drag CTA block to top position | CTA appears first in preview         | ⬜     |
| Drop animation               | Release dragged block          | Smooth drop animation                | ⬜     |
| Undo reorder                 | Press Cmd+Z after reorder      | Original order restored              | ⬜     |
| Save reordered blocks        | Save after reordering          | Order persists on refresh            | ⬜     |

### 1.4 Keyboard Shortcuts

| Shortcut    | Action           | Expected Result               | Status |
| ----------- | ---------------- | ----------------------------- | ------ |
| Cmd+S       | Save changes     | Save triggered, success toast | ⬜     |
| Cmd+Z       | Undo last change | Previous state restored       | ⬜     |
| Cmd+Shift+Z | Redo change      | Undone change reapplied       | ⬜     |
| Cmd+E       | Toggle Edit Mode | Switch between View/Edit      | ⬜     |
| Esc         | Exit Edit Mode   | Return to View Mode           | ⬜     |

### 1.5 Undo/Redo Functionality

| Test Case             | Steps                          | Expected Result         | Status |
| --------------------- | ------------------------------ | ----------------------- | ------ |
| Undo text edit        | Edit field, press Cmd+Z        | Original text restored  | ⬜     |
| Redo text edit        | Undo change, press Cmd+Shift+Z | Edit reapplied          | ⬜     |
| Undo block reorder    | Reorder, press Cmd+Z           | Original order restored | ⬜     |
| Multiple undos        | Press Cmd+Z 5 times            | Each undo step works    | ⬜     |
| Undo limit (10 steps) | Make 15 edits, undo all        | Only last 10 undoable   | ⬜     |

---

## 2. Block Editors

### 2.1 HeroEditor

| Test Case              | Steps                          | Expected Result                        | Status |
| ---------------------- | ------------------------------ | -------------------------------------- | ------ |
| Edit heading prefix    | Type in "heading prefix" field | Preview updates after 300ms            | ⬜     |
| Edit heading suffix    | Type in "heading suffix" field | Preview updates after 300ms            | ⬜     |
| Add animated word      | Click "+ Add Word" button      | New input appears                      | ⬜     |
| Remove animated word   | Click trash icon on word       | Word removed, preview cycles remaining | ⬜     |
| Reorder animated words | Drag word up/down              | Preview animation order updates        | ⬜     |
| Edit subtext           | Type in "subtext" field        | Preview updates                        | ⬜     |
| Change CTA text        | Edit "cta.text" field          | Button text updates in preview         | ⬜     |
| Change CTA link        | Edit "cta.href" field          | Link updated (not visually testable)   | ⬜     |
| Change image           | Use MediaUploader              | New image appears in preview           | ⬜     |

### 2.2 ServiceCardsEditor

| Test Case                | Steps                            | Expected Result                  | Status |
| ------------------------ | -------------------------------- | -------------------------------- | ------ |
| Edit section heading     | Type in "heading" field          | Preview heading updates          | ⬜     |
| Edit section description | Type in "description" field      | Preview description updates      | ⬜     |
| Expand service accordion | Click service title              | Accordion opens, fields visible  | ⬜     |
| Edit service title       | Edit "title" in expanded service | Preview card title updates       | ⬜     |
| Edit service description | Edit "description" field         | Preview card description updates | ⬜     |
| Add new service          | Click "+ Add Service" button     | New empty service appears        | ⬜     |
| Remove service           | Click trash icon                 | Service removed from preview     | ⬜     |
| Services count display   | Add/remove services              | Count badge updates correctly    | ⬜     |

### 2.3 ProcessStepsEditor

| Test Case                | Steps                       | Expected Result                      | Status |
| ------------------------ | --------------------------- | ------------------------------------ | ------ |
| Edit section heading     | Type in "heading" field     | Preview heading updates              | ⬜     |
| Edit section description | Type in "description" field | Preview description updates          | ⬜     |
| Edit step title          | Edit "title" in step        | Preview step title updates           | ⬜     |
| Edit step description    | Edit "description" in step  | Preview step description updates     | ⬜     |
| Add new step             | Click "+ Add Step" button   | New step appears with correct number | ⬜     |
| Remove step              | Click trash icon            | Step removed, remaining renumbered   | ⬜     |
| Auto-renumbering         | Delete step 2 of 3          | Steps renumbered to 1, 2             | ⬜     |
| Expand/collapse step     | Click step title            | Accordion opens/closes smoothly      | ⬜     |

### 2.4 TeamSectionEditor

| Test Case               | Steps                       | Expected Result                  | Status |
| ----------------------- | --------------------------- | -------------------------------- | ------ |
| Edit section heading    | Type in "heading" field     | Preview heading updates          | ⬜     |
| Edit member name        | Edit "name" field           | Preview card name updates        | ⬜     |
| Edit member role        | Edit "role" field           | Preview card role updates        | ⬜     |
| Edit member description | Edit "description" field    | Preview card description updates | ⬜     |
| Change member image     | Use MediaUploader           | Preview card image updates       | ⬜     |
| Add new member          | Click "+ Add Member" button | New member card appears          | ⬜     |
| Remove member           | Click trash icon            | Member removed from preview      | ⬜     |
| Expand/collapse member  | Click member name           | Accordion opens/closes           | ⬜     |

### 2.5 FAQEditor

| Test Case            | Steps                    | Expected Result                  | Status |
| -------------------- | ------------------------ | -------------------------------- | ------ |
| Edit section heading | Type in "heading" field  | Preview heading updates          | ⬜     |
| Edit question        | Edit "question" field    | Preview question updates         | ⬜     |
| Edit answer          | Edit "answer" field      | Preview answer updates           | ⬜     |
| Add new FAQ          | Click "+ Add FAQ" button | New question/answer pair appears | ⬜     |
| Remove FAQ           | Click trash icon         | FAQ removed from preview         | ⬜     |
| Expand/collapse FAQ  | Click FAQ question       | Accordion opens/closes           | ⬜     |
| Multiple FAQs        | Add 5 FAQs               | All display correctly in preview | ⬜     |

### 2.6 CTASectionEditor

| Test Case               | Steps                        | Expected Result                      | Status |
| ----------------------- | ---------------------------- | ------------------------------------ | ------ |
| Edit heading            | Type in "heading" field      | Preview heading updates              | ⬜     |
| Edit description        | Type in "description" field  | Preview description updates          | ⬜     |
| Edit button text        | Edit "button.text" field     | Preview button text updates          | ⬜     |
| Edit button link        | Edit "button.href" field     | Link updated (not visually testable) | ⬜     |
| Edit background color   | Edit "backgroundColor" field | Preview background color updates     | ⬜     |
| Change background image | Use MediaUploader            | Preview background image updates     | ⬜     |

---

## 3. Real-Time Preview

### 3.1 Debounced Updates

| Test Case       | Steps                       | Expected Result                       | Status |
| --------------- | --------------------------- | ------------------------------------- | ------ |
| Type slowly     | Type 1 char per second      | Each char triggers update after 300ms | ⬜     |
| Type fast       | Type quickly (10 chars/sec) | Only final update after typing stops  | ⬜     |
| Debounce timing | Type, wait 300ms            | Update appears after 300ms            | ⬜     |
| No lag          | Type in text field          | No noticeable input lag               | ⬜     |

### 3.2 Preview Accuracy

| Test Case           | Steps             | Expected Result                            | Status |
| ------------------- | ----------------- | ------------------------------------------ | ------ |
| Hero block matches  | Edit Hero fields  | Preview identical to actual Hero component | ⬜     |
| ServiceCards match  | Edit ServiceCards | Preview identical to actual component      | ⬜     |
| ProcessSteps match  | Edit ProcessSteps | Preview identical to actual component      | ⬜     |
| TeamSection matches | Edit TeamSection  | Preview identical to actual component      | ⬜     |
| FAQ matches         | Edit FAQ          | Preview identical to actual component      | ⬜     |
| CTA matches         | Edit CTA          | Preview identical to actual component      | ⬜     |

### 3.3 Preview Performance

| Test Case         | Steps                       | Expected Result                       | Status |
| ----------------- | --------------------------- | ------------------------------------- | ------ |
| Smooth animations | Edit animated words in Hero | Preview animation smooth (60fps)      | ⬜     |
| No layout shift   | Edit any field              | Preview updates without layout shift  | ⬜     |
| Image loading     | Change image                | Loading state, then smooth transition | ⬜     |
| Scroll sync       | Click block in sidebar      | Preview scrolls to block smoothly     | ⬜     |

---

## 4. Save Functionality

### 4.1 Save to API

| Test Case              | Steps                   | Expected Result                | Status |
| ---------------------- | ----------------------- | ------------------------------ | ------ |
| Save valid changes     | Edit, click Save button | Success toast, API called      | ⬜     |
| Save keyboard shortcut | Edit, press Cmd+S       | Save triggered, success toast  | ⬜     |
| Loading state          | Click Save              | Button shows spinner, disabled | ⬜     |
| Save response time     | Save changes            | Response within 1-2 seconds    | ⬜     |
| Data persistence       | Save, refresh page      | Changes persisted correctly    | ⬜     |

### 4.2 Error Handling

| Test Case                 | Steps                  | Expected Result                     | Status |
| ------------------------- | ---------------------- | ----------------------------------- | ------ |
| Network error             | Stop Laravel API, save | Error toast, retry option           | ⬜     |
| 500 server error          | Trigger server error   | Error toast with details            | ⬜     |
| Timeout                   | Simulate slow network  | Timeout error after 10s             | ⬜     |
| Validation error from API | Send invalid data      | Error toast with validation message | ⬜     |
| Retry after error         | Error occurred, retry  | Second attempt works                | ⬜     |

### 4.3 Loading States

| Test Case            | Steps                  | Expected Result                     | Status |
| -------------------- | ---------------------- | ----------------------------------- | ------ |
| Save button disabled | Click Save             | Button disabled during save         | ⬜     |
| Spinner animation    | Click Save             | Spinner shows, smooth animation     | ⬜     |
| Form inputs disabled | Click Save             | All inputs disabled during save     | ⬜     |
| Quick save           | Save very small change | Loading state still visible briefly | ⬜     |

---

## 5. Media Upload

### 5.1 MediaUploader Component

| Test Case               | Steps                 | Expected Result                   | Status |
| ----------------------- | --------------------- | --------------------------------- | ------ |
| Select image            | Click "Change" button | File picker opens                 | ⬜     |
| Valid image (JPEG)      | Select JPEG image     | Preview shows, no errors          | ⬜     |
| Valid image (PNG)       | Select PNG image      | Preview shows, no errors          | ⬜     |
| Invalid file type (PDF) | Select PDF file       | Error message shown               | ⬜     |
| File too large (>5MB)   | Select 10MB image     | Error message shown               | ⬜     |
| Preview before save     | Select image          | Preview shows immediately         | ⬜     |
| Remove button           | Click "Remove"        | Preview cleared, reset to default | ⬜     |
| Upload progress         | Select image          | Progress indicator shown          | ⬜     |

### 5.2 Laravel API Integration

| Test Case            | Steps              | Expected Result                    | Status |
| -------------------- | ------------------ | ---------------------------------- | ------ |
| Upload endpoint      | Select image, save | POST to /api/media/upload          | ⬜     |
| Success response     | Upload completes   | API returns image URL              | ⬜     |
| Error response       | Backend error      | Error toast with details           | ⬜     |
| Image URL stored     | Save after upload  | Image URL saved in page data       | ⬜     |
| Thumbnail generation | Upload image       | Thumbnail created (if implemented) | ⬜     |

### 5.3 Image Display

| Test Case            | Steps                   | Expected Result           | Status |
| -------------------- | ----------------------- | ------------------------- | ------ |
| Image in Hero        | Upload Hero image       | Image displays in preview | ⬜     |
| Image in TeamSection | Upload member image     | Member card image updates | ⬜     |
| Image in CTA         | Upload CTA background   | Background image updates  | ⬜     |
| Responsive images    | View on different sizes | Images scale correctly    | ⬜     |
| Missing image        | Remove image            | Placeholder/default shown | ⬜     |

---

## 6. Validation

### 6.1 Required Fields

| Test Case            | Steps                     | Expected Result                 | Status |
| -------------------- | ------------------------- | ------------------------------- | ------ |
| Empty required field | Clear heading field, blur | Error message appears           | ⬜     |
| Red border on error  | Field has error           | Red border shown                | ⬜     |
| Error message text   | Validation fails          | Clear error message displayed   | ⬜     |
| Multiple errors      | Multiple empty fields     | All errors shown simultaneously | ⬜     |
| Error clears on fix  | Fill empty field          | Error message disappears        | ⬜     |

### 6.2 Save Blocking

| Test Case             | Steps                        | Expected Result                | Status |
| --------------------- | ---------------------------- | ------------------------------ | ------ |
| Save with errors      | Validation error, click Save | Warning toast, save blocked    | ⬜     |
| Save button state     | Validation errors present    | Save button disabled           | ⬜     |
| Warning toast content | Try to save with errors      | Toast: "Fix validation errors" | ⬜     |
| Save after fixing     | Fix errors, save             | Save succeeds                  | ⬜     |

### 6.3 Field-Specific Validation

| Test Case        | Steps                     | Expected Result           | Status |
| ---------------- | ------------------------- | ------------------------- | ------ |
| URL validation   | Enter invalid URL in href | Error: "Invalid URL"      | ⬜     |
| Email validation | Enter invalid email       | Error: "Invalid email"    | ⬜     |
| Max length       | Exceed max characters     | Error: "Max X characters" | ⬜     |
| Min length       | Below min characters      | Error: "Min X characters" | ⬜     |

---

## 7. Toast Notifications

### 7.1 Toast Types

| Test Case     | Steps             | Expected Result             | Status |
| ------------- | ----------------- | --------------------------- | ------ |
| Success toast | Save successfully | Green toast, checkmark icon | ⬜     |
| Error toast   | Network error     | Red toast, error icon       | ⬜     |
| Warning toast | Validation error  | Yellow toast, warning icon  | ⬜     |
| Info toast    | General message   | Blue toast, info icon       | ⬜     |

### 7.2 Toast Behavior

| Test Case      | Steps                    | Expected Result            | Status |
| -------------- | ------------------------ | -------------------------- | ------ |
| Auto-dismiss   | Toast appears            | Disappears after 5 seconds | ⬜     |
| Manual dismiss | Click X on toast         | Toast closes immediately   | ⬜     |
| Stacking       | Trigger 3 toasts quickly | Max 3 toasts visible       | ⬜     |
| Animation      | Toast appears            | Smooth slide-in animation  | ⬜     |
| Animation exit | Toast auto-dismisses     | Smooth slide-out animation | ⬜     |

### 7.3 Toast Content

| Test Case      | Steps                   | Expected Result                   | Status |
| -------------- | ----------------------- | --------------------------------- | ------ |
| Clear message  | Error occurs            | Message describes error clearly   | ⬜     |
| Action buttons | Toast with action       | Action button works (e.g., Retry) | ⬜     |
| Long messages  | Very long error message | Text wraps correctly, readable    | ⬜     |

---

## 8. Loading States

### 8.1 Page Load

| Test Case          | Steps              | Expected Result                      | Status |
| ------------------ | ------------------ | ------------------------------------ | ------ |
| Skeleton on load   | Navigate to editor | Skeleton loader shown                | ⬜     |
| Skeleton structure | Page loading       | Skeleton mimics final layout         | ⬜     |
| Smooth transition  | Data loads         | Smooth fade from skeleton to content | ⬜     |
| Loading time       | First load         | Content appears within 2 seconds     | ⬜     |

### 8.2 Skeleton Loaders

| Test Case           | Steps                    | Expected Result              | Status |
| ------------------- | ------------------------ | ---------------------------- | ------ |
| Toolbar skeleton    | Page loads               | Toolbar skeleton shown       | ⬜     |
| Block skeleton      | Page loads               | Block skeletons shown        | ⬜     |
| Animation           | Skeleton visible         | Subtle pulse animation       | ⬜     |
| Correct proportions | Skeleton vs real content | Skeleton matches real layout | ⬜     |

### 8.3 Component Loading

| Test Case        | Steps                  | Expected Result       | Status |
| ---------------- | ---------------------- | --------------------- | ------ |
| Editor switching | Switch between editors | Brief loading state   | ⬜     |
| Image loading    | Change image           | Image loading spinner | ⬜     |
| Save loading     | Click Save             | Save button spinner   | ⬜     |

---

## 9. Accessibility

### 9.1 Keyboard Navigation

| Test Case         | Steps                       | Expected Result                       | Status |
| ----------------- | --------------------------- | ------------------------------------- | ------ |
| Tab navigation    | Press Tab repeatedly        | Focus moves logically through UI      | ⬜     |
| Shift+Tab         | Press Shift+Tab             | Focus moves backward correctly        | ⬜     |
| Enter to activate | Focus button, press Enter   | Button activates                      | ⬜     |
| Space to activate | Focus checkbox, press Space | Checkbox toggles                      | ⬜     |
| Focus indicators  | Tab through UI              | Visible focus outline on all elements | ⬜     |

### 9.2 Screen Reader (VoiceOver)

| Test Case           | Steps              | Expected Result                     | Status |
| ------------------- | ------------------ | ----------------------------------- | ------ |
| Heading hierarchy   | VoiceOver on       | Headings announced in correct order | ⬜     |
| Form labels         | Focus input field  | Label read aloud                    | ⬜     |
| Button descriptions | Focus button       | Purpose announced clearly           | ⬜     |
| Error messages      | Validation error   | Error message read aloud            | ⬜     |
| Toast notifications | Toast appears      | Content announced                   | ⬜     |
| Landmarks           | Navigate landmarks | Main, nav, form landmarks present   | ⬜     |

### 9.3 ARIA Labels

| Test Case            | Steps                 | Expected Result              | Status |
| -------------------- | --------------------- | ---------------------------- | ------ |
| Interactive elements | Inspect with DevTools | aria-label on icon buttons   | ⬜     |
| Form inputs          | Inspect form fields   | aria-describedby for errors  | ⬜     |
| Dynamic content      | Content updates       | aria-live for toasts/updates | ⬜     |
| State changes        | Toggle button         | aria-pressed state updates   | ⬜     |

---

## 10. Performance

### 10.1 Console Checks

| Test Case   | Steps                 | Expected Result                       | Status |
| ----------- | --------------------- | ------------------------------------- | ------ |
| No errors   | Open DevTools Console | No red errors                         | ⬜     |
| No warnings | Check Console         | No yellow warnings (or only expected) | ⬜     |
| API calls   | Perform actions       | API calls successful (200 status)     | ⬜     |
| Network tab | Monitor Network       | No failed requests                    | ⬜     |

### 10.2 Memory Leaks

| Test Case              | Steps                     | Expected Result                 | Status |
| ---------------------- | ------------------------- | ------------------------------- | ------ |
| Open/close editors 10x | Switch editors repeatedly | Memory stable (DevTools Memory) | ⬜     |
| Undo/redo 20x          | Undo/redo repeatedly      | Memory stable                   | ⬜     |
| Drag blocks 20x        | Drag blocks repeatedly    | Memory stable                   | ⬜     |
| Long session (30 min)  | Use editor for 30 minutes | No memory growth                | ⬜     |

### 10.3 Animation Performance

| Test Case          | Steps                    | Expected Result         | Status |
| ------------------ | ------------------------ | ----------------------- | ------ |
| 60fps animations   | DevTools Performance tab | Animations at 60fps     | ⬜     |
| No janky scrolling | Scroll preview area      | Smooth scroll, no jank  | ⬜     |
| Smooth transitions | Mode toggle animation    | Smooth 60fps transition | ⬜     |
| Hero word cycling  | Watch Hero animation     | Smooth word transitions | ⬜     |

### 10.4 Load Performance

| Test Case          | Steps                      | Expected Result                   | Status |
| ------------------ | -------------------------- | --------------------------------- | ------ |
| Initial load time  | Hard refresh               | Page interactive <2 seconds       | ⬜     |
| Bundle size        | Check `.next` build output | Additional bundle <500KB          | ⬜     |
| Code splitting     | Network tab                | Editor chunks loaded on-demand    | ⬜     |
| Image optimization | Check images               | Images compressed, modern formats | ⬜     |

---

## Browser Compatibility

### Desktop Browsers

| Browser | Version       | Status | Notes                      |
| ------- | ------------- | ------ | -------------------------- |
| Chrome  | Latest (120+) | ⬜     | Primary target             |
| Safari  | Latest (17+)  | ⬜     | Important for Mac users    |
| Firefox | Latest (121+) | ⬜     | Secondary target           |
| Edge    | Latest        | ⬜     | Chrome engine, should work |

### Mobile Browsers

| Browser       | Device           | Status | Notes                     |
| ------------- | ---------------- | ------ | ------------------------- |
| Safari Mobile | iPhone (iOS 17+) | ⬜     | Critical for mobile admin |
| Chrome Mobile | Android (latest) | ⬜     | Optional but nice to have |

### Browser-Specific Tests

**Safari:**

- [ ] CSS Grid layout works
- [ ] Smooth scrolling works
- [ ] Animations smooth
- [ ] MediaUploader works
- [ ] Keyboard shortcuts work

**Firefox:**

- [ ] Drag-and-drop works
- [ ] Form validation works
- [ ] Toast positioning correct
- [ ] Performance acceptable

---

## Build Verification

### Build Commands

```bash
# 1. Type checking
npx tsc --noEmit

# 2. Linting
npm run lint

# 3. Production build
npm run build

# 4. Bundle analysis (optional)
ANALYZE=true npm run build
```

### Build Checklist

- [ ] TypeScript compilation successful (no errors)
- [ ] ESLint passes (no errors, warnings acceptable if documented)
- [ ] Production build completes without errors
- [ ] Build output size acceptable (<1MB for visual-editor chunk)
- [ ] No tree-shaking issues (components render correctly in build)

---

## Test Scenarios

### Scenario 1: Complete Content Update

**Objective:** Edit all blocks on the page and save

1. Open editor at `/admin/editor/home`
2. Switch to Edit Mode
3. Edit Hero: Change heading, add animated word
4. Edit ServiceCards: Modify first service title
5. Edit ProcessSteps: Edit step 1 description
6. Edit TeamSection: Change first member name
7. Edit FAQ: Edit first question
8. Edit CTA: Change heading
9. Save changes (Cmd+S)
10. Verify success toast
11. Refresh page
12. Verify all changes persisted

**Expected:** All changes saved and persist after refresh

---

### Scenario 2: Block Reordering Workflow

**Objective:** Reorder blocks and verify preview updates

1. Open editor, switch to Edit Mode
2. Drag ServiceCards above Hero
3. Verify preview updates immediately
4. Drag ProcessSteps to top
5. Verify preview updates
6. Press Cmd+Z twice (undo)
7. Verify original order restored
8. Press Cmd+Shift+Z twice (redo)
9. Verify reorder reapplied
10. Save changes
11. Refresh page
12. Verify order persisted

**Expected:** Drag-and-drop works smoothly, undo/redo works, order persists

---

### Scenario 3: Validation and Error Recovery

**Objective:** Trigger validation errors and recover

1. Open editor, switch to Edit Mode
2. Select Hero block
3. Clear "heading prefix" field (required)
4. Blur field
5. Verify error message appears
6. Try to save (Cmd+S)
7. Verify warning toast appears
8. Verify save blocked
9. Fill "heading prefix" field
10. Verify error clears
11. Save again
12. Verify success toast

**Expected:** Validation prevents save, error messages clear, save succeeds after fix

---

### Scenario 4: Media Upload Workflow

**Objective:** Upload and change images

1. Open editor, switch to Edit Mode
2. Select Hero block
3. Click "Change" in image MediaUploader
4. Select valid JPEG image (<5MB)
5. Verify preview updates
6. Click "Save"
7. Verify success toast
8. Select TeamSection block
9. Expand first member accordion
10. Upload member image
11. Verify preview updates
12. Save changes
13. Refresh page
14. Verify both images persisted

**Expected:** Image upload works, preview updates, images persist

---

### Scenario 5: Network Error Recovery

**Objective:** Handle network errors gracefully

1. Open editor, switch to Edit Mode
2. Make some edits (e.g., change Hero heading)
3. Stop Laravel backend: `pkill -9 php` (in backend terminal)
4. Try to save (Cmd+S)
5. Verify error toast appears with retry option
6. Restart Laravel backend: `php artisan serve --port=8001`
7. Click "Retry" in toast (or save again)
8. Verify success toast
9. Verify changes saved

**Expected:** Error handled gracefully, retry works, changes saved after recovery

---

## Performance Benchmarks

### Lighthouse Audit

Run Lighthouse audit on `/admin/editor/home`:

```bash
# In Chrome DevTools
# 1. Open Lighthouse tab
# 2. Select "Desktop"
# 3. Run audit
```

**Target Scores:**

- Performance: >90
- Accessibility: >95
- Best Practices: >90
- SEO: N/A (admin page)

### Bundle Size

Check build output:

```bash
npm run build
# Check .next/static/chunks/ for visual-editor chunks
```

**Target:** Visual editor additional bundle <500KB

### Animation Performance

Use Chrome DevTools Performance tab:

1. Start recording
2. Perform actions (drag blocks, edit fields, toggle modes)
3. Stop recording
4. Check FPS graph

**Target:** Maintain 60fps during animations

---

## Known Issues

Document any known issues found during testing here:

### Issue Template

```markdown
### Issue: [Brief Description]

- **Severity:** Critical | High | Medium | Low
- **Browser:** Chrome 120 | Safari 17 | Firefox 121 | All
- **Steps to Reproduce:**
  1. Step 1
  2. Step 2
  3. Step 3
- **Expected:** What should happen
- **Actual:** What actually happens
- **Status:** Open | In Progress | Fixed | Won't Fix
- **Workaround:** (if any)
```

---

## Sign-Off Checklist

### Functionality

- [ ] All 6 block editors work correctly
- [ ] Drag-and-drop reordering works
- [ ] Undo/Redo works (10 step history)
- [ ] Save functionality works
- [ ] MediaUploader works
- [ ] Validation blocks invalid saves
- [ ] Toast notifications display correctly

### Quality

- [ ] No console errors
- [ ] No memory leaks detected
- [ ] Performance acceptable (60fps animations)
- [ ] Bundle size acceptable (<500KB)
- [ ] Lighthouse scores meet targets

### Accessibility

- [ ] Keyboard navigation works
- [ ] Screen reader compatible (VoiceOver tested)
- [ ] ARIA labels present and correct
- [ ] Focus management correct

### Browser Compatibility

- [ ] Chrome (latest) ✓
- [ ] Safari (latest) ✓
- [ ] Firefox (latest) ✓
- [ ] Mobile Safari ✓

### Documentation

- [ ] TESTING.md complete
- [ ] TEST-REPORT.md created
- [ ] KNOWN-ISSUES.md created (if issues found)
- [ ] DEPLOYMENT-CHECKLIST.md created

---

## Next Steps

After completing all tests:

1. **Document Results:** Create TEST-REPORT.md with findings
2. **Fix Critical Issues:** Address any critical or high-severity bugs
3. **Update KNOWN-ISSUES.md:** Document any remaining issues
4. **Create Deployment Checklist:** Prepare for production deployment
5. **Get Sign-Off:** Review with stakeholders

---

**Tester Signature:** ********\_********
**Date:** ********\_********
**Status:** ⬜ In Progress | ⬜ Complete | ⬜ Blocked
