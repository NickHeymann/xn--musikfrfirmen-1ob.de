# Codebase Concerns

**Analysis Date:** 2026-01-26

## Tech Debt

**Large Component Files (>300 lines):**
- `src/components/TeamSection.tsx` (477 lines) - Inline CSS with complex media queries makes refactoring difficult
- `src/app/ueber-uns/page.tsx` (431 lines) - Page-level component with mixed concerns
- `src/visual-editor/components/RichTextEditor.tsx` (421 lines) - Rich text editor monolith
- `src/components/Hero.tsx` (395 lines) - Slider component with complex animation logic
- `src/visual-editor/sidebar/editors/ServiceCardsEditor.tsx` (369 lines) - Editor component needs extraction

**Impact:** Large files reduce code reusability, make testing harder, increase cognitive load for maintenance.

**Fix approach:** Split into smaller focused components with clear responsibilities. Extract inline styles to separate CSS modules.

---

**Type Safety Issues:**
- `value: any` in `Hero.tsx:13` and other components - Callback prop uses `any` type
- `Record<string, any>` used in error logger and editor context for flexibility but sacrifices type safety
- Multiple components accept optional props without strict type validation

**Impact:** Loss of compile-time type checking, potential runtime errors from incorrect prop types.

**Fix approach:** Replace `any` with specific union types or generics. Use `Zod` (already a dependency) for runtime validation.

---

**Brittle Layout System:**
- `TeamSection.tsx` depends on CSS sibling selectors and specific DOM structure
- Uses hardcoded `top: -200px` positioning with `clip-path` animations
- Layout breaks if HTML structure changes even slightly
- Development notes explicitly warn against adding React State for hover effects

**Impact:** Layout is fragile and resistant to refactoring. Adding new features to TeamSection requires CSS expertise.

**Files:** `src/components/TeamSection.tsx` (lines 114-143), `DEVELOPMENT_NOTES.md`

**Fix approach:** Consider migrating to CSS Grid/Flexbox with more predictable layout engine. Document layout constraints in component JSDoc.

---

## Known Bugs (Fixed but Watch)

**Infinite useEffect Loop (FIXED - 2026-01-18):**
- **File:** `src/components/Hero.tsx`
- **Symptoms:** Page unresponsive, 100% CPU usage
- **Root cause:** `currentIndex` dependency in effect that modified `currentIndex`
- **Current status:** ✅ Fixed with effect separation
- **Watch for:** Similar patterns in other components with timer-based state updates

---

**Error Logging Loop (FIXED - 2026-01-18):**
- **File:** `src/lib/error-logger.ts`
- **Symptoms:** Created 5.7GB log file in 10 minutes
- **Root cause:** Automatic server-side error logging created feedback loop
- **Current status:** ✅ Fixed - logging now client-only with manual export
- **Prevention:** Check that no other automatic error reporting is added

---

## Security Considerations

**Email Privacy in Contact Form:**
- **Risk:** Contact form data passed via `mailto:` link contains PII (name, email, phone)
- **Issue:** Data visible in browser address bar, browser history, and Vercel logs
- **Files:** `src/components/contact/useContactForm.ts` (line 138)
- **Current mitigation:** None
- **Recommendations:**
  1. Implement server-side form submission with encryption
  2. Store form submissions in database with access control
  3. Replace `mailto:` approach with secure API endpoint
  4. Sanitize and validate all form inputs server-side

**Priority:** Medium (for future refactor)

---

**CORS and API Integration:**
- **Risk:** API endpoint hardcoded in `.env.local` as HTTP (not HTTPS)
- **File:** `.env.local` - `NEXT_PUBLIC_API_URL=http://localhost:8001/api`
- **Issue:** Production builds expose localhost URL in public config
- **Current mitigation:** Only localhost in dev
- **Recommendations:**
  1. Use separate environment configs for dev/prod
  2. Ensure production URL uses HTTPS
  3. Store sensitive endpoints in private env vars (not NEXT_PUBLIC_)

---

