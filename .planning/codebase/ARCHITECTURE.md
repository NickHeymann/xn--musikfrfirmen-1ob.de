# Architecture

**Analysis Date:** 2026-01-26

## Pattern Overview

**Overall:** Next.js 16 (App Router) + React + TypeScript with dual-mode content delivery

**Key Characteristics:**
- Hybrid rendering: Public static website + authenticated visual editor backend
- Configuration-driven data (single source of truth in `src/config/site.ts`)
- Component-based UI with reusable section modules (Hero, Services, Team, FAQ)
- Context-based state management for modal coordination and editor functionality
- Client-side contact form with external geolocation API integration

## Layers

**Presentation Layer (Components & Pages):**
- Purpose: Renders UI components and orchestrates page layouts
- Location: `src/components/` and `src/app/`
- Contains: React functional components, page layouts, section components
- Depends on: Config, types, data, hooks, contexts
- Used by: Page routes in `src/app/`

**State Management Layer (Contexts & Hooks):**
- Purpose: Manages application state (modals, editor state, authentication, forms)
- Location: `src/components/ModalProvider.tsx`, `src/contexts/`, `src/hooks/`, `src/visual-editor/context/`
- Contains: Context providers, custom hooks (useModal, useContactForm, useEditor, useEditorAuth)
- Depends on: React Context API, types
- Used by: Components, pages, modal orchestration

**Data & Configuration Layer:**
- Purpose: Centralized single source of truth for site data and configuration
- Location: `src/config/site.ts`, `src/data/`, `src/types/`
- Contains: Site configuration (name, contact, navigation), static data (FAQ, team, services), TypeScript type definitions
- Depends on: None (lowest layer)
- Used by: Components, hooks, pages

**API Integration Layer:**
- Purpose: Communication with backend services
- Location: `src/lib/api/client.ts`, `src/app/api/`
- Contains: Fetch client wrapper, API route handlers, error logging endpoint
- Depends on: Environment configuration, error logger
- Used by: Visual editor context, page loader

**Utility & Infrastructure Layer:**
- Purpose: Cross-cutting concerns and debugging
- Location: `src/lib/error-logger.ts`, `src/lib/config.ts`, `src/components/ErrorLoggerInit.tsx`, `src/components/DebugPanel.tsx`
- Contains: Error logging, global error handling, debug utilities
- Depends on: None
- Used by: Layout, components

**Visual Editor Subsystem:**
- Purpose: Drag-and-drop content editor for page customization
- Location: `src/visual-editor/`
- Contains: Editor context, mode management (View/Edit), block editors, templates, sidebar
- Depends on: Data layer, API layer, contexts
- Used by: Admin routes (`src/app/admin/`)

## Data Flow

**Public Website Load Flow:**

1. Browser requests `/` or other public page
2. Next.js App Router matches route to `src/app/page.tsx`
3. Page renders layout from `src/app/layout.tsx` (server-side metadata)
4. Root layout initializes:
   - ModalProvider (enables contact modal)
   - ErrorLoggerInit (sets up global error listeners)
   - DebugPanel (optional debugging)
5. Page content renders composable sections (Hero, ServiceCards, FAQ, TeamSection, etc.)
6. Components pull data from `src/config/site.ts` and `src/data/*.ts`
7. Client-side interactions trigger modal or form handling

**Contact Form Submission Flow:**

1. User clicks "Anfrage stellen" button → openContactModal() via custom event
2. ModalProvider's useEffect listens for "openMFFCalculator" event
3. ContactModal opens with step-based form (useContactForm hook)
4. Form validation per step (dates, emails, required fields)
5. City autocomplete queries Komoot Photon API (external)
6. On submit, generates mailto link with form data → opens email client
7. Success status displays, form resets

**Editor Load Flow (Admin):**

1. Browser requests `/admin/editor/[slug]`
2. AdminLayout wraps with EditorAuthProvider (authentication check)
3. VisualEditorPage (in `src/app/admin/editor/[slug]/page.tsx`):
   - Calls `api.pages.get(slug)` to fetch page data from Laravel backend
   - Enriches blocks with defaults from component files
   - Provides EditorProvider with initial blocks
4. EditorProvider initializes state:
   - mode: 'view' (read-only by default)
   - blocks: Loaded or enriched content
   - history: Undo/redo stack
5. EditorModeRouter conditionally renders ViewMode or EditMode
6. User toggles Edit mode via ModeToggle button
7. EditMode allows inline editing with sidebar controls
8. Changes update EditorContext state, triggering re-renders
9. Save action posts to `http://localhost:8001/api/pages/{slug}`

**State Management:**

