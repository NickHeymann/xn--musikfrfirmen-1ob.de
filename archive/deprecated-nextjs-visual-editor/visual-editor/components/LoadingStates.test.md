# Loading States - Visual Test Checklist

## Test Environment

```bash
npm run dev
# Navigate to: http://localhost:3000/admin/editor/home
```

## Test Cases

### ✅ Test 1: Save Button Loading State

**Steps:**

1. Navigate to `/admin/editor/home`
2. Click "Edit" mode toggle
3. Edit any text field
4. Click "Save" button (⌘S)

**Expected:**

- ✓ Button shows spinner (sm size, 16px)
- ✓ Button text changes to "Saving..."
- ✓ Button is disabled during save
- ✓ Spinner rotates smoothly (0.6s animation)
- ✓ After save: Button returns to normal
- ✓ Success toast appears

**Actual:**

- [ ] Spinner appears
- [ ] Smooth 60fps animation
- [ ] Button disabled
- [ ] Toast notification works

---

### ✅ Test 2: Page Load Skeleton

**Steps:**

1. Navigate to `/admin/editor/home`
2. Hard refresh (⌘R) or open in incognito
3. Observe loading state

**Expected:**

- ✓ Skeleton loaders appear immediately
- ✓ Shimmer animation (1.5s loop)
- ✓ Three skeleton blocks:
  - 400px height (Hero)
  - 300px height (Services)
  - 200px height (CTA)
- ✓ Rounded corners (8px radius)
- ✓ Smooth transition to content

**Actual:**

- [ ] Skeletons render instantly
- [ ] Shimmer effect works
- [ ] Layout matches content
- [ ] Smooth transition

---

### ✅ Test 3: Preview Debounce (Typing)

**Steps:**

1. Edit mode → Select Hero block
2. Type in "Title" field continuously
3. Observe preview pane

**Expected:**

- ✓ "Preview updating..." hint appears
- ✓ No flicker/jank during typing
- ✓ Preview updates 300ms after stop typing
- ✓ Smooth transition

**Actual:**

- [ ] No visual jank
- [ ] Debounce works (300ms)
- [ ] Updating hint visible
- [ ] Smooth UX

---

### ✅ Test 4: Undo/Redo Disabled States

**Steps:**

1. Fresh page load (no history)
2. Observe Undo/Redo buttons

**Expected:**

- ✓ Undo button disabled (opacity: 0.3)
- ✓ Redo button disabled
- ✓ Make a change → Undo enabled
- ✓ Click Undo → Redo enabled

**Actual:**

- [ ] Disabled state clear
- [ ] State updates correctly
- [ ] Cursor: not-allowed

---

### ✅ Test 5: Mobile Responsiveness

**Steps:**

1. Open DevTools → Device mode
2. Select iPhone 14 Pro
3. Test all loading states

**Expected:**

- ✓ Spinner scales correctly
- ✓ Skeletons fill width
- ✓ No horizontal scroll
- ✓ Touch-friendly hit areas

**Actual:**

- [ ] Mobile layout works
- [ ] Loading states visible
- [ ] No layout shifts

---

### ✅ Test 6: Dark Mode

**Steps:**

1. System → Enable Dark Mode
2. Refresh editor

**Expected:**

- ✓ Spinner color visible on dark bg
- ✓ Skeleton gradient updated:
  - #2C2C2E (dark gray)
  - #3A3A3C (lighter gray)
- ✓ Shimmer still visible
- ✓ Contrast meets WCAG AA

**Actual:**

- [ ] Dark mode skeletons work
- [ ] Spinner visible
- [ ] Good contrast

---

### ✅ Test 7: Accessibility (Screen Reader)

**Steps:**

1. Enable VoiceOver (⌘F5)
2. Navigate to save button
3. Trigger save

**Expected:**

- ✓ Spinner: "Loading" announced
- ✓ Button: "Saving..." announced
- ✓ Skeleton: "Loading content" announced
- ✓ aria-busy="true" during load

**Actual:**

- [ ] Screen reader announces
- [ ] ARIA labels correct
- [ ] Loading state clear

---

### ✅ Test 8: Performance (60fps)

**Steps:**

1. Chrome DevTools → Performance
2. Start recording
3. Click Save → observe spinner
4. Stop recording

**Expected:**

- ✓ Consistent 60fps (16.6ms frames)
- ✓ No layout thrashing
- ✓ GPU-accelerated animation
- ✓ No JavaScript frame drops

**Actual:**

- [ ] 60fps maintained
- [ ] Smooth rendering
- [ ] No jank

---

### ✅ Test 9: Error State

**Steps:**

1. Disconnect from API server
2. Click Save

**Expected:**

- ✓ Spinner appears during attempt
- ✓ Error toast shows
- ✓ Spinner disappears
- ✓ Button re-enabled

**Actual:**

- [ ] Error handled gracefully
- [ ] Loading state clears
- [ ] User can retry

---

### ✅ Test 10: Multiple Saves (Rapid Clicks)

**Steps:**

1. Edit text
2. Click Save rapidly (5x)

**Expected:**

- ✓ Only one save executes
- ✓ Button stays disabled
- ✓ Spinner visible throughout
- ✓ No duplicate API calls

**Actual:**

- [ ] Button protection works
- [ ] Single save only
- [ ] Clean state management

---

## Regression Tests

### After Code Changes

- [ ] npm run build → succeeds
- [ ] No TypeScript errors
- [ ] No console errors
- [ ] All 10 tests pass

### Browser Compatibility

- [ ] Chrome/Edge (Chromium)
- [ ] Firefox
- [ ] Safari
- [ ] Mobile Safari

---

## Performance Benchmarks

| Metric               | Target    | Actual     |
| -------------------- | --------- | ---------- |
| Spinner animation    | 60fps     | \_\_\_ fps |
| Skeleton shimmer     | 60fps     | \_\_\_ fps |
| Save button response | <100ms    | \_\_\_ ms  |
| Page load skeleton   | <16ms TTI | \_\_\_ ms  |
| Debounce preview     | 300ms     | \_\_\_ ms  |

---

## Known Issues

### Issue 1: Spinner in Safari

**Status:** ✅ Fixed
**Fix:** Added explicit border-radius

### Issue 2: Skeleton flicker on slow network

**Status:** 🔄 Investigating
**Workaround:** Min-height on container

---

## Sign-off

**Tested by:** ******\_\_\_******  
**Date:** ******\_\_\_******  
**Build:** ******\_\_\_******  
**Status:** [ ] Pass [ ] Fail

**Notes:**

---

---

---