**Unvalidated Error Metadata:**
- **Risk:** Error logger accepts arbitrary metadata without validation
- **File:** `src/lib/error-logger.ts` (line 14)
- **Issue:** Could capture sensitive user data or PII in error context
- **Current mitigation:** Manual export only (not auto-sent)
- **Recommendations:**
  1. Use Zod to validate error metadata structure
  2. Add sanitization for user data in stack traces
  3. Document what data should/shouldn't be captured

---

## Performance Bottlenecks

**Hero Component Animation Performance:**
- **Problem:** Complex timer-based animation with multiple state updates
- **Files:** `src/components/Hero.tsx` (lines 51-80)
- **Cause:** Three separate useEffect hooks with setInterval, setTimeout chains
- **Current metrics:** Not measured
- **Improvement path:**
  1. Consider using requestAnimationFrame for smoother animations
  2. Profile component with DevTools to measure frame rate
  3. Consider Framer Motion for better performance (already a dependency)

---

**TeamSection Layout Calculations:**
- **Problem:** Complex clip-path with transform animations may cause layout reflows
- **Files:** `src/components/TeamSection.tsx` (lines 109-143)
- **Current cost:** Not measured, but multiple media queries redefine same styles
- **Improvement path:**
  1. Consolidate media query rules
  2. Use CSS containment (`contain: layout`) to prevent layout thrashing
  3. Test with Lighthouse performance audit

---

**Bundle Size - Editor Package:**
- **Concern:** Visual editor imports heavy dependencies (Tiptap, dnd-kit, Destack)
- **Dependencies:** Multiple editor-related packages total significant KB
- **Impact:** Even if editor not used, imports included in page bundles
- **Improvement path:**
  1. Dynamic import editor components with `next/dynamic`
  2. Code split visual editor into separate lazy-loaded bundle
  3. Measure with `next/bundle-analyzer`

---

## Fragile Areas

**EditorContext State Management:**
- **Files:** `src/visual-editor/context/EditorContext.tsx`
- **Why fragile:**
  - Complex nested state updates through callbacks
  - History/undo system depends on strict immutability
  - Debounced blocks state could diverge from main state
- **Safe modification:** Always use immutable patterns, test undo/redo thoroughly before changes
- **Test coverage:** Needs dedicated tests for state transitions

---

**Visual Editor Block Rendering:**
- **Files:** `src/visual-editor/modes/EditMode.tsx`, `src/visual-editor/components/TemplateLibrary.tsx`
- **Why fragile:**
  - Dynamic component rendering based on block type
  - Props passed as `Record<string, unknown>` (untyped)
  - No validation that required block props exist
- **Safe modification:** Add runtime prop validation with Zod before rendering
- **Test coverage:** Test edge cases like missing props, invalid block types

---

**ContactForm Multi-Step State:**
- **Files:** `src/components/contact/useContactForm.ts`
- **Why fragile:**
  - Manual step validation without centralized schema
  - Three separate validation functions with duplicated logic
  - Regex validation for email is basic (should use library)
- **Safe modification:** Extract validation to Zod schema used across all steps
- **Test coverage:** Add tests for each validation function and integration tests for step flow

---

## Scaling Limits

**Static Data in Memory:**
- **Current:** All data (FAQ, team, services) imported statically at build time
- **Limit:** Works fine for ~10 team members, ~20 FAQ items. Exceeds UX with >50 items
- **Scaling path:** Migrate to database + dynamic imports when content reaches 50+ items

---

**Client-Side Error Logging:**
- **Current:** Stores max 100 errors in memory
- **Limit:** On high-traffic pages, errors expire in ~5-10 minutes before user exports
- **Scaling path:** Implement persistent storage (localStorage first, then server-side logging with proper rate limiting)

---

**Visual Editor Template System:**
- **Current:** Template library embedded in component
- **Limit:** Adding templates requires code changes, can't be updated without redeploy
- **Scaling path:** Move templates to database or JSON file served from API

---

## Dependencies at Risk

**Tiptap (Rich Text Editor):**
- **Risk:** Heavy library (v3.15.3) for relatively simple text editing needs
- **Impact:** Significant bundle size impact for one editor feature
- **Migration plan:** Evaluate simpler alternatives or implement custom ContentEditable wrapper

---

