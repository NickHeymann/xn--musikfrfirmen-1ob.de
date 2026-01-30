# Musikfürfirmen.de UI Improvements - Design Document

**Date:** 2026-01-30
**Project:** musikfürfirmen.de (TALL Stack)
**Status:** Ready for Implementation

---

## Overview

Comprehensive UI/UX improvements for musikfürfirmen.de including Cal.com-style booking calendar, hero video background, animations, and consistent branding with mintgrün (#b8ddd2).

---

## Design Decisions

### Color Scheme
- **Primary Brand Green:** `#b8ddd2` (Mintgrün)
- **Text:** Black (#000) for buttons, Dark Gray (#374151) for body text
- **Backgrounds:** White, Light sections with subtle gradients
- **Accents:** Brand green for CTAs, icons, and interactive elements

### Typography System (Poppins)
```css
/* Headings */
h1: font-poppins font-semibold text-5xl md:text-6xl
h2: font-poppins font-semibold text-4xl md:text-5xl
h3: font-poppins font-semibold text-2xl md:text-3xl

/* Body Text */
Large: font-poppins text-lg md:text-xl
Medium: font-poppins text-base md:text-lg (default)
Small: font-poppins text-sm md:text-base

/* Weights */
Headings: font-semibold (600)
Body: font-normal (400)
Buttons: font-medium (500)
```

### Button Design
```css
.btn-primary, .btn-secondary {
  @apply inline-block px-8 py-4 rounded-[2rem] border-2 border-black
         font-poppins font-medium text-base uppercase tracking-wide
         transition-all duration-300;
}

.btn-primary {
  background: #b8ddd2;
  color: #000;
}

.btn-primary:hover {
  background: #a5cdbf; /* 10% darker */
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(184, 221, 210, 0.4);
}
```

---

## Component Designs

### 1. Cal.com-Style Booking Calendar

**Architecture:**
- **Component:** Livewire Component (`BookingCalendar.php`)
- **Service:** `GoogleCalendarService.php` for Calendar API integration
- **MCP Integration:** `mcp__google-workspace-mff__*` tools

**UI Pattern:**
- **Layout:** Left-Right Split (3:2 ratio)
  - Left: Calendar with month navigation
  - Right: Time slots → Form (progressive disclosure)
- **Flow:** Select Date → Select Time → Enter Details → Confirm
- **Design:** Cal.com minimal aesthetic with musikfürfirmen.de branding

**Key Features:**
- Only weekdays (Mo-Fr) selectable
- Business hours: 9:00-18:00 (30-min slots)
- Real-time availability from Google Calendar
- Automatic email confirmation with Google Meet link
- Timezone display (Europe/Berlin)
- Mobile-responsive (stacks vertically on mobile)

**Files to Create:**
```
app/Livewire/BookingCalendar.php
app/Services/GoogleCalendarService.php
resources/views/livewire/booking-calendar.blade.php
routes/web.php (add /erstgespraech route)
```

---

### 2. Hero Section with Video Background

**Design:**
- **Video:** `/videos/hero-landing-page.mp4` (Musikfürfirmen Landing Page Video)
- **Overlay:** `bg-black/45` (45% opacity) for text readability
- **Layout:** Centered text with CTAs below
- **Video Settings:** autoplay, muted, loop, playsinline, object-cover

**CTAs:**
- Primary: "JETZT BUCHEN" → scrolls to contact form
- Secondary: "KOSTENLOSES ERSTGESPRÄCH" → routes to `/erstgespraech`

**Files to Modify:**
```
resources/views/components/hero.blade.php
public/videos/ (add hero-landing-page.mp4)
```

---

### 3. Footer Logo Animation

**Animation:** Musical notes (♪) float up on hover

**Implementation:**
```blade
<div x-data="{ hovering: false }" @mouseenter="hovering = true" @mouseleave="hovering = false">
  <svg class="logo">...</svg>
  <div class="notes-container">
    <template x-for="i in 5">
      <div class="musical-note" :class="{ 'note-animate': hovering }">♪</div>
    </template>
  </div>
</div>
```

**CSS:**
- Notes start at `translateY(0)` with `opacity: 0`
- Animate to `translateY(-80px)` with fade-out
- Stagger delay: `i * 0.1s`
- Color: `#b8ddd2`

**Files to Modify:**
```
resources/views/components/footer.blade.php
resources/css/app.css (add @keyframes float-up)
```

---

### 4. Section Animations

**Dienstleistungen (Services):**
- **Trigger:** Alpine.js `x-intersect`
- **Animation:** Fade-in + translate-y from bottom
- **Stagger:** 150ms delay between cards
- **Duration:** 700ms ease-out

**Warum Wir (Why Us):**
- **Trigger:** Alpine.js `x-intersect`
- **Animation:** Slide-in from left
- **Checkmark Icons:** `#b8ddd2` background, black checkmark SVG
- **Stagger:** 100ms delay between items
- **Duration:** 600ms ease-out

**Files to Modify:**
```
resources/views/components/services-section.blade.php
resources/views/components/why-us-section.blade.php
```

---

### 5. Team Photo Popup Modals

**Interaction:**
- **Hover:** Scale image to 110% + dark overlay
- **Click:** Open modal with large photo + bio
- **Modal:** Rounded-3xl, centered, click-away to close

**Modal Content:**
- Left: Large photo (rounded-2xl)
- Right: Name, Role (#b8ddd2), Bio text
- Close button: top-right, rounded-full bg-gray-100

**Files to Modify:**
```
resources/views/components/team-section.blade.php
```

---

### 6. Event Photo Gallery (Swipeable)

**UI Components:**
- **Container:** Rounded-3xl with overflow-hidden
- **Navigation:** Left/Right arrows (mintgrün circles with black SVG)
- **Indicators:** Dots below (active dot is wider, mintgrün)
- **Images:** 500px height, object-cover

**Interaction:**
- Click arrows to navigate
- Touch swipe on mobile (future enhancement)
- Auto-reset to slide 0 on loop

**Content:**
- Section title: "Unser letztes Event."
- Below gallery: Link to band page with underlined text (#b8ddd2)

**Files to Create:**
```
resources/views/components/event-gallery.blade.php
app/Livewire/EventGallery.php (if dynamic photos)
```

---

### 7. Dynamic Header Color Adaptation

**Behavior:** Header changes color based on scroll position over sections

**Implementation:**
```javascript
// Alpine.js component
x-data="dynamicHeader()"

function dynamicHeader() {
  return {
    headerClass: 'bg-transparent text-white',
    init() {
      const sections = document.querySelectorAll('[data-section-theme]');
      window.addEventListener('scroll', () => {
        // Detect current section
        // Update headerClass based on data-section-theme
      });
    }
  }
}
```

**Section Attributes:**
```blade
<section data-section-theme="dark">...</section> <!-- Hero, Event Gallery -->
<section data-section-theme="light">...</section> <!-- Services, Why Us -->
```

**Header States:**
- **Dark sections:** `bg-black/90 backdrop-blur-sm text-white`
- **Light sections:** `bg-white/90 backdrop-blur-sm text-black shadow-sm`
- **Transition:** `transition-all duration-300`

**Files to Modify:**
```
resources/views/components/header.blade.php
resources/js/app.js (add Alpine component)
```

---

### 8. Typography Standardization

**Changes:**
1. **Import Poppins:** Add to `app.blade.php` head
2. **Update Tailwind Config:** Set Poppins as default font
3. **Replace all font classes:** Update throughout all Blade templates
4. **Consistent sizing:** Apply size system to all headings/text

**Tailwind Config:**
```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      fontFamily: {
        poppins: ['Poppins', 'sans-serif'],
        sans: ['Poppins', 'sans-serif'],
      },
    },
  },
}
```

**Files to Modify:**
```
resources/views/layouts/app.blade.php (add Google Fonts link)
tailwind.config.js
All component Blade files (global find/replace)
```

---

### 9. Button Styling Update

**Components to Update:**
- Hero CTAs
- Footer contact button
- Event Request Modal submit button
- Newsletter signup button
- Navigation "Jetzt Buchen" button

**Unified Button Classes:**
```blade
<!-- Primary Button -->
<button class="btn-primary">
  JETZT BUCHEN
</button>

<!-- Secondary Button (transparent with white border) -->
<button class="btn-secondary">
  MEHR ERFAHREN
</button>
```

**Files to Modify:**
```
resources/views/components/hero.blade.php
resources/views/components/footer.blade.php
resources/views/livewire/event-request-modal.blade.php
resources/views/components/header.blade.php
resources/css/app.css (add .btn-primary, .btn-secondary)
```

---

### 10. Email Delivery Fix

**Issue:** Contact form submissions don't send emails to moin@jonas

**Investigation Steps:**
1. Check Brevo API configuration in `.env`
2. Review `EventRequestModal` Livewire component email logic
3. Test email service with `php artisan tinker`
4. Check logs: `storage/logs/laravel.log`
5. Verify Brevo sender/recipient configuration

**Files to Check:**
```
.env (MAIL_* variables)
config/mail.php
app/Livewire/EventRequestModal.php
app/Mail/* (if custom mail classes exist)
```

---

## Implementation Order

### Phase 1: Foundation (Design System)
1. ✅ Typography standardization (Poppins)
2. ✅ Button styling update
3. ✅ Color variables in Tailwind config

### Phase 2: Core Features
4. ✅ Hero video background
5. ✅ Cal.com booking calendar
6. ✅ Email delivery fix

### Phase 3: Animations & Interactions
7. ✅ Footer logo animation
8. ✅ Services/Why Us scroll animations
9. ✅ Team photo modals
10. ✅ Event gallery carousel
11. ✅ Dynamic header

---

## Technical Stack

**Frontend:**
- **Framework:** Laravel 12 + Livewire 4
- **CSS:** Tailwind CSS 4
- **JS:** Alpine.js (no additional dependencies)
- **Fonts:** Google Fonts (Poppins)

**Backend:**
- **Calendar Sync:** Google Calendar API via MCP
- **Email:** Brevo (already configured)
- **Database:** PostgreSQL (shared on Hetzner)

**Deployment:**
- **Server:** Hetzner (46.224.6.69)
- **CI/CD:** GitHub Actions → Docker → Traefik
- **Domain:** musikfürfirmen.de (punycode: xn--musikfrfirmen-1ob.de)

---

## Google Calendar Integration Setup

### Prerequisites
1. **Google Cloud Project:** Create/use existing project
2. **Enable Google Calendar API:** In Google Cloud Console
3. **Service Account:** Create with Calendar Editor permissions
4. **Calendar ID:** Get from musikfürfirmen.de Google Calendar settings
5. **MCP Authentication:** Configure `mcp__google-workspace-mff__*` tools

### MCP Setup Steps
```bash
# 1. Authenticate MCP Server for musikfürfirmen.de
# (Follow MCP Google Workspace authentication flow)

# 2. Test connection
php artisan tinker
# Test: mcp__google-workspace-mff__listCalendars

# 3. Get Calendar ID
# From Google Calendar Settings → Integrate Calendar → Calendar ID

# 4. Update .env
GOOGLE_CALENDAR_ID=primary # or specific calendar ID
```

### Service Implementation
```php
// app/Services/GoogleCalendarService.php

class GoogleCalendarService
{
    protected $calendarId = 'primary'; // or env('GOOGLE_CALENDAR_ID')

    public function getAvailableSlots($date) {
        // 1. Generate business hour slots (9:00-18:00, 30-min intervals)
        // 2. Fetch existing events via MCP
        // 3. Filter out booked slots
        // 4. Return available slots
    }

    public function createEvent($data) {
        // Use mcp__google-workspace-mff__createCalendarEvent
        // Return event with Google Meet link
    }
}
```

---

## File Structure

```
tall-stack/
├── app/
│   ├── Livewire/
│   │   ├── BookingCalendar.php (NEW)
│   │   └── EventRequestModal.php (MODIFY)
│   ├── Services/
│   │   └── GoogleCalendarService.php (NEW)
│   └── Mail/
│       └── BookingConfirmation.php (NEW)
├── resources/
│   ├── views/
│   │   ├── livewire/
│   │   │   └── booking-calendar.blade.php (NEW)
│   │   └── components/
│   │       ├── hero.blade.php (MODIFY - video bg)
│   │       ├── header.blade.php (MODIFY - dynamic color)
│   │       ├── footer.blade.php (MODIFY - logo animation)
│   │       ├── services-section.blade.php (MODIFY - animations)
│   │       ├── why-us-section.blade.php (MODIFY - animations)
│   │       ├── team-section.blade.php (MODIFY - modals)
│   │       └── event-gallery.blade.php (NEW)
│   ├── css/
│   │   └── app.css (ADD button styles, animations)
│   └── js/
│       └── app.js (ADD Alpine components)
├── routes/
│   └── web.php (ADD /erstgespraech route)
├── tailwind.config.js (UPDATE fonts, colors)
└── docs/
    └── plans/
        └── 2026-01-30-musikfuerfirmen-ui-improvements.md (THIS FILE)
```

---

## Testing Checklist

### Booking Calendar
- [ ] Calendar displays correctly (weekdays only, current month)
- [ ] Month navigation works (prev/next)
- [ ] Date selection highlights correctly
- [ ] Time slots load for selected date
- [ ] Booked slots are hidden
- [ ] Form validation works (name, email required)
- [ ] Event creates in Google Calendar
- [ ] Confirmation email sends with Google Meet link
- [ ] Mobile responsive (stacks vertically)

### Animations
- [ ] Footer logo notes animation triggers on hover
- [ ] Services cards fade in on scroll
- [ ] Why Us items slide in with stagger
- [ ] Team modals open/close correctly
- [ ] Event gallery swipes smoothly
- [ ] Dynamic header changes color on scroll

### Typography & Buttons
- [ ] All text uses Poppins font
- [ ] Font sizes are consistent
- [ ] Buttons have correct mintgrün background
- [ ] Button hover effects work
- [ ] Mobile font sizes scale appropriately

### Hero Video
- [ ] Video loads and plays automatically
- [ ] Overlay makes text readable
- [ ] CTAs route correctly
- [ ] Video loops seamlessly
- [ ] Mobile performance is acceptable

### Email Delivery
- [ ] Contact form sends to moin@jonas
- [ ] Booking confirmation sends to customer
- [ ] Email templates render correctly
- [ ] Brevo logs show successful sends

---

## Performance Considerations

### Video Optimization
- **Format:** MP4 (H.264 codec)
- **Resolution:** 1920x1080 max
- **File Size:** < 5MB (compress if larger)
- **Loading:** Use `poster` attribute for placeholder
- **Mobile:** Consider disabling video on mobile (show static image)

### Animation Performance
- Use `transform` and `opacity` (GPU-accelerated)
- Avoid animating `width`, `height`, `margin`
- Debounce scroll listeners (dynamic header)
- Use `will-change` sparingly (only during animation)

### Calendar Performance
- Cache available slots (5-min TTL)
- Lazy-load time slots (only for selected date)
- Debounce Google Calendar API calls
- Show loading states during API requests

---

## Deployment Strategy

### Pre-Deployment
1. Test all features locally
2. Run Laravel tests: `php artisan test`
3. Check responsive design (mobile, tablet, desktop)
4. Validate Google Calendar API connection
5. Test email delivery (Brevo sandbox)

### Deployment Steps
1. Create feature branch: `feature/ui-improvements-2026-01-30`
2. Commit changes with descriptive messages
3. Push to GitHub (triggers CI/CD)
4. GitHub Actions builds Docker image
5. Deploy to Hetzner via SSH
6. Run migrations (if any): `php artisan migrate`
7. Clear cache: `php artisan optimize:clear`
8. Test production site

### Post-Deployment
1. Test booking flow on production
2. Verify email delivery
3. Check animations on real devices
4. Monitor logs for errors
5. Gather user feedback

---

## Accessibility Considerations

### Keyboard Navigation
- All interactive elements focusable (buttons, links, form inputs)
- Calendar navigable with arrow keys
- Modal closable with `Escape` key
- Focus trap in modals

### Screen Readers
- Proper ARIA labels for icons
- Alt text for images
- Semantic HTML (headings, nav, footer)
- Form labels associated with inputs

### Color Contrast
- Text on #b8ddd2 background: Black (#000) passes WCAG AA
- Overlay opacity ensures text readability
- Focus states have sufficient contrast

---

## Browser Compatibility

**Target Browsers:**
- Chrome 100+
- Firefox 100+
- Safari 15+
- Edge 100+
- Mobile Safari (iOS 15+)
- Chrome Mobile (Android 10+)

**Fallbacks:**
- Video not supported → Show static hero image
- Alpine.js fails → Static content remains visible
- Google Calendar API fails → Show error message with email contact

---

## Future Enhancements

### Phase 4 (Future)
- [ ] Calendar sync with Outlook/iCal
- [ ] SMS reminders for bookings
- [ ] Recurring event templates
- [ ] Multi-language support (EN/DE toggle)
- [ ] Calendar widget embed for external sites
- [ ] Analytics tracking (booking conversion)
- [ ] A/B testing framework for CTAs

---

## Questions & Decisions

### Resolved
- ✅ **Kalender-Design:** Cal.com-style with left-right split
- ✅ **Button-Farbe:** #b8ddd2 (Mintgrün)
- ✅ **Video-Overlay:** 45% dark overlay (Option A)
- ✅ **Logo-Animation:** Musical notes float up on hover
- ✅ **Schriftart:** Poppins für alles

### Open
- **Rechner-Design:** Wo soll der Budget-Rechner integriert werden? Als separate Seite oder Modal?
- **Event-Fotos:** Sollen Event-Fotos dynamisch aus Datenbank kommen oder statisch?
- **Calendar-Berechtigungen:** Brauchen wir separate Kalender für verschiedene Event-Typen?

---

## Success Metrics

### User Experience
- Booking completion rate > 80%
- Avg. time to book < 2 minutes
- Mobile usability score > 90

### Performance
- Largest Contentful Paint (LCP) < 2.5s
- First Input Delay (FID) < 100ms
- Cumulative Layout Shift (CLS) < 0.1

### Business
- Increase in bookings from website
- Reduction in manual calendar management
- Improved brand perception (feedback)

---

## Contact & Support

**Developer:** Nick Heymann (Claude Code)
**Client:** Jonas (moin@jonas)
**Project Repo:** GitHub (nickheymann/xn--musikfrfirmen-1ob.de)
**Server:** Hetzner (46.224.6.69)

---

**Document Status:** ✅ Ready for Implementation
**Next Step:** Begin Phase 1 (Foundation) implementation
