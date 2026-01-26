# Codebase Structure

**Analysis Date:** 2026-01-26

## Directory Layout

```
src/
├── app/                           # Next.js App Router (routes & API)
│   ├── layout.tsx                 # Root layout (metadata, providers, Header/Footer)
│   ├── page.tsx                   # Home page (main website content)
│   ├── admin/                     # Admin routes (protected by EditorAuthProvider)
│   │   ├── layout.tsx             # Admin layout wrapper
│   │   ├── login/page.tsx         # Login page
│   │   ├── pages/page.tsx         # Pages management dashboard
│   │   └── editor/[slug]/page.tsx # Visual editor (main interface)
│   ├── api/                       # API routes (server functions)
│   │   └── log-error/route.ts     # Error logging endpoint
│   ├── datenschutz/page.tsx       # Privacy policy page
│   ├── impressum/page.tsx         # Legal imprint page
│   ├── referenz/page.tsx          # References/portfolio page
│   ├── ueber-uns/page.tsx         # About us page
│   ├── robots.ts                  # SEO robots.txt generator
│   ├── sitemap.ts                 # SEO sitemap generator
│   └── globals.css                # Global Tailwind styles
│
├── components/                    # Reusable React components
│   ├── Header.tsx                 # Navigation header
│   ├── Footer.tsx                 # Footer with links
│   ├── Hero.tsx                   # Hero section (main banner)
│   ├── ServiceCards.tsx           # Service offerings grid
│   ├── ProcessSteps.tsx           # Process/workflow steps
│   ├── TeamSection.tsx            # Team members showcase
│   ├── TeamMemberCard.tsx         # Individual team member card
│   ├── FAQ.tsx                    # FAQ accordion section
│   ├── LogoAnimation.tsx          # Animated logo display
│   ├── HamburgAnimation.tsx       # Hamburg city animation
│   ├── CTASection.tsx             # Call-to-action section
│   ├── TextBlock.tsx              # Generic text block
│   ├── ModalProvider.tsx          # Modal state provider
│   ├── ContactModal.tsx           # Contact form modal (moved to contact/)
│   ├── DebugPanel.tsx             # Debug controls (error logs, feature flags)
│   ├── ErrorLoggerInit.tsx        # Global error listener initialization
│   ├── contact/                   # Contact form subsystem
│   │   ├── index.ts               # Barrel export
│   │   ├── ContactModal.tsx       # Modal wrapper for contact form
│   │   ├── ContactStep1.tsx       # Step 1: Event details
│   │   ├── ContactStep2.tsx       # Step 2: Package selection
│   │   ├── ContactStep3.tsx       # Step 3: Contact info
│   │   ├── ContactSuccess.tsx     # Success screen after submission
│   │   └── useContactForm.ts      # Form state + validation hook
│   └── icons/                     # SVG icon components
│       └── index.tsx              # Icon component exports
│
├── config/                        # Configuration (single source of truth)
│   └── site.ts                    # Site metadata: name, email, phone, nav, packages
│
├── contexts/                      # React Context providers
│   └── EditorAuthContext.tsx      # Editor authentication context
│
├── data/                          # Static data files
│   ├── faq.ts                     # FAQ questions & answers
│   ├── team.ts                    # Team members
│   ├── services.ts                # Service/process steps
│   ├── jsonLd.ts                  # JSON-LD schema generation
│   └── (additional data files)
│
├── hooks/                         # Custom React hooks
│   └── useEditorAuth.ts           # Hook for accessing editor auth state
│
├── lib/                           # Utilities & libraries
│   ├── api/                       # API integration
│   │   └── client.ts              # Fetch wrapper for backend calls
│   ├── config.ts                  # Runtime config (env vars)
│   └── error-logger.ts            # Global error logging
│
├── types/                         # TypeScript type definitions
│   ├── index.ts                   # Core types (FAQ, Team, Contact, Site, etc.)
│   └── visual-editor.ts           # Editor-specific types
│
├── visual-editor/                 # Visual editor subsystem (admin editing)
│   ├── components/                # Editor UI components
│   │   ├── ArrayInput.tsx         # Form input for arrays
│   │   ├── MediaUploader.tsx      # Image/media upload
│   │   ├── RichTextEditor.tsx     # WYSIWYG text editor
│   │   ├── ModeToggle.tsx         # View/Edit mode switcher
│   │   ├── PageNavigation.tsx     # Page selector in editor
│   │   ├── Toast.tsx              # Notification toasts
│   │   ├── Spinner.tsx            # Loading spinner
│   │   ├── SkeletonLoader.tsx     # Skeleton placeholder
│   │   ├── TemplateLibrary.tsx    # Block template picker
│   │   ├── TemplatePreviewModal.tsx # Template preview
│   │   └── SaveTemplateModal.tsx  # Save custom template
│   │
│   ├── context/                   # Editor state management
│   │   ├── EditorContext.tsx      # Main editor state (blocks, mode, history)
│   │   ├── EditorModeContext.tsx  # View/Edit mode coordination
│   │   ├── ToastContext.tsx       # Notification toast state
│   │   └── ValidationContext.tsx  # Form validation state
│   │
│   ├── modes/                     # Editor rendering modes
│   │   ├── ViewMode.tsx           # Read-only preview
│   │   └── EditMode.tsx           # Editable mode with inline controls
│   │
│   ├── sidebar/                   # Editor sidebar
│   │   ├── EditorSidebar.tsx      # Main sidebar container
│   │   ├── BlockList.tsx          # List of page blocks
│   │   └── editors/               # Per-block editors
│   │       ├── HeroEditor.tsx
│   │       ├── ServiceCardsEditor.tsx
│   │       ├── ProcessStepsEditor.tsx
│   │       ├── TeamSectionEditor.tsx
│   │       ├── FAQEditor.tsx
│   │       └── CTASectionEditor.tsx
│   │
│   ├── hooks/                     # Editor-specific hooks
│   │   ├── useCustomTemplates.ts  # Custom template management
│   │   ├── useKeyboardShortcuts.ts # Keyboard input handling
│   │   └── useValidation.ts       # Validation logic
│   │
│   ├── lib/                       # Editor utilities
│   │   └── defaultBlockData.ts    # Default data enrichment
│   │
│   ├── data/                      # Editor data
│   │   └── blockTemplates.ts      # Pre-built block templates
│   │
│   ├── types/                     # Editor types
│   │   ├── types.ts               # Block, Editor state types
│   │   └── blockTemplate.ts       # Block template types
│   │
│   ├── styles/                    # Editor-specific CSS
│   │   └── apple-editor.css       # Apple-style editor theme
│   │
│   └── docs/                      # Editor documentation
│       └── examples/              # Component examples
│
├── contexts/                      # Auth context
│   └── EditorAuthContext.tsx      # Authentication for admin
│
├── global.d.ts                    # Global TypeScript declarations
└── (other root-level files)
```

