# Quick Test Guide

**Purpose:** Rapid manual testing checklist for visual editor
**Time Required:** ~15 minutes
**Prerequisites:** Laravel backend running, dev server running

---

## Setup (2 minutes)

```bash
# Terminal 1: Laravel backend
cd laravel-backend-files
php artisan serve --port=8001

# Terminal 2: Next.js dev server
npm run dev

# Browser: Open editor
open http://localhost:3000/admin/editor/home
```

**Verify:**

- [ ] Editor loads without console errors
- [ ] Skeleton loader shows briefly
- [ ] Content appears after loading

---

## Core Functionality (5 minutes)

### Test 1: Mode Toggle (30 seconds)

1. Click "Edit Mode" button
   - [ ] Sidebar appears
   - [ ] Preview updates to show editable state

2. Press `Esc` key
   - [ ] Returns to View Mode
   - [ ] Sidebar disappears

3. Press `Cmd+E`
   - [ ] Toggles back to Edit Mode

**Pass/Fail:** ⬜

---

### Test 2: Block Selection (1 minute)

1. Click each block in BLOCKS tab:
   - [ ] Hero
   - [ ] Service Cards
   - [ ] Process Steps
   - [ ] Team Section
   - [ ] FAQ
   - [ ] CTA Section

2. For each block:
   - [ ] Properties editor switches
   - [ ] Active state highlighted

**Pass/Fail:** ⬜

---

### Test 3: Simple Edit (1 minute)

1. Select Hero block
2. Change "heading prefix" field
3. Wait 300ms
   - [ ] Preview updates
4. Press `Cmd+S`
   - [ ] Success toast appears
5. Refresh page
   - [ ] Change persisted

**Pass/Fail:** ⬜

---

### Test 4: Drag & Drop (1 minute)

1. Drag "Service Cards" above "Hero"
   - [ ] Preview reorders immediately
2. Press `Cmd+Z`
   - [ ] Original order restored
3. Press `Cmd+Shift+Z`
   - [ ] Reorder reapplied

**Pass/Fail:** ⬜

---

### Test 5: Validation (1 minute)

1. Select CTA Section
2. Clear "heading" field (required)
3. Blur field
   - [ ] Error message appears
   - [ ] Red border shown
4. Press `Cmd+S`
   - [ ] Warning toast appears
   - [ ] Save blocked
5. Fill heading field
   - [ ] Error clears
6. Save again
   - [ ] Success toast

**Pass/Fail:** ⬜

---

## Block Editors (5 minutes)

### Test 6: Array Operations (2 minutes)

**Hero - Animated Words:**

1. Select Hero block
2. Click "+ Add Word" button
   - [ ] New input appears
3. Type "Music"
4. Click trash icon on first word
   - [ ] Word removed
   - [ ] Preview updates

**Pass/Fail:** ⬜

---

**ServiceCards - Add/Remove:**

1. Select ServiceCards block
2. Click "+ Add Service"
   - [ ] New accordion appears
3. Expand accordion
4. Fill in title: "Test Service"
5. Click trash icon
   - [ ] Service removed

**Pass/Fail:** ⬜

---

### Test 7: Accordion Behavior (1 minute)

1. Select ProcessSteps block
2. Click first step title
   - [ ] Accordion opens
   - [ ] Fields visible
3. Click second step title
   - [ ] First closes (only one open)
   - [ ] Second opens

**Pass/Fail:** ⬜

---

### Test 8: Media Upload (2 minutes)

1. Select Hero block
2. Scroll to image field
3. Click "Change" button
4. Select a JPEG image (<5MB)
   - [ ] Preview updates immediately
5. Click "Remove" button
   - [ ] Preview resets
6. Upload again
7. Save
   - [ ] Success toast
8. Refresh page
   - [ ] Image persisted

**Pass/Fail:** ⬜

---

## Error Handling (2 minutes)

### Test 9: Network Error (1 minute)

1. Make an edit
2. Stop Laravel backend: `Ctrl+C` in backend terminal
3. Press `Cmd+S`
   - [ ] Error toast appears
   - [ ] Clear error message
4. Restart backend: `php artisan serve --port=8001`
5. Save again
   - [ ] Success toast

**Pass/Fail:** ⬜

---

### Test 10: Toast Behavior (1 minute)

1. Trigger 3 toasts quickly (make 3 edits, save 3 times)
   - [ ] Max 3 toasts visible
   - [ ] Oldest dismissed automatically
2. Wait 5 seconds
   - [ ] Toasts auto-dismiss
3. Trigger a toast, click X
   - [ ] Dismisses immediately

**Pass/Fail:** ⬜

---

## Quick Browser Test (3 minutes)

### Chrome

- [ ] All features work
- [ ] No console errors
- [ ] Smooth animations

### Safari

- [ ] All features work
- [ ] No console errors
- [ ] Drag-and-drop works

### Firefox (optional)

- [ ] All features work
- [ ] Drag-and-drop acceptable

---

## Performance Check (1 minute)

1. Open Chrome DevTools Console
   - [ ] No red errors
   - [ ] No unexpected warnings

2. Open Network tab
   - [ ] All API calls return 200
   - [ ] No failed requests

3. Watch animations
   - [ ] Smooth (no stuttering)
   - [ ] No lag during typing

---

## Final Checklist

### Must Work

- [ ] Edit Mode toggle
- [ ] Block selection
- [ ] Simple edits update preview
- [ ] Save works (Cmd+S)
- [ ] Validation blocks invalid saves
- [ ] Toasts appear

### Should Work

- [ ] Drag-and-drop reordering
- [ ] Undo/Redo (Cmd+Z/Cmd+Shift+Z)
- [ ] Media upload
- [ ] Array operations (add/remove)
- [ ] Error handling (network errors)

### Nice to Have

- [ ] Smooth animations
- [ ] Keyboard shortcuts
- [ ] Auto-dismiss toasts
- [ ] Accordion behavior

---

## Overall Result

**Total Tests:** 10
**Passed:** **_
**Failed:** _**
**Blocked:** \_\_\_

**Ready for Full Testing?** ⬜ Yes | ⬜ No (fix issues first)

---

## Found Issues

(Note any issues discovered during quick test)

1. **Issue:** ********\_\_\_********
   **Severity:** Critical | High | Medium | Low
   **Notes:** ********\_\_\_********

2. **Issue:** ********\_\_\_********
   **Severity:** Critical | High | Medium | Low
   **Notes:** ********\_\_\_********

---

## Next Steps

- [ ] If all tests pass → Proceed to full TESTING.md
- [ ] If critical issues → Fix before continuing
- [ ] If minor issues → Document in KNOWN-ISSUES.md

---

**Tester:** ********\_********
**Date:** ********\_********
**Duration:** **\_** minutes
**Status:** ⬜ Pass | ⬜ Fail | ⬜ Partial
