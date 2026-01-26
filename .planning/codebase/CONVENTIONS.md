# Coding Conventions

**Analysis Date:** 2026-01-26

## Naming Patterns

**Files:**
- Components (React): PascalCase (e.g., `Hero.tsx`, `ContactModal.tsx`, `ProcessSteps.tsx`)
- Utilities/helpers: camelCase (e.g., `error-logger.ts`, `config.ts`)
- Hooks: `use` prefix + camelCase (e.g., `useContactForm.ts`, `useEditorAuth.ts`)
- Data files: camelCase (e.g., `faq.ts`, `team.ts`, `services.ts`)
- Test files: Suffix with `.spec.js` (e.g., `visual-editor.spec.js`)
- Page routes: kebab-case (e.g., `/ueber-uns`, `/datenschutz`)

**Functions & Variables:**
- camelCase for all functions and variables (e.g., `handleScrollPromptClick`, `fetchSuggestions`, `updateField`)
- Private class methods prefixed with underscore: `_editableProps` (marks as editor-only)
- Constants: UPPERCASE_SNAKE_CASE where appropriate (animation delays, z-index values)
- Event handlers prefixed with `handle`: `handleNext`, `handlePrev`, `handleSubmit`

**Types:**
- Interfaces: PascalCase with `Props` suffix for component props (e.g., `HeroProps`, `ContactModalProps`, `HeaderProps`)
- Type aliases: PascalCase (e.g., `PackageType = "dj" | "band" | "band_dj"`)
- Generic types follow PascalCase (e.g., `Record<string, string>`)

**Variables (React State):**
- State hooks follow descriptive pattern: `const [resourceName, setResourceName] = useState()`
- Error states: `[errors, setErrors]` for object maps
- Submission states: `[isSubmitting, setIsSubmitting]`, `[submitStatus, setSubmitStatus]`

## Code Style

**Formatting:**
- Tool: ESLint 9 with Next.js TypeScript configuration
- Config file: `eslint.config.mjs`
- JSX spacing: 2-space indentation
- Line length: No strict enforced limit (visible in components like `Hero.tsx` with long className strings)
- Trailing commas: Enabled (visible in all config and type definitions)

**Linting:**
- ESLint config chain: `nextVitals` → `nextTs` (combines Next.js core web vitals + TypeScript rules)
- ESLint rule violation example: `eslint-disable-next-line react-hooks/immutability` used when mutating window.location intentionally
- No custom rule overrides beyond Next.js defaults

**Semicolons:** Always present in TypeScript/React code

**Quote Style:** Double quotes for JSX attributes and strings (e.g., `className="..."`, `"use client"`)

## Import Organization

**Order:**
1. React/Next.js core imports (e.g., `import { useState, useRef } from "react"`)
2. Next.js utilities (e.g., `import Link from "next/link"`, `import { useRouter } from "next/navigation"`)
3. Third-party dependencies (e.g., `import { useEffect } from "@tiptap/react"`, `import clsx from "clsx"`)
4. Internal components/utilities (e.g., `import { CloseIcon } from "@/components/icons"`)
5. Internal hooks (e.g., `import { useContactForm } from "./useContactForm"`)
6. Type imports (e.g., `import type { ContactFormData } from "@/types"`)

**Path Aliases:**
- `@/*` maps to `./src/*`
- Used consistently: `@/components`, `@/config`, `@/data`, `@/lib`, `@/types`, `@/hooks`
- Import examples: `import { siteConfig } from "@/config/site"`, `import type { HeroProps } from "@/types"`

## Error Handling

**Pattern:**
- Try/catch blocks used for async operations and API calls
- Error logging: `console.error('Failed to [action]:', error)` followed by user-facing alert
- Specific error cases in `useContactForm.ts`: Regex validation for email, date validation with timezone safety
- Error logger class in `src/lib/error-logger.ts`: Captures unhandled errors and promise rejections globally
- Global error handlers:
  - `window.addEventListener('error', ...)` for synchronous errors
  - `window.addEventListener('unhandledrejection', ...)` for unhandled promise rejections
  - Note: Does NOT intercept `console.error` to avoid infinite loops

**Error States in Components:**
- Forms track errors per field: `Record<string, string>` (see `useContactForm.ts`)
- Validation errors set on blur/submit: `setErrors(newErrors)`
- Field errors cleared when user updates: `setErrors((prev) => ({ ...prev, [field]: "" }))`
- API errors handled with try/catch + user feedback: Alert on failure, set error boundary state

**Async Error Boundary Pattern:**
```typescript
try {
  const data = await api.pages.get(slug);
  setPageData(data);
} catch (err) {
  console.error('Failed to load page:', err);
  setError('Failed to load page. Make sure the Laravel backend is running.');
}
```

## Logging

**Framework:** Console-based (no external logging framework)

**Patterns:**
- `console.error('context:', error)` for errors
- `console.info('[ErrorLogger]', log)` for structured info (see `error-logger.ts`)
- `console.log('\n🧪 TEST: [Test Name]')` for test logging in Playwright (emoji + phase prefix)
- Error logger class manually manages logs in array: `logs: ErrorLog[]` (kept to `maxLogs = 100`)
- Server-to-client errors: Endpoint `/api/log-error` supports POST (optional feature)
- Error export: `errorLogger.exportLogs()` creates downloadable JSON file