## Directory Purposes

**`src/app/`:**
- Purpose: Next.js App Router — routes, layouts, pages, API endpoints
- Contains: Page files (`page.tsx`), layouts, API routes
- Key files: `layout.tsx` (root metadata), `page.tsx` (home), `admin/editor/[slug]/page.tsx` (editor)

**`src/components/`:**
- Purpose: Reusable React UI components
- Contains: Presentational components, modals, sections
- Key files: `Header.tsx`, `Hero.tsx`, `ServiceCards.tsx`, `ModalProvider.tsx`, `contact/`

**`src/config/`:**
- Purpose: Single source of truth for site configuration
- Contains: `site.ts` — name, domain, email, phone, nav links, packages
- Convention: Export const objects; components import and use directly

**`src/data/`:**
- Purpose: Static data (content) not stored in database
- Contains: `faq.ts`, `team.ts`, `services.ts`, `jsonLd.ts`
- Convention: Export arrays or objects; typed with interfaces from `src/types/`

**`src/types/`:**
- Purpose: TypeScript type definitions and interfaces
- Contains: `index.ts` (core types), `visual-editor.ts` (editor types)
- Convention: Export interfaces and types; used by components and data files

**`src/lib/`:**
- Purpose: Utilities, helpers, and infrastructure
- Contains: API client, error logger, config
- Key files: `api/client.ts` (fetch wrapper), `error-logger.ts` (global error tracking)

**`src/contexts/`:**
- Purpose: React Context providers for state management
- Contains: `ModalProvider.tsx` (modal coordination), `EditorAuthContext.tsx` (auth)
- Pattern: Provide context + hook for accessing (useModal, useEditorAuth)

**`src/hooks/`:**
- Purpose: Custom React hooks
- Contains: Form logic, authentication, editor utilities
- Key files: `useEditorAuth.ts`, `useContactForm.ts`

**`src/visual-editor/`:**
- Purpose: Separate subsystem for admin content editing
- Contains: Editor context, UI components, block editors, templates
- Justification: Isolates complex editor logic from public website code
- Key files: `context/EditorContext.tsx` (state), `modes/` (view/edit), `sidebar/editors/` (block editors)

## Key File Locations

**Entry Points:**
- `src/app/layout.tsx`: Root layout with metadata and providers
- `src/app/page.tsx`: Public home page
- `src/app/admin/editor/[slug]/page.tsx`: Visual editor interface
- `src/app/admin/login/page.tsx`: Admin login

**Configuration:**
- `src/config/site.ts`: Site metadata (name, email, phone, nav, packages, hero content)
- `src/lib/config.ts`: Runtime configuration (env vars)

**Core Logic:**
- `src/components/contact/useContactForm.ts`: Multi-step form logic and validation
- `src/visual-editor/context/EditorContext.tsx`: Editor state management (blocks, history, modes)
- `src/lib/error-logger.ts`: Global error tracking singleton
- `src/lib/api/client.ts`: Fetch wrapper for backend API calls

