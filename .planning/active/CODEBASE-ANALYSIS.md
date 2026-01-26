# Codebase Analysis: musikfürfirmen.de Booking UX
**Goal:** Improve the booking UX somehow
**Date:** 2026-01-26

---

## Tech Stack Overview

| Component | Technology | Version |
|-----------|-----------|---------|
| **Framework** | Next.js | 16.0.5 |
| **Language** | TypeScript | 5.x |
| **Styling** | Tailwind CSS | 4.x |
| **UI Library** | React | 19.2.0 |
| **Animations** | Framer Motion | 12.23.24 |
| **Validation** | Zod | 4.3.5 |
| **Icons** | Lucide React | 0.562.0 |
| **Deployment** | Vercel | Auto-deploy on `main` |

**Key Dependencies:**
- `@dnd-kit` - Drag & drop functionality
- `@tiptap` - Rich text editor
- `dompurify` - HTML sanitization
- `nanoid` - Unique ID generation
- `use-debounce` - Input debouncing

**Backend Infrastructure:**
- **Hosting**: Vercel (frontend), Hetzner CX42 (backend services)
- **n8n**: Workflow automation on Hetzner (46.224.6.69)
- **PostgreSQL**: Shared database on Hetzner
- **Traefik**: Reverse proxy + SSL

---

## Architecture Pattern

**Configuration-Driven React with Single Source of Truth**

```
src/
├── app/              # Next.js 16 App Router (pages/routes)
├── components/       # Reusable React components
│   ├── contact/      # Multi-step booking modal (CRITICAL)
│   │   ├── ContactModal.tsx          # Orchestrator
│   │   ├── useContactForm.ts         # State + validation
│   │   ├── ContactStep1.tsx          # Event details
│   │   ├── ContactStep2.tsx          # Package selection
│   │   ├── ContactStep3.tsx          # Contact info
│   │   └── ContactSuccess.tsx        # Success screen
│   └── icons/        # Icon components
├── config/           # Single Source of Truth (SSoT)
│   └── site.ts       # Site config, packages, guest options
├── data/             # Static content data
│   ├── faq.ts        # FAQ items
│   ├── team.ts       # Team members
│   ├── services.ts   # Process steps
│   └── jsonLd.ts     # SEO structured data
├── types/            # TypeScript interfaces
│   └── index.ts      # All type definitions
├── lib/              # Utilities
│   ├── error-logger.ts
│   └── api/client.ts
└── contexts/         # React Contexts
    └── EditorAuthContext.tsx
```

**Key Architectural Decisions:**
1. **Single Source of Truth**: All config in `src/config/site.ts`
2. **Type Safety**: All interfaces in `src/types/index.ts`, no `any` types
3. **Separation of Concerns**: Data separate from components
4. **Custom Hooks**: Form logic in `useContactForm.ts`, UI in step components
5. **Modular Components**: Contact form is self-contained in `src/components/contact/`

---

## Current Booking Flow (3-Step Modal)

### Entry Points
1. **Hero Section** (`src/components/Hero.tsx`)
   - Primary CTA button triggers contact modal
   - Hero section with video background

2. **CTA Section** (`src/components/CTASection.tsx`)
   - Secondary CTA (green gradient background)
   - Same modal trigger

3. **Modal Provider** (`src/components/ModalProvider.tsx`)
   - Context-based modal state management
   - `openContactModal()` function exposed globally

### Step 1: Event Details (`ContactStep1.tsx`)
**Fields:**
- **Date*** (required)
  - Type: Date picker
  - Validation: Must be today or future, 4-digit year, max +5 years
  - Error messages: "Bitte wählen Sie ein Datum", "Das Datum liegt in der Vergangenheit"
- **Time** (optional)
  - Type: Time picker
  - No validation
- **City*** (required)
  - Type: Text input with autocomplete
  - API: Photon Komoot (German cities only)
  - Validation: Required, min 2 chars for autocomplete
  - Features: Dropdown suggestions with state/county info
