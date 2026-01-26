# Visual Editor Diagnostic Report

## Current Issues (User Reported)

1. **Can't edit buttons** - Click on buttons doesn't make them editable
2. **Can only select singular letters** - Animation words select per-letter not per-word
3. **Can't scroll** - Page doesn't scroll
4. **Missing content** - Not all original page content imported as editable

## Investigation Needed

### 1. Scrolling Issue

**Hypothesis:** JavaScript event handlers preventing scroll

**Tests to run:**
- Open browser dev tools
- Check if .preview-mode div has overflow-y: scroll
- Check if height is constrained
- Try scrolling with mouse wheel vs dragging scrollbar
- Check console for errors

### 2. Button Editing Issue

**Hypothesis:** Button clicks trigger onClick instead of contentEditable

**Code check:**
```typescript
// Line 68: BUTTON is in the match
target.tagName.match(/^(H[1-6]|P|SPAN|BUTTON|LI|DIV)$/)
```

**Problem:** Buttons have onClick handlers that fire BEFORE contentEditable logic

**Solution needed:** preventDefault on buttons to stop onClick, OR exclude buttons from text editing

### 3. Letter vs Word Selection

**Code check:**
```typescript
// Line 170: pointerEvents set conditionally
pointerEvents: editable ? 'none' : 'auto'
```

**Problem:** `editable` prop might not be passed correctly, or CSS is overriding

**Solution needed:** Verify Hero component receives editable={true}

### 4. Missing Content

**Database check:**
```bash
curl http://localhost:8001/api/pages/home | jq '.content.blocks'
```

**Current blocks:**
- Hero
- ServiceCards
- ProcessSteps
- TeamSection
- FAQ
- CTASection

**Missing from original site (need to verify):**
- Contact section?
- Testimonials?
- Additional content sections?

## Recommended Fix Strategy

1. **STOP** making random fixes
2. **CREATE** a minimal test HTML page with JUST scrolling
3. **VERIFY** scrolling works in isolation
4. **ADD** click-to-edit one element at a time
5. **TEST** each addition separately

## Architecture Question

**Is PreviewMode fundamentally wrong?**

Current approach:
- Loads actual frontend components
- Adds contentEditable via click handlers
- Manages save state manually

Better approaches to research:
- Use TipTap in "bubble menu" mode
- Use Lexical with inline toolbar
- Use iframe with postMessage for isolation
- Use proper WYSIWYG library instead of raw contentEditable