**Testing:**
- No test files found (add `*.test.ts` or `*.spec.tsx` following convention)

## Naming Conventions

**Files:**
- Component files: PascalCase (e.g., `Header.tsx`, `ContactModal.tsx`)
- Hook files: camelCase starting with `use` (e.g., `useContactForm.ts`, `useEditor.ts`)
- Data files: camelCase (e.g., `faq.ts`, `team.ts`, `services.ts`)
- Type files: `index.ts` in types directory; types use PascalCase (e.g., `ContactFormData`)
- API routes: `route.ts` (Next.js convention)

**Directories:**
- Feature areas: lowercase, plural or descriptor (e.g., `components/`, `hooks/`, `contexts/`)
- Nested subsystems: lowercase (e.g., `contact/`, `sidebar/`, `editors/`)
- Slugs/params: square brackets (e.g., `[slug]` in Next.js App Router)

**React Component Props:**
- PascalCase for component names: `<Header />`, `<ContactModal />`
- camelCase for prop names: `isOpen`, `onClose`, `initialBlocks`

**Constants:**
- Config exports: UPPER_SNAKE_CASE (when used as constants) or camelCase objects
- Example: `navLinks`, `heroFeatures`, `packageOptions` from `src/config/site.ts`

**Types:**
- Interfaces: PascalCase (e.g., `ContactFormData`, `SiteConfig`, `EditorState`)
- Type aliases: PascalCase (e.g., `PackageType`)

## Where to Add New Code

**New Feature (Multi-Component):**
- Primary code: `src/components/` (for display components) or `src/visual-editor/` (for editor features)
- Logic: `src/hooks/` (custom hooks) or `src/lib/` (utilities)
- Data: `src/data/` if static, `src/types/` for types
- State: `src/contexts/` if shared across multiple components

**New Component/Module:**
- Implementation: `src/components/` for public site components, `src/visual-editor/components/` for editor components
- Hooks: `src/hooks/` for reusable logic
- Types: Add to `src/types/index.ts` or `src/types/visual-editor.ts`
- Example: New section component goes in `src/components/YourSection.tsx`

**Utilities:**
- Shared helpers: `src/lib/` (e.g., `src/lib/api/`, `src/lib/helpers/`)
- API integration: `src/lib/api/client.ts` or `src/app/api/` for route handlers
- Error handling: Extend `src/lib/error-logger.ts`

**New Page/Route:**
- Public pages: Create directory in `src/app/` (e.g., `src/app/new-page/page.tsx`)
- Admin pages: Create directory in `src/app/admin/` (protected by layout)
- Dynamic routes: Use `[param]` syntax (e.g., `src/app/admin/editor/[slug]/page.tsx`)

**Configuration/Data Updates:**
- Site metadata: Update `src/config/site.ts` (email, phone, nav links, etc.)
- Static data: Update `src/data/faq.ts`, `src/data/team.ts`, etc.
- Contact form options: Update `src/config/site.ts` (packageOptions, guestOptions)

**New Editor Feature:**
- Block type: Add to `src/visual-editor/types.ts`, create editor in `src/visual-editor/sidebar/editors/`
- Template: Add to `src/visual-editor/data/blockTemplates.ts`
- Context: Extend `src/visual-editor/context/EditorContext.tsx` for new state
- Component: Render in `src/visual-editor/modes/ViewMode.tsx` or `EditMode.tsx`

## Special Directories

**`src/app/`:**
- Purpose: Next.js App Router (page routes, layouts, API)
- Generated: No (user-created)
- Committed: Yes
- Convention: Mirrors URL structure; `page.tsx` files map to routes

**`src/visual-editor/`:**
- Purpose: Isolated editor subsystem
- Generated: No
- Committed: Yes
- Convention: Mirrors structure of main components; context-based state

**`src/components/contact/`:**
- Purpose: Multi-step contact form subsystem
- Generated: No
- Committed: Yes
- Convention: Barrel export via `index.ts`; useContactForm hook encapsulates logic

**`src/types/`:**
- Purpose: Centralized TypeScript definitions
- Generated: No
- Committed: Yes
- Convention: One type file per major feature or subsystem

**`public/`:**
- Purpose: Static assets (not analyzed, but referenced in code)
- Generated: No (user-placed)
- Committed: Yes
- Convention: Images, favicons, fonts referenced by absolute path

**`out/` (Build Output):**
- Purpose: Next.js build artifacts
- Generated: Yes (via `npm run build`)
- Committed: No (in .gitignore)

**.next/ (Next.js Cache):**
- Purpose: Development cache and build artifacts
- Generated: Yes (via `npm run dev`)
- Committed: No (in .gitignore)

---

*Structure analysis: 2026-01-26*