- **Budget** (optional)
  - Type: Free text input
  - Placeholder: "z.B. 5.000 €"
  - No validation
- **Guests*** (required)
  - Type: Dropdown select
  - Options: "< 100", "100-300", "300-500", "> 500"
  - Source: `guestOptions` from `src/config/site.ts`

**Current Pain Points:**
- ❌ Budget is free text → inconsistent data
- ❌ No event type field (Firmenevent, Weihnachtsfeier, etc.)
- ⚠️ Time is optional but important for planning

### Step 2: Package Selection (`ContactStep2.tsx`)
**Options:**
- "Nur DJ"
- "Full Band"
- "Full Band + DJ"

**UI:**
- Radio buttons with custom styling
- Hover effects: border color, lift animation
- Selected state: mint green border + background

**Current Pain Points:**
- ❌ No visual preview of packages
- ❌ No description of what's included
- ❌ No pricing indication
- ❌ No "Unsicher?" CTA for consultation

### Step 3: Contact Info (`ContactStep3.tsx`)
**Fields:**
- **Name*** (required)
  - Validation: Trim whitespace, must not be empty
- **Company** (optional)
- **Email*** (required)
  - Validation: Regex `/^[^\s@]+@[^\s@]+\.[^\s@]+$/`
- **Phone*** (required)
- **Message** (optional)
  - Type: Textarea
- **Privacy checkbox*** (required)
  - Must be checked to submit

**Current Pain Points:**
- ✅ Validation is good
- ❌ No confirmation of what will happen after submission

### Submission Flow
1. Client-side validation (step-by-step)
2. Construct email body with all form data
3. **Open `mailto:` link** (CRITICAL ISSUE)
4. Show success message in modal

**CRITICAL ISSUES:**
- ❌ **`mailto:` is unreliable**: Depends on email client, can fail silently
- ❌ **No server-side storage**: Lost submissions if email fails
- ❌ **No tracking**: Can't measure conversion rate
- ❌ **No automation**: Manual processing required
- ❌ **No fallback**: If email client fails, inquiry is lost

---

## Key Files (Relevant to Booking UX)

### Contact Modal System
| File | Purpose | Lines | Status |
|------|---------|-------|--------|
| `src/components/contact/ContactModal.tsx` | Modal orchestrator, step navigation | 115 | ✅ Good |
| `src/components/contact/useContactForm.ts` | Form state, validation, submission | 213 | ⚠️ Needs refactor |
| `src/components/contact/ContactStep1.tsx` | Event details form | 167 | ⚠️ Needs enhancement |
| `src/components/contact/ContactStep2.tsx` | Package selection | 58 | ⚠️ Needs visual preview |
| `src/components/contact/ContactStep3.tsx` | Contact info | ~120 | ✅ Good |
| `src/components/contact/ContactSuccess.tsx` | Success screen | ~40 | ✅ Good |

### Configuration & Data
| File | Purpose |
|------|---------|
| `src/config/site.ts` | SSoT: email, phone, cal.com URL, package options, guest options |
| `src/types/index.ts` | TypeScript interfaces: ContactFormData, CityResult, PackageOption |
| `src/data/faq.ts` | FAQ items (mentions booking process in Q2) |
| `src/data/services.ts` | Process steps ("60 seconds", "24 hours", "Rundum-Service") |

### Entry Points
| File | Purpose |
|------|---------|
| `src/components/Hero.tsx` | Hero section with primary CTA |
| `src/components/CTASection.tsx` | Secondary CTA section |
| `src/components/ModalProvider.tsx` | Context provider for modal state |

---

## Patterns to Follow

### 1. TypeScript Conventions
```typescript
// All types in src/types/index.ts
export interface ContactFormData {
  date: string;
  time: string;
  city: string;
  budget: string;
  guests: string;
  package: string;
  name: string;
  company: string;
  email: string;
  phone: string;
  message: string;
  privacy: boolean;
}

// Strict type safety
interface ContactStepProps {
  formData: ContactFormData;
  onUpdateField: <K extends keyof ContactFormData>(
    field: K,
    value: ContactFormData[K]
  ) => void;
}
```