- **Modal State:** Managed by ModalProvider context, triggered by custom events or useModal hook
- **Contact Form State:** Managed by useContactForm hook (step, data, errors, submission status)
- **Editor State:** Managed by EditorContext (blocks, mode, history, selection, unsaved changes)
- **Authentication State:** EditorAuthContext manages editor access (server-side or context-based)
- **Error Logging:** Singleton ErrorLogger class captures global errors and unhandled rejections

## Key Abstractions

**useContactForm Hook:**
- Purpose: Encapsulates multi-step form logic with validation
- Examples: `src/components/contact/useContactForm.ts`, `src/components/contact/useCityAutocomplete.ts`
- Pattern: Custom React hook returning state and handlers; separates form logic from UI

**ModalProvider Pattern:**
- Purpose: Centralized modal state and coordination
- Examples: `src/components/ModalProvider.tsx`, `src/components/contact/ContactModal.tsx`
- Pattern: Context provider + hook for accessing modal methods; custom event listener for third-party triggers

**EditorContext Pattern:**
- Purpose: Complex editor state with history, debouncing, and multi-mode rendering
- Examples: `src/visual-editor/context/EditorContext.tsx`
- Pattern: React Context + useCallback for memoized actions; useDebounce for preview updates

**Configuration as Data:**
- Purpose: Single source of truth eliminates scattered config
- Examples: `src/config/site.ts` (site name, email, phone, nav links, packages), `src/data/faq.ts`, `src/data/team.ts`
- Pattern: Export const objects; components import and reference by key

**Error Logger Singleton:**
- Purpose: Global error tracking without infinite loops
- Examples: `src/lib/error-logger.ts`
- Pattern: Class-based singleton with window event listeners; prevents recursive logging

## Entry Points

**Public Website:**
- Location: `src/app/page.tsx`
- Triggers: Direct navigation to domain or subdirectories
- Responsibilities: Renders hero, services, process, team, FAQ sections; coordinates with ModalProvider

**Root Layout:**
- Location: `src/app/layout.tsx`
- Triggers: Every page load (server-rendered)
- Responsibilities: Sets up metadata (SEO), injects JSON-LD schema, initializes providers (ModalProvider, ErrorLoggerInit), wraps children with Header/Footer

**Visual Editor:**
- Location: `src/app/admin/editor/[slug]/page.tsx`
- Triggers: Navigation to `/admin/editor/home` or other page slugs
- Responsibilities: Loads page data from API, provides editor state, switches between view and edit modes

**Admin Layout:**
- Location: `src/app/admin/layout.tsx`
- Triggers: Any `/admin/*` route
- Responsibilities: Wraps admin routes with EditorAuthProvider (authentication guard)

**Contact Modal:**
- Location: `src/components/contact/ContactModal.tsx`
- Triggers: Custom "openMFFCalculator" event or useModal hook
- Responsibilities: Multi-step form orchestration, validation, mailto submission

## Error Handling

**Strategy:** Layered error handling from global to component-level

**Patterns:**

- **Global Error Listeners:** ErrorLogger captures unhandled errors and promise rejections via window event listeners (no infinite loops)
- **API Error Handling:** try-catch blocks in editor context's saveDraft() with user-facing toast notifications
- **Form Validation:** Step-by-step validation with error object accumulation; errors clear when field updates
- **Missing Backend:** Graceful error UI in editor page with instruction to start Laravel API
- **Type Safety:** TypeScript interfaces prevent runtime errors (ContactFormData, SiteConfig, etc.)

## Cross-Cutting Concerns

**Logging:**
- ErrorLogger singleton captures client-side errors, stores last 100 logs in memory
- Debug panel allows manual log export
- `/api/log-error` endpoint receives error submissions (optional backend logging)

**Validation:**
- Form-level validation in useContactForm with per-step rules
- Email format validation with regex
- Date validation against past dates
- Package/guest count validation at form submission

**Authentication:**
- EditorAuthContext wraps admin routes (implementation in `src/contexts/EditorAuthContext.tsx`)
- Protects `/admin/*` pages from unauthorized access
- Likely token-based or session-based (check EditorAuthContext for details)

**API Communication:**
- Centralized fetch wrapper in `src/lib/api/client.ts`
- Hardcoded base URL `http://localhost:8001` (for local Laravel backend)
- PUT requests for draft saves
- GET requests for page data retrieval

**Geolocation:**
- useCityAutocomplete hook integrates Komoot Photon API
- Queries with German bbox filter to limit results
- Filters results to Germany only
- No server-side caching; client-side fetch with debouncing

---

*Architecture analysis: 2026-01-26*
