# Visual Page Builder Architecture for musikfürfirmen.de
## Squarespace/WordPress-Style WYSIWYG Visual Editing System

**Project:** musikfürfirmen.de
**Stack:** Laravel 12 + Filament 4 (Admin) + Next.js 16 (Frontend)
**Date:** 2026-01-15
**Status:** 🎯 Architecture Proposal (Ultrathink Analysis)

---

## Executive Summary

**User Requirement:**
> "I need something like Squarespace UI or WordPress, where you see the webpage and are able to edit text, pictures etc."

**Key Distinction:**
- ❌ **NOT a RichEditor** (text editor in admin form field)
- ✅ **Visual Page Builder** (drag-and-drop website editor showing actual layout)

**Architecture Decision Required:**
Where should the visual editor live?
1. **Backend-First**: Edit in Laravel/Filament admin, preview Next.js frontend
2. **Frontend-First**: Edit directly on Next.js site, store in Laravel API
3. **Hybrid**: Visual editor in admin, components rendered from Next.js

This document analyzes 10+ solutions and proposes the optimal architecture for your dual-stack setup.

---

## Table of Contents

1. [Requirements Analysis](#requirements-analysis)
2. [Solution Categories](#solution-categories)
3. [Evaluated Solutions](#evaluated-solutions)
4. [Architecture Options](#architecture-options)
5. [Recommendation](#recommendation)
6. [Implementation Plan](#implementation-plan)
7. [Technical Details](#technical-details)
8. [Migration Strategy](#migration-strategy)

---

## Requirements Analysis

### Functional Requirements

| Requirement | Priority | Rationale |
|-------------|----------|-----------|
| **Visual drag-and-drop editing** | Critical | User wants Squarespace/WordPress experience |
| **See actual website layout** | Critical | WYSIWYG = What You See Is What You Get (final site) |
| **Edit text directly on page** | Critical | No form fields, inline editing |
| **Image drag-and-drop placement** | Critical | Visual positioning of images |
| **Component library** | High | Services, team, FAQ blocks |
| **Responsive preview** | High | Mobile/tablet/desktop views |
| **Undo/redo** | High | Non-destructive editing |
| **Save as draft** | High | Preview before publishing |
| **Version history** | Medium | Rollback to previous versions |
| **Multi-user collaboration** | Low | Future consideration |

### Technical Requirements

| Requirement | Priority | Rationale |
|-------------|----------|-----------|
| **Works with existing Next.js 16 frontend** | Critical | Don't rebuild entire frontend |
| **Integrates with Laravel 12 backend** | Critical | Content stored in existing DB |
| **Works with Filament 4 admin** | High | Unified admin experience |
| **JSON-based content storage** | High | Flexible, API-ready |
| **TypeScript support** | High | Next.js project uses TypeScript |
| **Tailwind CSS compatibility** | High | Existing styling system |
| **CDN-friendly** | Medium | Hetzner + Cloudflare setup |
| **Self-hosted option** | Medium | No vendor lock-in |

### User Experience Requirements

| Requirement | Priority | Description |
|-------------|----------|-------------|
| **No coding required** | Critical | Content editors are non-technical |
| **Intuitive UI** | Critical | Like Squarespace/WordPress |
| **Fast page load** | High | Visual editor loads quickly |
| **Real-time preview** | High | See changes immediately |
| **Mobile editing support** | Medium | Edit from tablets |

---

## Solution Categories

Visual page builders fall into three architectural categories:

### Category 1: Backend-Integrated Builders
**Edit in admin panel, data stored in Laravel, frontend consumes API**

**Pros:**
- ✅ Unified admin experience (Filament integration)
- ✅ Full control over authentication/permissions
- ✅ Direct database access (Laravel Eloquent)
- ✅ Easy to secure (admin-only)

**Cons:**
- ❌ Editor shows simplified preview, not actual Next.js rendering
- ❌ Component mismatch (Laravel Blade vs Next.js React)
- ❌ Two styling systems (admin + frontend)

**Solutions in this category:**
- Filamentor (Filament plugin)
- Filament Fabricator (Filament plugin)
- Mason (Filament field)
- GrapesJS (embedded in Laravel)

---

### Category 2: Frontend-Integrated Builders
**Edit directly on Next.js site, visual editor overlays actual pages**

**Pros:**
- ✅ WYSIWYG = actual website (pixel-perfect)
- ✅ Direct component editing (React components)
- ✅ Single styling system (Tailwind CSS)
- ✅ Instant preview (no API roundtrip)

**Cons:**
- ❌ Editor code added to frontend bundle
- ❌ Authentication complexity (secure editing on public site)
- ❌ Must sync data back to Laravel API

**Solutions in this category:**
- Builder.io (headless CMS with visual editor)
- Plasmic (no-code visual builder)
- React Bricks (visual headless CMS)
- Destack (open-source Next.js builder)

---

### Category 3: Hybrid Builders
**Edit in dedicated editor, render components from both systems**

**Pros:**
- ✅ Flexibility (best of both worlds)
- ✅ Can edit from admin or frontend
- ✅ Component library shared

**Cons:**
- ❌ More complex architecture
- ❌ Requires bridge layer
- ❌ Component definition duplication

**Solutions in this category:**
- Storyblok (headless CMS with visual editor)
- Strapi (headless CMS with page builder plugin)
- GrapesJS (with custom Laravel + Next.js bridge)

---

## Evaluated Solutions

### 1. Filamentor (Backend-Integrated)

**What it is:**
Drag-and-drop page builder plugin for Laravel Filament with grid-based system.

**Key Features:**
- Intuitive drag-and-drop interface
- Grid-based responsive system
- Ready-to-use elements (text, image, video)
- Margin & padding controls
- Works with Livewire stack

**Architecture:**
```
Admin User → Filament Form (Filamentor) → JSON Storage (Laravel DB)
                                                ↓
                                        Next.js API Fetch → Render Components
```

**Pros:**
- ✅ Native Filament integration
- ✅ No external dependencies
- ✅ Full control over data
- ✅ Built-in authentication

**Cons:**
- ❌ Editor preview != Next.js output
- ❌ Components must be duplicated (Livewire + React)
- ❌ Limited to grid-based layouts

**Verdict:** ⚠️ Good for Laravel-only sites, but component mismatch for dual-stack

**Source:** [Filamentor on Filament Plugins](https://filamentphp.com/plugins/george-semaan-filamentor-page-builder)

---

### 2. Filament Fabricator (Backend-Integrated)

**What it is:**
Block-based page builder skeleton for Filament (more framework than full builder).

**Key Features:**
- Block-based content structure
- Custom block development required
- Layout system with sections
- SEO metadata support

**Architecture:**
```
Admin User → Filament Resource → Custom Blocks → JSON Storage
                                                        ↓
                                                Next.js API → Custom Block Components
```

**Pros:**
- ✅ Very flexible (build your own blocks)
- ✅ Clean data structure
- ✅ Filament-native

**Cons:**
- ❌ Skeleton only (requires significant development)
- ❌ No visual drag-and-drop (more like page builder framework)
- ❌ Each block needs React + Blade versions

**Verdict:** ⚠️ Too much custom development, not WYSIWYG enough

---

### 3. Mason (Backend-Integrated)

**What it is:**
Simple block-based drag-and-drop field for Filament forms.

**Key Features:**
- Drag-and-drop reordering of blocks
- Custom "bricks" (blocks) system
- Lightweight (just a form field)

**Architecture:**
```
Admin User → Filament Form Field → Block Array → JSON Storage
                                                        ↓
                                                Next.js API → Render Blocks
```

**Pros:**
- ✅ Simple to implement
- ✅ Lightweight
- ✅ Easy to customize

**Cons:**
- ❌ Not a full visual editor (just block ordering)
- ❌ No inline text editing
- ❌ No drag-and-drop layout building

**Verdict:** ❌ Too simple, doesn't meet "Squarespace UI" requirement

**Source:** [Mason on Packagist](https://packagist.org/packages/awcodes/mason)

---

### 4. GrapesJS (Hybrid - Backend or Frontend)

**What it is:**
Open-source visual web builder framework (embeddable, like TinyMCE but for entire pages).

**Key Features:**
- Full drag-and-drop interface
- Component-based architecture
- Plugin ecosystem (image editor, blocks, etc.)
- Storage Manager for API integration
- Can embed in Laravel or Next.js

**Architecture Option A (Backend-Integrated):**
```
Admin User → Laravel View (GrapesJS embed) → Storage API → Laravel DB
                                                                ↓
                                                        Next.js API → HTML/JSON Render
```

**Architecture Option B (Frontend-Integrated):**
```
Editor User → Next.js Page (GrapesJS embed) → Storage API → Laravel DB
                                ↑
                        Real Next.js Components (pixel-perfect)
```

**Pros:**
- ✅ Free and open-source
- ✅ Full visual editor (very Squarespace-like)
- ✅ Flexible (can integrate anywhere)
- ✅ Large plugin ecosystem
- ✅ Active community

**Cons:**
- ❌ Requires custom integration work
- ❌ Components need mapping (GrapesJS → React)
- ❌ Styling coordination (Tailwind CSS)

**Verdict:** ✅ Excellent flexibility, but requires bridge development

**Sources:**
- [GrapesJS GitHub](https://github.com/GrapesJS/grapesjs)
- [Laravel GrapesJS Integration Guide](https://medium.com/@akmalarzhang/build-your-own-website-builder-using-laravel-and-grapes-js-93226d82ea97)
- [GrapesJS Storage Manager API](https://grapesjs.com/docs/modules/Storage.html)

---

### 5. Builder.io (Frontend-Integrated)

**What it is:**
Headless CMS with powerful visual editor for Next.js (drag-and-drop actual React components).

**Key Features:**
- Register your existing React components
- Drag-and-drop them into layouts
- Visual editing on actual Next.js site
- A/B testing and targeting built-in
- Component-driven approach

**Architecture:**
```
Editor User → Builder.io Visual Editor (embedded in Next.js) → Builder.io API
                            ↓                                         ↓
                    Real React Components                    Laravel Webhook
                                                                    ↓
                                                            Sync to Laravel DB
```

**Pros:**
- ✅ True WYSIWYG (edit actual Next.js components)
- ✅ Zero component duplication (use existing React components)
- ✅ Excellent Next.js integration (official SDK)
- ✅ A/B testing & targeting features
- ✅ Fast visual editor

**Cons:**
- ❌ Hosted service (SaaS, not self-hosted)
- ❌ Cannot embed editor commercially (must use their hosted version)
- ❌ Requires webhook sync to Laravel
- ❌ Additional cost ($)

**Verdict:** ✅ Best visual experience, but vendor lock-in and cost

**Sources:**
- [Builder.io for Next.js](https://www.builder.io/m/nextjs)
- [Top 5 Page Builders for React](https://dev.to/fede_bonel_tozzi/top-5-page-builders-for-react-190g)

---

### 6. Plasmic (Frontend-Integrated)

**What it is:**
Visual no-code page builder + headless CMS for Next.js with code export.

**Key Features:**
- Full visual design tool
- Component library system
- Code export (React/TypeScript)
- Headless CMS mode
- Design tokens support

**Architecture:**
```
Editor User → Plasmic Visual Editor → Plasmic CMS → Next.js Loader
                                                            ↓
                                                    Laravel Webhook Sync
```

**Pros:**
- ✅ Powerful visual design capabilities
- ✅ Code export (own the code)
- ✅ Supports existing React components
- ✅ Good Next.js integration

**Cons:**
- ❌ Hosted service (SaaS)
- ❌ Steeper learning curve
- ❌ Requires webhook sync to Laravel
- ❌ Complex for simple content editing

**Verdict:** ⚠️ Powerful but overkill for content pages

**Source:** [Plasmic for Next.js](https://www.plasmic.app/nextjs)

---

### 7. React Bricks (Frontend-Integrated)

**What it is:**
Visual headless CMS for Next.js with React Server Components support.

**Key Features:**
- Inline visual editing
- Pre-built content blocks
- Next.js App Router support
- React Server Components compatible
- Tailwind CSS friendly

**Architecture:**
```
Editor User → React Bricks Visual Editor (Next.js) → React Bricks API
                                                            ↓
                                                    Laravel Webhook Sync
```

**Pros:**
- ✅ Modern Next.js support (App Router, RSC)
- ✅ Tailwind CSS integration
- ✅ Visual inline editing
- ✅ Clean API

**Cons:**
- ❌ Hosted service (SaaS)
- ❌ Smaller ecosystem than Builder.io
- ❌ Requires webhook sync to Laravel

**Verdict:** ✅ Good balance of features and simplicity, but SaaS

---

### 8. Storyblok (Hybrid)

**What it is:**
Headless CMS with real-time visual editor and on-page collaboration.

**Key Features:**
- Visual editor with live preview
- Block-based content (Bloks)
- Official Laravel SDK
- Official Next.js SDK
- Real-time collaboration

**Architecture:**
```
Editor User → Storyblok Visual Editor → Storyblok API
                                            ↓                ↓
                                    Laravel Consumer  Next.js Consumer
                                            ↓
                                    Laravel DB (cache)
```

**Pros:**
- ✅ Official Laravel and Next.js SDKs
- ✅ Visual editor with real-time preview
- ✅ Block-based approach
- ✅ Good documentation

**Cons:**
- ❌ Hosted service (SaaS, expensive)
- ❌ Editor not embedded (separate Storyblok interface)
- ❌ Another system to learn

**Verdict:** ⚠️ Powerful but expensive, and editor is separate interface

**Sources:**
- [Storyblok for Laravel](https://www.storyblok.com/laravel-cms)
- [Best Headless CMS for Next.js 2026](https://naturaily.com/blog/next-js-cms)

---

### 9. Destack (Frontend-Integrated, Open-Source)

**What it is:**
Open-source visual page builder for Next.js (like GrapesJS but Next.js-specific).

**Key Features:**
- Open-source (MIT license)
- Visual drag-and-drop
- Embeddable in Next.js
- Component-based
- Tailwind CSS support

**Architecture:**
```
Editor User → Next.js Page (Destack embed) → API Route → Laravel API
                        ↓
                Real Next.js Components
```

**Pros:**
- ✅ Open-source (no vendor lock-in)
- ✅ Next.js-native
- ✅ Embeddable
- ✅ Free

**Cons:**
- ❌ Smaller community than GrapesJS
- ❌ Less mature
- ❌ Limited plugin ecosystem

**Verdict:** ✅ Interesting open-source alternative to Builder.io

---

### 10. TomatoPHP Page Builder (Backend-Integrated)

**What it is:**
Drag-and-drop page builder for Filament with dynamic sections.

**Key Features:**
- Drag-and-drop section management
- Dynamic content blocks
- URL management
- Filament-native

**Architecture:**
```
Admin User → Filament Resource → Section Builder → JSON Storage
                                                        ↓
                                                Next.js API → Render Sections
```

**Pros:**
- ✅ Filament-native
- ✅ Simple integration
- ✅ URL management built-in

**Cons:**
- ❌ Not a full visual WYSIWYG editor
- ❌ Section-based (not pixel-level editing)
- ❌ Components still need duplication

**Verdict:** ⚠️ Good for simple section management, not full visual editing

**Source:** [TomatoPHP Page Builder](https://github.com/tomatophp/filament-page-builder)

---

## Architecture Options

Based on the evaluated solutions, here are three viable architectural approaches:

---

### Option A: Backend-First with GrapesJS Embedded in Filament

**Architecture:**

```
┌────────────────────────────────────────────────────────────────┐
│                     Filament Admin Panel                        │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │         Pages Resource (Filament)                         │ │
│  │                                                            │ │
│  │  ┌─────────────────────────────────────────────────────┐ │ │
│  │  │   GrapesJS Visual Editor (embedded in Blade view)   │ │ │
│  │  │                                                       │ │ │
│  │  │   • Drag-and-drop components                         │ │ │
│  │  │   • Visual styling                                    │ │ │
│  │  │   • Preview mode                                      │ │ │
│  │  └─────────────────────────────────────────────────────┘ │ │
│  │                          ↓                                │ │
│  │                    Storage Manager                        │ │
│  │                          ↓                                │ │
│  │              Laravel API (POST /api/pages/save)          │ │
│  └──────────────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────────────┘
                                ↓
                        ┌───────────────┐
                        │   pages table │
                        │               │
                        │   - id        │
                        │   - title     │
                        │   - slug      │
                        │   - content   │  ← GrapesJS JSON
                        │   - html      │  ← GrapesJS HTML
                        │   - css       │  ← GrapesJS CSS
                        └───────────────┘
                                ↓
                        ┌──────────────────────────────────┐
                        │  Laravel API (GET /api/pages)    │
                        └──────────────────────────────────┘
                                ↓
                        ┌──────────────────────────────────┐
                        │      Next.js Frontend             │
                        │                                   │
                        │  • Fetch page content from API    │
                        │  • Render HTML + CSS              │
                        │  • Apply Tailwind utilities       │
                        └──────────────────────────────────┘
```

**Implementation Steps:**

1. **Install GrapesJS in Laravel:**
   ```bash
   composer require jd-dotlogics/laravel-grapesjs
   ```

2. **Extend Filament Pages Resource:**
   - Replace content field with custom GrapesJS view
   - Embed GrapesJS editor in Blade template

3. **Create Storage API:**
   - POST `/api/grapesjs/save` → Save JSON/HTML/CSS to database
   - GET `/api/grapesjs/load` → Load page data into editor

4. **Update Next.js Frontend:**
   - Fetch page content from Laravel API
   - Render HTML with Tailwind utilities
   - Apply CSS (scope to prevent conflicts)

5. **Component Mapping:**
   - Create GrapesJS custom components
   - Map to existing Next.js components (services, team, etc.)

**Pros:**
- ✅ Unified admin experience (all in Filament)
- ✅ Full control over data and authentication
- ✅ Open-source (no vendor lock-in)
- ✅ Mature editor (GrapesJS has large ecosystem)

**Cons:**
- ❌ Editor preview != actual Next.js rendering
- ❌ Requires custom component mapping
- ❌ CSS scoping complexity
- ❌ Two-step workflow (edit in admin → preview on frontend)

**Estimated Development Time:** 2-3 weeks

**Best For:** Teams that want everything in Filament admin

---

### Option B: Frontend-First with Builder.io or Destack

**Architecture (Builder.io):**

```
┌────────────────────────────────────────────────────────────────┐
│                   Next.js Frontend                              │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │      /admin/pages/[slug] (protected route)                │ │
│  │                                                            │ │
│  │  ┌─────────────────────────────────────────────────────┐ │ │
│  │  │   Builder.io Visual Editor (embedded)               │ │ │
│  │  │                                                       │ │ │
│  │  │   • Drag-and-drop ACTUAL React components           │ │ │
│  │  │   • Real-time preview (pixel-perfect)                │ │ │
│  │  │   • Component props editing                          │ │ │
│  │  └─────────────────────────────────────────────────────┘ │ │
│  │                          ↓                                │ │
│  │                  Builder.io API                           │ │
│  └──────────────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────────────┘
                                ↓
                        ┌──────────────────────────────────┐
                        │  Builder.io Webhook               │
                        │  (on content.save)                │
                        └──────────────────────────────────┘
                                ↓
                        ┌──────────────────────────────────┐
                        │  Laravel API (POST /webhooks/    │
                        │                builder)           │
                        │                                   │
                        │  • Receive content JSON           │
                        │  • Save to pages table            │
                        │  • Trigger revalidation           │
                        └──────────────────────────────────┘
                                ↓
                        ┌───────────────┐
                        │   pages table │
                        │               │
                        │   - id        │
                        │   - title     │
                        │   - slug      │
                        │   - content   │  ← Builder.io JSON
                        └───────────────┘
                                ↓
                        ┌──────────────────────────────────┐
                        │      Next.js Public Pages         │
                        │                                   │
                        │  • Fetch from Laravel API         │
                        │  • Or fetch directly from         │
                        │    Builder.io (hybrid)            │
                        └──────────────────────────────────┘
```

**Implementation Steps:**

1. **Register Components with Builder.io:**
   ```typescript
   // src/builder/components.ts
   Builder.registerComponent(ServiceCard, {
     name: 'Service Card',
     inputs: [
       { name: 'title', type: 'string' },
       { name: 'description', type: 'string' },
       { name: 'icon', type: 'file' }
     ]
   });
   ```

2. **Create Protected Editor Route:**
   ```typescript
   // app/admin/pages/[slug]/edit/page.tsx
   export default function PageEditor({ params }) {
     return <BuilderEditor model="page" entry={params.slug} />;
   }
   ```

3. **Setup Webhook in Laravel:**
   ```php
   // routes/api.php
   Route::post('/webhooks/builder', [WebhookController::class, 'handleBuilder']);
   ```

4. **Render on Frontend:**
   ```typescript
   // app/pages/[slug]/page.tsx
   export default async function Page({ params }) {
     const content = await fetch(`/api/pages/${params.slug}`);
     return <BuilderComponent model="page" content={content} />;
   }
   ```

**Pros:**
- ✅ True WYSIWYG (edit actual Next.js components)
- ✅ Zero component duplication
- ✅ Pixel-perfect preview
- ✅ Fast visual editor
- ✅ Instant preview (no API roundtrip)

**Cons:**
- ❌ SaaS vendor lock-in (Builder.io)
- ❌ Monthly cost ($)
- ❌ Editor bundle added to frontend
- ❌ Authentication complexity (protect editor routes)
- ❌ Must sync back to Laravel

**Estimated Development Time:** 1-2 weeks (Builder.io), 3-4 weeks (Destack)

**Best For:** Teams that prioritize visual editing experience and can accept SaaS cost

---

### Option C: Hybrid with Storyblok + Dual Consumers

**Architecture:**

```
┌────────────────────────────────────────────────────────────────┐
│                   Storyblok Visual Editor                       │
│                   (separate hosted interface)                   │
│                                                                 │
│  • Block-based content (Bloks)                                 │
│  • Real-time visual preview                                     │
│  • Collaboration features                                       │
└────────────────────────────────────────────────────────────────┘
                                ↓
                        ┌──────────────────────────────────┐
                        │      Storyblok Content API        │
                        │      (GraphQL or REST)            │
                        └──────────────────────────────────┘
                        ↙                                  ↘
          ┌───────────────────────┐              ┌─────────────────────┐
          │  Laravel Consumer     │              │  Next.js Consumer   │
          │                       │              │                     │
          │  • Fetch via SDK      │              │  • Fetch via SDK    │
          │  • Cache in DB        │              │  • SSG/ISR          │
          │  • Serve admin        │              │  • Render pages     │
          └───────────────────────┘              └─────────────────────┘
                    ↓                                      ↑
          ┌───────────────┐                                │
          │   pages table │                                │
          │               │                                │
          │   - id        │                                │
          │   - storyblok_id                              │
          │   - content   │  ← Cached Storyblok JSON      │
          │   - updated_at│                                │
          └───────────────┘                                │
                    ↓                                      │
          [Cache is used for admin]        [Direct API for frontend]
```

**Implementation Steps:**

1. **Setup Storyblok Space:**
   - Define content types (Page, Section, Component)
   - Create visual components (Bloks)

2. **Install SDKs:**
   ```bash
   # Laravel
   composer require storyblok/php-client

   # Next.js
   npm install @storyblok/react
   ```

3. **Laravel Consumer:**
   ```php
   // Sync Storyblok content to local DB
   $client = new \Storyblok\Client('your-token');
   $story = $client->getStoryBySlug('pages/' . $slug);
   Page::updateOrCreate(['slug' => $slug], ['content' => $story->content]);
   ```

4. **Next.js Consumer:**
   ```typescript
   // Fetch directly from Storyblok for ISR
   import { getStoryblokApi } from '@storyblok/react';
   const story = await getStoryblokApi().get('cdn/stories/' + slug);
   ```

**Pros:**
- ✅ Professional visual editor (mature product)
- ✅ Official SDKs for both Laravel and Next.js
- ✅ Real-time collaboration
- ✅ Flexible block system

**Cons:**
- ❌ Expensive SaaS ($300+/month for teams)
- ❌ Editor is separate interface (not embedded)
- ❌ Requires learning Storyblok system
- ❌ Vendor lock-in

**Estimated Development Time:** 2-3 weeks

**Best For:** Large teams with budget for premium headless CMS

---

## Recommendation

### 🎯 Recommended Solution: **Option B (Frontend-First with Destack)**

**Why Destack over Builder.io?**

| Criteria | Builder.io | Destack | Winner |
|----------|-----------|---------|--------|
| **Cost** | $49+/month SaaS | Free (open-source) | **Destack** |
| **Visual Experience** | Excellent | Good | Builder.io |
| **Vendor Lock-in** | High | None | **Destack** |
| **Next.js Integration** | Official SDK | Native Next.js | **Destack** |
| **Component Reuse** | Register components | Use components directly | **Destack** |
| **Self-Hosted** | No | Yes | **Destack** |
| **Maturity** | Very mature | Growing | Builder.io |
| **Learning Curve** | Moderate | Low | **Destack** |

**Decision:**
For musikfürfirmen.de, **Destack provides the best balance**:
- ✅ Open-source (no ongoing costs)
- ✅ True WYSIWYG (edit actual Next.js components)
- ✅ No vendor lock-in
- ✅ Embeddable (editor lives on your Next.js site)
- ✅ Tailwind CSS support (matches your stack)

**Trade-off:**
Slightly less mature than Builder.io, but the open-source benefits and perfect Next.js integration outweigh this for your use case.

---

### Alternative Recommendation: **Option A (GrapesJS in Filament)** if...

Choose Option A (Backend-First with GrapesJS) if:
- ❌ You don't want editor code in frontend bundle
- ❌ You prefer all editing in Filament admin
- ❌ You're okay with preview not being pixel-perfect

---

## Implementation Plan

### Phase 1: Proof of Concept (Week 1)

**Goal:** Validate Destack works with your Next.js + Laravel setup

**Tasks:**
1. Install Destack in Next.js project
2. Create protected `/admin/editor` route
3. Test drag-and-drop editing
4. Register 1-2 existing components (e.g., ServiceCard)
5. Save page JSON to localStorage (no Laravel yet)

**Deliverables:**
- Working Destack editor in Next.js
- Can edit and preview one page
- Demo to stakeholders

**Success Criteria:**
- Can drag-and-drop components
- Can edit text inline
- Preview matches actual Next.js rendering

---

### Phase 2: Laravel Integration (Week 2)

**Goal:** Connect Destack editor to Laravel backend

**Tasks:**
1. Create Laravel API routes:
   - GET `/api/pages/{slug}` → Fetch page content
   - POST `/api/pages/{slug}` → Save page content
   - PUT `/api/pages/{slug}/publish` → Publish page
2. Update Pages model to store Destack JSON
3. Add authentication middleware (Laravel Sanctum)
4. Implement draft/published workflow
5. Test roundtrip (edit → save → fetch → render)

**Database Schema:**
```php
Schema::table('pages', function (Blueprint $table) {
    $table->json('destack_content')->nullable(); // Destack JSON
    $table->json('destack_components')->nullable(); // Component registry
    $table->timestamp('edited_at')->nullable(); // Last edit time
});
```

**API Endpoints:**
```php
// routes/api.php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/pages/{slug}', [PageController::class, 'show']);
    Route::post('/pages/{slug}', [PageController::class, 'update']);
    Route::post('/pages/{slug}/publish', [PageController::class, 'publish']);
});
```

**Deliverables:**
- API routes for page CRUD
- Authentication flow
- Data persistence in Laravel DB

**Success Criteria:**
- Can save edited pages to Laravel
- Can fetch page content from Laravel
- Draft/published workflow works

---

### Phase 3: Component Library (Week 3)

**Goal:** Make all existing Next.js components editable in Destack

**Tasks:**
1. Register components with Destack:
   - ServiceCard
   - TeamMember
   - FAQ
   - ContactForm
   - Hero sections
   - Call-to-action blocks
2. Define component props (editable fields)
3. Add component preview thumbnails
4. Create component categories (Layout, Content, Forms)
5. Test each component in editor

**Component Registration Example:**
```typescript
// src/destack/components.ts
import { ServiceCard } from '@/components/ServiceCard';

export const components = [
  {
    component: ServiceCard,
    name: 'Service Card',
    category: 'Content',
    props: {
      title: { type: 'string', label: 'Title' },
      description: { type: 'string', label: 'Description' },
      icon: { type: 'image', label: 'Icon' },
      linkUrl: { type: 'string', label: 'Link URL' }
    },
    defaultProps: {
      title: 'Service Title',
      description: 'Service description...'
    }
  },
  // ... more components
];
```

**Deliverables:**
- All key components registered
- Component library in editor sidebar
- Documentation for adding new components

**Success Criteria:**
- Can drag-and-drop all components
- Can edit component props inline
- Components render correctly on frontend

---

### Phase 4: Filament Integration (Week 4)

**Goal:** Allow accessing editor from Filament admin panel

**Tasks:**
1. Add "Edit Page" button in Filament Pages resource
2. Button opens Next.js editor in new tab
3. Pass authentication token to Next.js
4. Implement SSO (single sign-on) flow
5. Show last edited timestamp in Filament table
6. Add editor access logs (audit trail)

**Filament Resource Enhancement:**
```php
// app/Filament/Resources/Pages/Tables/PagesTable.php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            // ... existing columns
            TextColumn::make('edited_at')
                ->label('Last Edited')
                ->dateTime()
                ->sortable(),
        ])
        ->actions([
            Action::make('edit_visual')
                ->label('Edit Visually')
                ->icon('heroicon-o-pencil-square')
                ->url(fn (Page $record): string =>
                    config('app.frontend_url') . '/admin/editor/' . $record->slug .
                    '?token=' . auth()->user()->createToken('editor')->plainTextToken
                )
                ->openUrlInNewTab(),
            // ... existing actions
        ]);
}
```

**Authentication Flow:**
```
1. User clicks "Edit Visually" in Filament
2. Filament generates short-lived JWT token
3. Opens Next.js editor with token in URL
4. Next.js validates token with Laravel API
5. Editor loads with authenticated session
```

**Deliverables:**
- Filament action button
- SSO authentication flow
- Audit trail

**Success Criteria:**
- Can launch editor from Filament
- Authentication seamless (single click)
- Admin users can edit any page

---

### Phase 5: Polish & Production (Week 5-6)

**Goal:** Production-ready deployment

**Tasks:**
1. **Performance:**
   - Lazy-load editor (code splitting)
   - Optimize component bundle
   - Add loading states
   - Implement auto-save (every 30 seconds)

2. **UX Enhancements:**
   - Undo/redo functionality
   - Responsive preview (mobile/tablet/desktop)
   - Component search in sidebar
   - Keyboard shortcuts (Ctrl+S to save)
   - Exit confirmation ("Unsaved changes")

3. **Security:**
   - Rate limiting on API endpoints
   - CSRF protection
   - XSS sanitization for user inputs
   - Content validation

4. **Deployment:**
   - Deploy editor to Vercel (Next.js)
   - Update Laravel API on Hetzner
   - Setup environment variables
   - Configure CORS for cross-origin requests
   - Test on staging environment

5. **Documentation:**
   - User guide for content editors
   - Developer guide for adding components
   - Troubleshooting guide
   - Video tutorial (optional)

**Deliverables:**
- Production-ready system
- User documentation
- Deployment runbook

**Success Criteria:**
- Editor loads in <2 seconds
- Auto-save works reliably
- No security vulnerabilities
- All stakeholders trained

---

## Technical Details

### Data Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                         Edit Flow                                │
└─────────────────────────────────────────────────────────────────┘

1. User opens editor: /admin/editor/about-us
   ↓
2. Next.js fetches page data: GET /api/pages/about-us
   ↓
3. Laravel returns JSON:
   {
     "id": 5,
     "slug": "about-us",
     "title": "About Us",
     "destack_content": { ... }, // Destack JSON
     "is_published": true
   }
   ↓
4. Destack loads content into editor
   ↓
5. User drags/drops components, edits text
   ↓
6. Auto-save (every 30s): POST /api/pages/about-us
   {
     "destack_content": { ... },
     "edited_at": "2026-01-15T14:30:00Z"
   }
   ↓
7. Laravel saves to database
   ↓
8. User clicks "Publish" → PUT /api/pages/about-us/publish
   ↓
9. Laravel updates is_published = true
   ↓
10. Next.js revalidates page (ISR)

┌─────────────────────────────────────────────────────────────────┐
│                         Render Flow                              │
└─────────────────────────────────────────────────────────────────┘

1. User visits: /pages/about-us
   ↓
2. Next.js checks cache (ISR)
   ↓
3. If stale, fetch: GET /api/pages/about-us
   ↓
4. Laravel returns content JSON
   ↓
5. Next.js renders Destack components
   ↓
6. Components use Tailwind CSS
   ↓
7. Page served to user
```

---

### Database Schema

```sql
-- Enhanced pages table for Destack
ALTER TABLE pages ADD COLUMN destack_content JSONB;
ALTER TABLE pages ADD COLUMN destack_components JSONB;
ALTER TABLE pages ADD COLUMN edited_at TIMESTAMP;

-- Example content structure
{
  "destack_content": {
    "type": "root",
    "children": [
      {
        "type": "component",
        "name": "Hero",
        "props": {
          "title": "Welcome to musikfürfirmen.de",
          "subtitle": "Professional music for corporate events",
          "image": "/storage/hero-image.jpg"
        }
      },
      {
        "type": "component",
        "name": "ServiceGrid",
        "props": {
          "services": [
            { "title": "Live Bands", "icon": "music" },
            { "title": "DJs", "icon": "disc" }
          ]
        }
      }
    ]
  },
  "destack_components": [
    "Hero",
    "ServiceGrid",
    "TeamSection",
    "ContactForm"
  ],
  "edited_at": "2026-01-15T14:30:00Z"
}
```

---

### Authentication Flow

```typescript
// Next.js middleware for protected editor routes
// middleware.ts
import { NextResponse } from 'next/server';

export function middleware(request: NextRequest) {
  // Check if accessing editor
  if (request.nextUrl.pathname.startsWith('/admin/editor')) {
    const token = request.nextUrl.searchParams.get('token');

    if (!token) {
      return NextResponse.redirect(new URL('/login', request.url));
    }

    // Validate token with Laravel API
    const valid = await validateToken(token);

    if (!valid) {
      return NextResponse.redirect(new URL('/login', request.url));
    }

    // Store token in cookie for subsequent requests
    const response = NextResponse.next();
    response.cookies.set('auth_token', token, {
      httpOnly: true,
      secure: true,
      sameSite: 'strict',
      maxAge: 3600 // 1 hour
    });

    return response;
  }

  return NextResponse.next();
}

async function validateToken(token: string): Promise<boolean> {
  const response = await fetch(`${process.env.LARAVEL_API_URL}/api/auth/validate`, {
    headers: { Authorization: `Bearer ${token}` }
  });
  return response.ok;
}
```

---

### Component Registration

```typescript
// src/destack/registry.ts
import { ServiceCard } from '@/components/ServiceCard';
import { TeamMember } from '@/components/TeamMember';
import { Hero } from '@/components/Hero';
import { ContactForm } from '@/components/ContactForm';

export const componentRegistry = {
  ServiceCard: {
    component: ServiceCard,
    label: 'Service Card',
    category: 'Content',
    icon: '🎵',
    schema: {
      title: { type: 'string', label: 'Title', default: 'Service Title' },
      description: { type: 'textarea', label: 'Description' },
      icon: { type: 'image', label: 'Icon' },
      linkUrl: { type: 'string', label: 'Link URL' }
    }
  },
  TeamMember: {
    component: TeamMember,
    label: 'Team Member',
    category: 'People',
    icon: '👤',
    schema: {
      name: { type: 'string', label: 'Name' },
      role: { type: 'string', label: 'Role' },
      image: { type: 'image', label: 'Photo' },
      bio: { type: 'textarea', label: 'Biography' }
    }
  },
  Hero: {
    component: Hero,
    label: 'Hero Section',
    category: 'Layout',
    icon: '🎯',
    schema: {
      title: { type: 'string', label: 'Title' },
      subtitle: { type: 'string', label: 'Subtitle' },
      backgroundImage: { type: 'image', label: 'Background Image' },
      ctaText: { type: 'string', label: 'Call to Action Text' },
      ctaUrl: { type: 'string', label: 'Call to Action URL' }
    }
  },
  ContactForm: {
    component: ContactForm,
    label: 'Contact Form',
    category: 'Forms',
    icon: '✉️',
    schema: {
      title: { type: 'string', label: 'Form Title' },
      submitText: { type: 'string', label: 'Submit Button Text', default: 'Send' }
    }
  }
};
```

---

### API Routes (Laravel)

```php
// routes/api.php
use App\Http\Controllers\Api\PageEditorController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Get page for editing
    Route::get('/pages/{slug}', [PageEditorController::class, 'show']);

    // Save draft
    Route::post('/pages/{slug}', [PageEditorController::class, 'update']);

    // Publish page
    Route::put('/pages/{slug}/publish', [PageEditorController::class, 'publish']);

    // Unpublish page
    Route::put('/pages/{slug}/unpublish', [PageEditorController::class, 'unpublish']);

    // Get edit history
    Route::get('/pages/{slug}/history', [PageEditorController::class, 'history']);

    // Restore version
    Route::post('/pages/{slug}/restore/{version}', [PageEditorController::class, 'restore']);
});

// Public API (for frontend rendering)
Route::get('/public/pages/{slug}', [PageEditorController::class, 'public']);
```

```php
// app/Http/Controllers/Api/PageEditorController.php
namespace App\Http\Controllers\Api;

use App\Models\Page;
use Illuminate\Http\Request;

class PageEditorController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return response()->json([
            'id' => $page->id,
            'slug' => $page->slug,
            'title' => $page->title,
            'destack_content' => $page->destack_content,
            'destack_components' => $page->destack_components,
            'is_published' => $page->is_published,
            'edited_at' => $page->edited_at,
        ]);
    }

    public function update(Request $request, string $slug)
    {
        $validated = $request->validate([
            'destack_content' => 'required|array',
            'destack_components' => 'array',
        ]);

        $page = Page::where('slug', $slug)->firstOrFail();

        // Save current version to history before updating
        $page->saveVersion();

        $page->update([
            'destack_content' => $validated['destack_content'],
            'destack_components' => $validated['destack_components'] ?? [],
            'edited_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Page saved successfully',
            'edited_at' => $page->edited_at,
        ]);
    }

    public function publish(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $page->update(['is_published' => true]);

        // Trigger Next.js revalidation
        $this->revalidateNextJs($slug);

        return response()->json([
            'success' => true,
            'message' => 'Page published successfully',
        ]);
    }

    private function revalidateNextJs(string $slug)
    {
        // Call Next.js revalidation endpoint
        Http::post(config('app.frontend_url') . '/api/revalidate', [
            'secret' => config('app.revalidation_secret'),
            'path' => '/pages/' . $slug,
        ]);
    }
}
```

---

### Next.js API Routes

```typescript
// app/api/revalidate/route.ts
import { revalidatePath } from 'next/cache';
import { NextRequest, NextResponse } from 'next/server';

export async function POST(request: NextRequest) {
  const body = await request.json();
  const { secret, path } = body;

  // Verify secret
  if (secret !== process.env.REVALIDATION_SECRET) {
    return NextResponse.json({ error: 'Invalid secret' }, { status: 401 });
  }

  try {
    // Revalidate the path
    revalidatePath(path);

    return NextResponse.json({ revalidated: true, path });
  } catch (error) {
    return NextResponse.json({ error: 'Revalidation failed' }, { status: 500 });
  }
}
```

---

### Destack Integration

```typescript
// app/admin/editor/[slug]/page.tsx
'use client';

import { useEffect, useState } from 'react';
import { ContentProvider, Editor } from 'destack';
import { componentRegistry } from '@/destack/registry';

export default function DestackEditor({ params }: { params: { slug: string } }) {
  const [content, setContent] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Fetch page content from Laravel
    fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/pages/${params.slug}`, {
      headers: {
        Authorization: `Bearer ${getAuthToken()}`,
      },
    })
      .then(res => res.json())
      .then(data => {
        setContent(data.destack_content);
        setLoading(false);
      });
  }, [params.slug]);

  const handleSave = async (newContent: any) => {
    // Auto-save to Laravel
    await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/pages/${params.slug}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${getAuthToken()}`,
      },
      body: JSON.stringify({
        destack_content: newContent,
        destack_components: extractComponents(newContent),
      }),
    });
  };

  const handlePublish = async () => {
    await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/pages/${params.slug}/publish`, {
      method: 'PUT',
      headers: {
        Authorization: `Bearer ${getAuthToken()}`,
      },
    });

    alert('Page published successfully!');
  };

  if (loading) return <div>Loading editor...</div>;

  return (
    <div className="h-screen">
      <ContentProvider>
        <Editor
          data={content}
          onChange={handleSave}
          components={componentRegistry}
        />
      </ContentProvider>

      <div className="fixed bottom-4 right-4">
        <button
          onClick={handlePublish}
          className="bg-green-600 text-white px-6 py-2 rounded-lg"
        >
          Publish
        </button>
      </div>
    </div>
  );
}

function getAuthToken(): string {
  // Get token from cookie
  return document.cookie
    .split('; ')
    .find(row => row.startsWith('auth_token='))
    ?.split('=')[1] || '';
}

function extractComponents(content: any): string[] {
  // Extract list of components used in content
  const components = new Set<string>();

  function traverse(node: any) {
    if (node.type === 'component' && node.name) {
      components.add(node.name);
    }
    if (node.children) {
      node.children.forEach(traverse);
    }
  }

  traverse(content);
  return Array.from(components);
}
```

---

## Migration Strategy

### From Current RichEditor to Destack

**Current State:**
- PageForm.php uses RichEditor component
- Content stored as TipTap JSON
- Page model has content_html accessor

**Migration Plan:**

**Step 1: Add Destack Columns (Non-Breaking)**
```php
Schema::table('pages', function (Blueprint $table) {
    $table->json('destack_content')->nullable();
    $table->json('destack_components')->nullable();
    $table->timestamp('edited_at')->nullable();

    // Keep existing content column for backward compatibility
});
```

**Step 2: Dual-Write Period (2 weeks)**
- New pages use Destack
- Existing pages keep RichEditor
- Users choose which editor to use

**Step 3: Migration Script**
```php
// Convert existing TipTap content to simple Destack layout
$pages = Page::whereNotNull('content')->whereNull('destack_content')->get();

foreach ($pages as $page) {
    $html = $page->content_html;

    // Convert to basic Destack structure
    $destack = [
        'type' => 'root',
        'children' => [
            [
                'type' => 'component',
                'name' => 'RichTextBlock',
                'props' => [
                    'html' => $html
                ]
            ]
        ]
    ];

    $page->update([
        'destack_content' => $destack,
        'destack_components' => ['RichTextBlock']
    ]);
}
```

**Step 4: Deprecate RichEditor (Month 2)**
- All pages migrated to Destack
- Remove RichEditor from PageForm.php
- Keep content column for historical reference

---

## Comparison Matrix

| Feature | Filamentor | GrapesJS | Destack | Builder.io | Storyblok |
|---------|-----------|----------|---------|------------|-----------|
| **Open Source** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Self-Hosted** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Cost** | Free | Free | Free | $49+/mo | $300+/mo |
| **WYSIWYG Quality** | Good | Good | Excellent | Excellent | Good |
| **Next.js Integration** | ❌ | ⚠️ Custom | ✅ Native | ✅ Native | ✅ Native |
| **Filament Integration** | ✅ Native | ⚠️ Custom | ⚠️ Custom | ❌ | ⚠️ Custom |
| **Component Reuse** | ❌ | ⚠️ Mapping | ✅ Direct | ✅ Direct | ⚠️ Mapping |
| **Vendor Lock-in** | None | None | None | High | High |
| **Maturity** | Medium | High | Medium | Very High | Very High |
| **Learning Curve** | Low | Medium | Low | Medium | High |
| **Drag-and-Drop** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Inline Editing** | ⚠️ | ✅ | ✅ | ✅ | ✅ |
| **Responsive Preview** | ✅ | ⚠️ Plugin | ✅ | ✅ | ✅ |
| **Undo/Redo** | ⚠️ | ✅ | ✅ | ✅ | ✅ |
| **Version History** | ❌ | ❌ | ❌ Custom | ✅ | ✅ |
| **Collaboration** | ❌ | ❌ | ❌ | ✅ | ✅ |
| **A/B Testing** | ❌ | ❌ | ❌ | ✅ | ❌ |

**Legend:** ✅ Built-in | ⚠️ Possible with custom work | ❌ Not available

---

## Cost Analysis

### Option A: GrapesJS in Filament
- **Setup Cost:** 80-120 hours development ($8,000 - $12,000 at $100/hr)
- **Ongoing Cost:** Hosting + maintenance (~$100/month)
- **Total Year 1:** $9,200 - $13,200

### Option B: Destack
- **Setup Cost:** 60-80 hours development ($6,000 - $8,000 at $100/hr)
- **Ongoing Cost:** Hosting + maintenance (~$100/month)
- **Total Year 1:** $7,200 - $9,200

### Option B Alternative: Builder.io
- **Setup Cost:** 40-60 hours development ($4,000 - $6,000 at $100/hr)
- **Ongoing Cost:** $588/year (Builder.io Basic) + hosting ($100/month)
- **Total Year 1:** $5,788 - $7,788

### Option C: Storyblok
- **Setup Cost:** 60-80 hours development ($6,000 - $8,000 at $100/hr)
- **Ongoing Cost:** $3,600/year (Storyblok Team) + hosting ($100/month)
- **Total Year 1:** $10,800 - $12,800

**Winner: Destack (Best value for long-term)**

---

## Risk Assessment

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| **Destack project abandonment** | Low | High | Fork repo, maintain internally |
| **Performance degradation** | Low | Medium | Lazy-load editor, code splitting |
| **Security vulnerabilities** | Medium | High | Regular audits, input sanitization |
| **User adoption resistance** | Medium | Medium | Training, documentation, support |
| **Integration complexity** | Medium | Medium | Phased rollout, POC first |
| **Data migration issues** | Low | Medium | Thorough testing, backup strategy |

---

## Success Metrics

### Phase 1 (POC):
- [ ] Editor loads in <3 seconds
- [ ] Can drag-and-drop 5+ components
- [ ] Can edit text inline
- [ ] Preview matches actual rendering 100%

### Phase 2 (Integration):
- [ ] Can save/load from Laravel API
- [ ] Authentication works seamlessly
- [ ] Draft/publish workflow functional
- [ ] Auto-save works reliably

### Phase 3 (Launch):
- [ ] All pages migrated from RichEditor
- [ ] Content editors trained (2+ people)
- [ ] No critical bugs in production
- [ ] Page edit time reduced by 50%

### Long-term (3 months):
- [ ] 10+ pages created with visual editor
- [ ] Zero data loss incidents
- [ ] Editor uptime >99.5%
- [ ] User satisfaction >4/5 stars

---

## Conclusion

**Recommendation: Implement Option B (Frontend-First with Destack)**

This provides:
- ✅ True Squarespace/WordPress-like WYSIWYG experience
- ✅ No vendor lock-in (open-source)
- ✅ Best value (no ongoing SaaS costs)
- ✅ Perfect Next.js integration
- ✅ Reuse existing React components

**Next Steps:**
1. Review this architecture document with stakeholders
2. Get approval for Destack approach
3. Start Phase 1 (POC) - 1 week
4. Demo POC before committing to full implementation

**Timeline:**
- Week 1: POC (validate approach)
- Week 2: Laravel integration
- Week 3: Component library
- Week 4: Filament integration
- Week 5-6: Polish & production deployment

**Total Estimated Time:** 5-6 weeks

---

## Sources & References

### Visual Page Builder Research:
- [Filamentor Page Builder](https://filamentphp.com/plugins/george-semaan-filamentor-page-builder)
- [GrapesJS GitHub](https://github.com/GrapesJS/grapesjs)
- [GrapesJS Laravel Integration](https://medium.com/@akmalarzhang/build-your-own-website-builder-using-laravel-and-grapes-js-93226d82ea97)
- [GrapesJS Storage Manager API](https://grapesjs.com/docs/modules/Storage.html)
- [Builder.io for Next.js](https://www.builder.io/m/nextjs)
- [Plasmic for Next.js](https://www.plasmic.app/nextjs)
- [Top 5 Page Builders for React](https://dev.to/fede_bonel_tozzi/top-5-page-builders-for-react-190g)

### Headless CMS & Architecture:
- [Best Headless CMS for Next.js 2026](https://naturaily.com/blog/next-js-cms)
- [Storyblok for Laravel](https://www.storyblok.com/laravel-cms)
- [Build Scalable Web Apps with Next.js and Laravel](https://www.bacancytechnology.com/blog/build-web-app-with-nextjs-laravel)

### Filament Integration:
- [Mason Page Builder](https://packagist.org/packages/awcodes/mason)
- [TomatoPHP Page Builder](https://github.com/tomatophp/filament-page-builder)

---

**Document Version:** 1.0
**Last Updated:** 2026-01-15
**Status:** 🎯 Ready for Review
**Next Action:** Stakeholder approval → Start POC