### 2. Component Structure
```typescript
// 1. Imports
import type { ContactFormData } from "@/types";
import { packageOptions } from "@/config/site";

// 2. Interface for props
interface ContactStepProps {
  // Props definition
}

// 3. Component with destructured props
export default function ContactStep({ formData, onUpdateField }: ContactStepProps) {
  // Component logic
}
```

### 3. Styling Conventions
- **Color Palette**:
  - Primary: `#B2EAD8` (mint green)
  - Hover: `#7dc9b1` (darker mint)
  - Text: `#1a1a1a`, `#292929`, `#666666`
  - Error: `red-600`
- **Font**: `font-family: 'Poppins', sans-serif`
- **Borders**: `rounded-[10px]` (consistent)
- **Transitions**: `duration-200` or `duration-300`

### 4. Form Patterns
```typescript
// Validation per step
const validateStep1 = useCallback(() => {
  const newErrors: Record<string, string> = {};
  if (!formData.date) {
    newErrors.date = "Bitte wählen Sie ein Datum";
  }
  setErrors(newErrors);
  return Object.keys(newErrors).length === 0;
}, [formData]);

// Input base classes
const inputBaseClass = "w-full p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white text-[#1a1a1a] transition-all duration-200 focus:outline-none focus:border-[#B2EAD8] focus:shadow-[0_0_0_4px_rgba(178,234,216,0.1)]";
const inputErrorClass = "border-red-600";
const inputNormalClass = "border-[#e0e0e0]";
```

### 5. State Management
- **Custom Hooks**: Extract logic from components
- **useCallback**: Memoize validation functions
- **Type-Safe Updates**: Generic updateField function

---

## Recommended Approach

### 🔴 Priority 1: Fix Critical Backend Issue (URGENT)

**Problem:** `mailto:` links are unreliable, no data persistence, no automation

**Solution:** Integrate with n8n workflow on Hetzner server

**Implementation:**
1. Create API route: `src/app/api/contact/route.ts`
2. Add Zod validation schema
3. POST to n8n webhook
4. n8n workflow:
   - Store in PostgreSQL (Supabase self-hosted)
   - Send email to `kontakt@musikfürfirmen.de`
   - Send confirmation email to customer
   - Log to analytics
5. Add error handling + fallback to mailto

**Code Pattern:**
```typescript
// src/app/api/contact/route.ts
import { z } from "zod";

const contactSchema = z.object({
  date: z.string().min(1),
  time: z.string().optional(),
  city: z.string().min(1),
  budget: z.string().optional(),
  guests: z.string().min(1),
  package: z.string().min(1),
  name: z.string().min(1),
  company: z.string().optional(),
  email: z.string().email(),
  phone: z.string().min(1),
  message: z.string().optional(),
  privacy: z.boolean().refine((val) => val === true),
});

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const result = contactSchema.safeParse(body);

    if (!result.success) {
      return Response.json(
        { error: "Invalid data", details: result.error.flatten() },
        { status: 400 }
      );
    }

    // Send to n8n webhook
    const response = await fetch(process.env.N8N_WEBHOOK_URL!, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(result.data),
    });

    if (!response.ok) {
      throw new Error("n8n webhook failed");
    }

    return Response.json({ success: true });
  } catch (error) {
    console.error("Contact form error:", error);
    return Response.json(
      { error: "Internal server error" },
      { status: 500 }
    );
  }
}
```

**Update `useContactForm.ts`:**
```typescript
const handleSubmit = async () => {
  if (!validateStep3()) return;
  setIsSubmitting(true);

  try {
    const response = await fetch("/api/contact", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(formData),
    });

    if (!response.ok) {
      throw new Error("API submission failed");
    }

    setSubmitStatus("success");
  } catch (error) {
    console.error("Submission error:", error);
    // Fallback to mailto
    const mailtoLink = constructMailtoLink(formData);
    window.location.href = mailtoLink;
    setSubmitStatus("success");
  } finally {
    setIsSubmitting(false);
  }
};
```