**Destack (Visual Page Builder):**
- **Risk:** Niche library with limited community support compared to alternatives
- **Impact:** Could become maintenance burden if library abandoned
- **Migration plan:** Have plan to replace with dnd-kit + custom components if needed (dnd-kit already in use)

---

**Next.js 16 - Turbopack Disabled:**
- **Risk:** Turbopack has known memory leaks causing page unresponsiveness
- **Current workaround:** Disabled in `next.config.ts` (line 7)
- **Impact:** Project uses slower Webpack builder. Performance penalty ~10-15% build time
- **Resolution:** Monitor Next.js 17+ releases for Turbopack fixes before re-enabling

---

## Missing Critical Features

**Error Reporting to Backend:**
- **Problem:** No production error monitoring. Errors only visible to users who export Debug Panel
- **Blocks:** Can't detect issues in production until users report them
- **Implementation:** Integrate with Sentry or similar service with proper error sampling to avoid loops

---

**Form Submission Tracking:**
- **Problem:** Contact form uses `mailto:` - no submission record or analytics
- **Blocks:** Can't measure conversion rate or identify lost submissions
- **Implementation:** Add server-side form submission with database storage + email notification

---

**Analytics Integration:**
- **Current:** Only Vercel Analytics (basic)
- **Missing:** Page interaction tracking, conversion funnels, user flow analysis
- **Implementation:** Add event tracking via Vercel Web Analytics or third-party service

---

## Test Coverage Gaps

**useContactForm Hook:**
- **What's not tested:** Multi-step form flow, validation logic, field updates
- **Files:** `src/components/contact/useContactForm.ts` (232 lines)
- **Risk:** Regression in contact form without tests could break lead generation
- **Priority:** High

---

**Hero Slider Animation:**
- **What's not tested:** Timer logic, state transitions, edge cases (rapid clicks, component unmount)
- **Files:** `src/components/Hero.tsx`
- **Risk:** Similar infinite loop issues could resurface
- **Priority:** High

---

**EditorContext State Machine:**
- **What's not tested:** Undo/redo sequences, block operations, debounce behavior
- **Files:** `src/visual-editor/context/EditorContext.tsx` (206+ lines)
- **Risk:** Complex state changes without tests cause data loss or corruption
- **Priority:** High

---

**Visual Editor Integration:**
- **What's not tested:** End-to-end: load page → edit content → save → reload
- **Files:** `src/app/admin/editor/[slug]/page.tsx`, EditorContext, block renderers
- **Risk:** Silent failures in editor workflow
- **Priority:** Medium (Playwright tests exist but coverage unclear)

---

**Form Validation:**
- **What's not tested:** Email regex, date validation, privacy checkbox requirements
- **Files:** `src/components/contact/useContactForm.ts` (lines 35-90)
- **Risk:** Invalid data submitted or false rejections
- **Priority:** Medium

---

## Code Quality Issues

**Console Logs in Production:**
- **Issue:** Multiple `console.log()` and `console.error()` calls left in code
- **Files:** `src/components/Footer.tsx` (4 logs), `src/app/admin/pages/page.tsx` (multiple)
- **Impact:** Information leakage, noise in browser console for users
- **Recommendation:** Remove or replace with proper logging service (use error-logger module)

---

**Hardcoded Spacing Values:**
- **Issue:** Padding/margin values hardcoded directly in inline styles and media queries
- **Files:** `src/components/TeamSection.tsx` (477 lines of inline CSS)
- **Impact:** Spacing changes require editing CSS in component, no single source of truth
- **Recommendation:** Extract to CSS variables or Tailwind config

---

**String Interpolation for Data:**
- **Issue:** Contact form builds email body with string templates
- **File:** `src/components/contact/useContactForm.ts` (line 114-132)
- **Impact:** No validation of special characters, potential encoding issues
- **Recommendation:** Use template library or structured email service (n8n integration recommended)

---

**Missing Documentation:**
- **Gap:** No JSDoc comments on complex components
- **Gap:** No prop documentation for large components like Hero, TeamSection
- **Gap:** Visual editor component graph not documented
- **Recommendation:** Add TSDoc comments to all exported components and utilities

---

---

*Concerns audit: 2026-01-26*