## Comments

**When to Comment:**
- Document complex logic (e.g., animation timing, form validation logic)
- Document browser compatibility concerns (e.g., "Force video to play on mount (browser autoplay policies)")
- Document gotchas and intentional deviations (e.g., "DO NOT intercept console.error - causes infinite loops!")
- Warn about potential infinite loops in error handling

**JSDoc/TSDoc:**
- Not consistently used across codebase
- Type definitions serve as documentation (interfaces are well-defined in `src/types/index.ts`)
- File headers used for high-level context (e.g., `// =====================================================`)

**Example from code:**
```typescript
// TODO: Add DOMPurify sanitization for production
// Force video to play on mount (browser autoplay policies)
// Prevent infinite loops
// DO NOT intercept console.error - causes infinite loops!
```

## Function Design

**Size Guideline:**
- Target: 50-150 lines per component
- Observed: Most components 150-400+ lines (larger components like `Hero.tsx` 395 lines, `TeamSection.tsx` 477 lines)
- Larger components contain JSX structure + inline styling logic
- Consider splitting when component exceeds 300 lines with multiple concerns

**Parameters:**
- Destructured component props (e.g., `{ sliderContent = [...], backgroundVideo = "..." }`)
- Default values provided inline: `= {}` for default empty objects
- Rest parameters for flexible props spreading

**Return Values:**
- Components return JSX (or `null` for conditional rendering)
- Hooks return objects with named methods: `{ step, formData, errors, handleNext, handlePrev, ... }`
- Utility functions return typed values with proper null/undefined handling

**Example Hook Return:**
```typescript
return {
  suggestions,
  showSuggestions,
  activeIndex,
  fetchSuggestions,
  hideSuggestions,
  setSuggestions,
  setShowSuggestions,
};
```

## Module Design

**Exports:**
- Default exports: Used for page components and main UI components (`export default function Hero(...)`)
- Named exports: Used for utilities, hooks, and types (`export function useContactForm()`, `export const siteConfig`)

**Barrel Files:**
- Components: `src/components/icons/index.tsx` (re-exports all icon components)
- Types: `src/types/index.ts` (centralizes all type definitions)
- Not extensively used; each module typically stands alone

**Module Organization:**
- Single Responsibility: Each hook handles one concern (useContactForm, useCityAutocomplete, useEditorAuth)
- Configuration as Source of Truth: `src/config/site.ts` contains all site-wide data (name, domain, email, nav links, etc.)
- Data layer: `src/data/` contains static data files (faq.ts, team.ts, services.ts, jsonLd.ts)

## Styling

**Approach:** Tailwind CSS 4 + inline CSS modules

**Inline CSS in JSX:**
- Style attributes for dynamic values: `style={{ fontFamily: "'Poppins', sans-serif", marginBottom: "160px" }}`
- Animation delays computed: `style={{ animationDelay: \`${index * 0.04 + 0.05}s\` }}`
- Colors often inline for component-specific themes: `style={{ backgroundColor: "rgba(0, 0, 0, 0.36)" }}`

**CSS-in-JS (Styled JSX):**
- `<style jsx>{...}</style>` blocks for scoped animations and complex selectors
- Used in: `Hero.tsx`, `ProcessSteps.tsx` (complex animation definitions)
- Keyframes defined within style blocks: `@keyframes letterFade`, `@keyframes holderAnimation`

**Tailwind Classes:**
- Utility-first approach: `className="relative flex items-center justify-center overflow-hidden"`
- Responsive variants: `className="md:px-[30px]"`, `@media (max-width: 768px)`
- Custom spacing: `className="mb-[160px]"` (square bracket syntax for custom values)
- Hover states: `hover:bg-[#B2EAD8]`, `hover:opacity-70`, `hover:shadow-[...]`

**Colors:**
- Hex codes used: `#B2EAD8` (teal accent), `#1a1a1a` (dark text), `#292929` (dark button)
- Tailwind opacity: `bg-black/10`, `text-white/60`
- CSS variables: Not extensively used; colors hardcoded in components

## TypeScript Strict Mode

**Setting:** `"strict": true` in `tsconfig.json`

**Implications:**
- No implicit `any` types
- All function parameters require types or type inference
- Generic types used: `<K extends keyof ContactFormData>` for generic state updates
- Type narrowing required: `typeof window !== 'undefined'` checks before accessing browser APIs
- JSX strict mode: `"jsx": "react-jsx"` (React 17+ new transform)

## Special Patterns

**"use client" Declarations:**
- All interactive components marked: `"use client"` at file top
- Examples: `Hero.tsx`, `Header.tsx`, `ContactModal.tsx`, pages with state/events

**Editable Props Pattern:**
- Components accept `editable?: boolean` and `_editableProps?` for editor mode
- Editor integration: `onContentChange(blockId, path, value)` callback pattern
- `data-editable` attributes mark editable regions (e.g., `"data-editable": "sliderPrefix"`)
- Conditional rendering: `{...(editable && { contentEditable: _editableProps?.isEditing })}`

**API Integration:**
- Centralized in `src/lib/api/` (implied from imports like `api.pages.list()`)
- Error handling: Try/catch with user-friendly messages
- Timeout handling: 2000ms timeout for fetch requests with AbortController

---

*Convention analysis: 2026-01-26*