**Estimated Effort:** 4-6 hours (includes n8n workflow setup)

---

### 🟡 Priority 2: Enhance Step 1 (Event Details)

**Problems:**
- Budget is free text → inconsistent data
- No event type selection
- Time field is optional but important

**Solutions:**

#### 2.1 Add Event Type Field
```typescript
// src/config/site.ts
export const eventTypeOptions = [
  { value: "firmenevent", label: "Firmenevent" },
  { value: "weihnachtsfeier", label: "Weihnachtsfeier" },
  { value: "jubilaeum", label: "Jubiläum" },
  { value: "sommerfest", label: "Sommerfest" },
  { value: "produktpraesentation", label: "Produktpräsentation" },
  { value: "sonstiges", label: "Sonstiges" },
];

// src/types/index.ts
export interface ContactFormData {
  // ... existing fields
  eventType: string; // NEW
}
```

#### 2.2 Change Budget to Dropdown
```typescript
// src/config/site.ts
export const budgetOptions = [
  { value: "lt2000", label: "< 2.000 €" },
  { value: "2000-5000", label: "2.000 - 5.000 €" },
  { value: "5000-10000", label: "5.000 - 10.000 €" },
  { value: "gt10000", label: "> 10.000 €" },
  { value: "unclear", label: "Noch unklar" },
];
```

#### 2.3 Improve Time Field UX
- Add "Ganztägig" checkbox (makes time optional)
- Show quick-select buttons for common times: "18:00", "19:00", "20:00"

**Estimated Effort:** 2-3 hours

---

### 🟡 Priority 3: Add Visual Package Preview (Step 2)

**Problem:** Just radio buttons with text labels, no context

**Solution:** Enhance package options with descriptions, icons, and pricing

**Update Type:**
```typescript
// src/types/index.ts
export interface PackageOption {
  value: PackageType;
  label: string;
  description: string;        // NEW
  included: string[];         // NEW
  icon?: string;             // NEW
  priceFrom?: string;        // NEW
}

// src/config/site.ts
export const packageOptions: PackageOption[] = [
  {
    value: "dj",
    label: "Nur DJ",
    description: "Professioneller DJ für durchgehende Stimmung",
    included: ["DJ-Equipment", "Lichtanlage", "Musikauswahl"],
    priceFrom: "ab 1.500 €",
  },
  {
    value: "band",
    label: "Full Band",
    description: "Live-Band für authentische Atmosphäre",
    included: ["Live-Band (4-5 Personen)", "Technik", "Soundcheck"],
    priceFrom: "ab 3.500 €",
  },
  {
    value: "band_dj",
    label: "Full Band + DJ",
    description: "Das Beste aus beiden Welten",
    included: ["Live-Band", "DJ-Set", "Komplette Technik"],
    priceFrom: "ab 5.000 €",
  },
];
```

**Add "Unsicher?" Link:**
```typescript
// In ContactStep2.tsx
<a
  href={siteConfig.calComUrl}
  target="_blank"
  rel="noopener noreferrer"
  className="text-sm text-[#0D7A5F] hover:underline"
>
  Unsicher? Buche ein kostenloses Beratungsgespräch
</a>
```

**Estimated Effort:** 2-3 hours

---

### 🟢 Priority 4: Add Progress Indicator

**Add visual progress bar:**
```typescript
// In ContactModal.tsx
<div className="flex items-center justify-center gap-2 mb-6">
  {[1, 2, 3].map((i) => (
    <div
      key={i}
      className={`h-2 w-12 rounded-full transition-all duration-300 ${
        i <= step ? "bg-[#B2EAD8]" : "bg-gray-200"
      }`}
    />
  ))}
</div>
<p className="text-center text-sm text-gray-600 mb-4">
  Schritt {step} von 3
</p>
```

**Estimated Effort:** 1 hour

---

### 🟢 Priority 5: Smart Defaults & Autofill

1. **Location Detection**: Use browser geolocation to pre-fill city (opt-in)
2. **Remember Me**: Save form data to localStorage (with consent checkbox)
3. **Calendar Integration**: Add .ics export after successful booking

**Estimated Effort:** 2 hours each

---

### 🟢 Priority 6: Mobile Optimization

- Test touch interactions on iOS/Android
- Optimize autocomplete dropdown for mobile keyboards
- Ensure date/time pickers work cross-platform
- Test on actual devices (not just emulators)

**Estimated Effort:** 2-3 hours

---

### 🟢 Priority 7: Analytics & Conversion Tracking

- Track step completion rates (which step has highest drop-off?)
- Track package selection distribution
- Track city/event type patterns
- Add Vercel Analytics events

```typescript
// In useContactForm.ts
import { track } from "@vercel/analytics";

const handleNext = () => {
  if (step === 1 && validateStep1()) {
    track("booking_step_1_completed", {
      city: formData.city,
      guests: formData.guests,
    });
    setStep(2);
  }
  // ...
};
```

**Estimated Effort:** 1-2 hours

---

## Summary of Issues & Opportunities

### 🔴 Critical Issues (Fix First)
1. **`mailto:` submission is unreliable** → Integrate n8n webhook + API route
2. **No data persistence** → Store inquiries in PostgreSQL
3. **No automation** → n8n workflow for email + CRM

### 🟡 High-Priority Enhancements
4. **Budget field is free text** → Change to dropdown with ranges
5. **No event type selection** → Add dropdown field
6. **No visual package preview** → Add descriptions, icons, pricing
7. **Time field UX** → Add quick-select buttons + "Ganztägig" option

### 🟢 Nice-to-Have Improvements
8. **No progress indicator** → Add visual step counter
9. **No smart defaults** → Geolocation, localStorage persistence
10. **No analytics** → Track conversion funnels
11. **Mobile optimization** → Test cross-device, improve touch targets

---

## Implementation Waves (GSD-Ready)

### Wave 1: Backend Integration (Critical) - 6 hours
1. Create `src/app/api/contact/route.ts` with Zod validation
2. Update `useContactForm.ts` to use API instead of mailto
3. Set up n8n webhook on Hetzner server
4. Configure n8n workflow (email, database, confirmation)
5. Add error handling + mailto fallback
6. Test end-to-end submission flow

### Wave 2: Step 1 Enhancements - 3 hours
7. Add event type dropdown to ContactStep1
8. Change budget to dropdown with ranges
9. Add quick-select time buttons + "Ganztägig" checkbox
10. Update types and validation

### Wave 3: Step 2 Visual Preview - 3 hours
11. Enhance PackageOption type with description, included, priceFrom
12. Update ContactStep2 UI with visual cards
13. Add "Unsicher?" link to cal.com
14. Test package selection UX

### Wave 4: Progress & Analytics - 2 hours
15. Add progress indicator component
16. Add Vercel Analytics tracking events
17. Test analytics in production

### Wave 5: Mobile & Polish - 3 hours
18. Test on iOS/Android devices
19. Optimize touch targets (min 48px)
20. Test autocomplete on mobile keyboards
21. Cross-browser testing

**Total Estimated Effort:** ~17 hours across 5 waves

---

## Next Steps

1. **Review this analysis** with stakeholder
2. **Prioritize waves** based on business impact (Wave 1 is critical)
3. **Create implementation plan** in `.planning/active/PLAN.md`
4. **Execute using GSD workflow** with verification checkpoints
5. **Deploy incrementally** (test each wave before moving to next)

---

**Analysis Complete** ✓

**Date:** 2026-01-26
**Analyzer:** Claude Sonnet 4.5
**Status:** Ready for implementation planning
