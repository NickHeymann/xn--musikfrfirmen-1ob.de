# musikfürfirmen.de - System Architecture

## Overview

TALL Stack CMS with dual-purpose admin panel for both public content management and customer operations.

---

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    MUSIKFÜRFIRMEN.DE                             │
│                   TALL Stack Application                         │
└─────────────────────────────────────────────────────────────────┘
                              │
                              │
        ┌─────────────────────┴─────────────────────┐
        │                                           │
        ▼                                           ▼
┌──────────────────┐                      ┌──────────────────┐
│   Public Site    │                      │   Admin Panel    │
│  (Next.js/Blade) │                      │   (Filament 4)   │
└──────────────────┘                      └──────────────────┘
        │                                           │
        │ Reads Data                                │ Manages Data
        │                                           │
        └─────────────────────┬─────────────────────┘
                              ▼
                    ┌──────────────────┐
                    │  Laravel Models  │
                    │    (Eloquent)    │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  MySQL Database  │
                    │   (7 tables)     │
                    └──────────────────┘
```

---

## Navigation Structure

```
Admin Sidebar
│
├── 📝 Content Management
│   │
│   ├── Services                 (Sort: 1)
│   │   └── Music offerings for companies
│   │
│   ├── Team Members             (Sort: 2)
│   │   └── Band/DJ profiles with images
│   │
│   ├── FAQs                     (Sort: 3)
│   │   └── Frequently asked questions
│   │
│   └── Pages                    (Sort: 4)
│       └── Custom content pages (legal, info)
│
└── 👥 Customer Management
    │
    ├── Contact Submissions      (Sort: 1)
    │   └── Inquiries from contact form
    │
    ├── Events                   (Sort: 2)
    │   └── Available events for booking
    │
    └── Bookings                 (Sort: 3)
        └── Event bookings (linked to Events)
```

---

## Database Schema Relationships

```
┌──────────────┐
│   Services   │     No relationships (standalone)
│   (3 rows)   │     Public-facing service descriptions
└──────────────┘

┌──────────────┐
│ TeamMembers  │     No relationships (standalone)
│   (2 rows)   │     Band/DJ profiles
└──────────────┘

┌──────────────┐
│     FAQs     │     No relationships (standalone)
│   (7 rows)   │     Question & answer pairs
└──────────────┘

┌──────────────┐
│    Pages     │     No relationships (standalone)
│   (0 rows)   │     Custom content pages
└──────────────┘

┌──────────────┐
│   Contact    │     No relationships (standalone)
│ Submissions  │     Contact form submissions
│   (0 rows)   │
└──────────────┘

┌──────────────┐
│    Events    │ ────┐
│   (0 rows)   │     │ One-to-Many
└──────────────┘     │
                     ▼
              ┌──────────────┐
              │   Bookings   │
              │   (0 rows)   │
              └──────────────┘
              Each booking references
              an event via event_id
```

---

## Resource Architecture Pattern

All 7 resources follow this modular structure:

```
app/Filament/Resources/[Name]/
│
├── [Name]Resource.php           ← Main configuration
│   ├── $model                    (Model reference)
│   ├── $navigationIcon           (Sidebar icon)
│   ├── $navigationLabel          (Display name)
│   ├── $navigationGroup          (Content/Customer Management)
│   ├── $navigationSort           (Order in sidebar)
│   ├── form()                    (References Schema)
│   ├── table()                   (References Table)
│   └── getPages()                (List, Create, Edit routes)
│
├── Schemas/
│   └── [Name]Form.php           ← Form builder
│       └── configure()
│           └── Section components
│               ├── Field definitions
│               ├── Helper text
│               ├── Validation rules
│               └── Default values
│
├── Tables/
│   └── [Name]sTable.php         ← Table configuration
│       └── configure()
│           ├── Columns (with badges, icons)
│           ├── Filters (SelectFilter)
│           ├── Sorting (defaultSort)
│           └── Actions (Edit, Delete)
│
└── Pages/
    ├── List[Name]s.php          ← List page
    ├── Create[Name].php         ← Create page
    └── Edit[Name].php           ← Edit page
```

---

## Form Pattern: Section-Based Organization

All forms use sections for logical grouping:

```
Example: TeamMemberForm

Section: Personal Information
├── TextInput: name
├── TextInput: role
├── TextInput: role_second_line
├── FileUpload: image
└── TextInput: display_order

Section: Biography (collapsible)
├── TextInput: bio_title
└── Textarea: bio_text

Section: Layout Settings
├── Select: position (left/right)
├── TextInput: image_class
└── Select: status (active/inactive)
```

**Benefits:**
- Clear visual separation
- Reduced cognitive load
- Collapsible sections for optional fields
- Better mobile experience

---

## Table Pattern: Badge-Styled Columns

All tables use color-coded badges for status visualization:

```
Example: BookingsTable

Columns:
├── event.title         (searchable, sortable)
├── company_name        (searchable, sortable)
├── email               (copyable)
├── phone               (copyable)
├── num_musicians       (numeric, suffix)
├── total_price         (money: EUR)
└── status              (badge with color)
    ├── pending         → yellow
    ├── confirmed       → blue
    ├── paid            → green
    └── cancelled       → red

Filters:
├── SelectFilter: status
└── SelectFilter: event_id (relationship)

Default Sort: created_at DESC
```

---

## Data Flow Diagrams

### Content Management Flow

```
User visits homepage
        │
        ▼
TeamSection.php component
        │
        ├─ Calls: TeamMember::active()->orderBy('display_order')
        │
        ▼
Database query
        │
        ├─ Returns: 2 team members
        │
        ▼
Transform snake_case → camelCase
        │
        ├─ role_second_line → roleSecondLine
        ├─ image_class      → imageClass
        ├─ bio_title        → bioTitle
        │
        ▼
Blade template renders
        │
        └─ Displays team members on homepage
```

### Customer Management Flow (Future)

```
User submits contact form (frontend)
        │
        ▼
POST /api/contact (Laravel route)
        │
        ▼
ContactSubmission::create(...)
        │
        ▼
Database insert
        │
        ├─ status: "new"
        │
        ▼
Admin receives notification (optional)
        │
        ▼
Admin reviews in Filament panel
        │
        ├─ Changes status: "new" → "contacted"
        │
        ▼
Admin responds to inquiry
        │
        ├─ Changes status: "contacted" → "converted"
        │
        └─ Booking created if applicable
```

---

## Security Architecture

### Authentication

```
Public Site (No Auth Required)
├── Homepage (/)
├── Services page
├── Team page
└── FAQ page

Admin Panel (Auth Required)
├── /admin/login           ← Login form
├── /admin/*               ← Protected by middleware
└── Filament Guard         ← Handles authentication
```

### File Upload Security

```
Storage Structure:
storage/app/public/
└── team-members/
    └── [uploaded images]
         │
         └─ Symlinked to: public/storage/team-members/
                          │
                          └─ Publicly accessible
```

**Security Measures:**
- File validation in FileUpload component
- Image-only uploads enforced
- Directory isolation (team-members separate)
- Symlink prevents direct storage access
- Laravel's secure file handling

---

## Performance Optimizations

### Database

```
Eloquent Scopes:
├── Service::active()       → WHERE status = 'active'
├── TeamMember::active()    → WHERE status = 'active'
├── Faq::active()          → WHERE status = 'active'
└── Page::published()      → WHERE status = 'published'

Query Optimization:
├── orderBy('display_order') → Indexed column
├── Eager loading for relationships (Event → Booking)
└── No N+1 query problems
```

### Frontend

```
Blade Components:
├── TeamSection (cached)
├── Faq (cached)
└── Service cards (cached)

Asset Optimization:
├── Tailwind CSS (purged, minified)
├── Alpine.js (lightweight)
└── No unnecessary JavaScript
```

---

## Type Safety

All resources use strict PHP type declarations:

```php
// Parent class requirement
protected static UnitEnum|string|null $navigationGroup;

// NOT accepted:
protected static ?string $navigationGroup;  // ❌ Type mismatch
```

**Enforced Types:**
- Property declarations match parent class exactly
- Models use type hints for relationships
- Form fields have validation rules
- Database migrations define types strictly

---

## Testing Strategy

### CRUD Testing (100% Coverage)

```
For each resource (7 total):
├── ✓ Create: New record creation
├── ✓ Read:   Query and display
├── ✓ Update: Field modifications
└── ✓ Delete: Record removal

Validation Testing:
├── ✓ Required fields enforced
├── ✓ Email format validation
├── ✓ Unique constraints (slug)
└── ✓ Enum validation (status, type)

Relationship Testing:
└── ✓ Event → Booking foreign key constraint
```

---

## Deployment Architecture

```
Development:
├── Local: Laravel serve (port 8001)
├── Database: SQLite (database.sqlite)
└── Storage: Local filesystem

Production (Recommended):
├── Web Server: Nginx + PHP-FPM
├── Database: MySQL 8.0+
├── Storage: Local filesystem or S3
├── Cache: Redis
├── Queue: Redis (optional)
└── SSL: Let's Encrypt
```

---

## API Endpoints (Future)

Currently admin-only, but ready for API expansion:

```
Potential Public API:
POST   /api/contact           → Create ContactSubmission
GET    /api/services          → List active services
GET    /api/team              → List active team members
GET    /api/faqs              → List active FAQs
GET    /api/events            → List published events
POST   /api/bookings          → Create booking

Admin API (Filament):
CRUD   /admin/services/*
CRUD   /admin/team-members/*
CRUD   /admin/faqs/*
CRUD   /admin/pages/*
CRUD   /admin/contact-submissions/*
CRUD   /admin/events/*
CRUD   /admin/bookings/*
```

---

## Technology Stack

```
Backend:
├── Laravel 12.46.0         (Framework)
├── Filament 4.5.1          (Admin Panel)
├── Livewire 3              (Dynamic Components)
└── PHP 8.5.1               (Language)

Frontend:
├── Alpine.js               (Minimal JS Framework)
├── Tailwind CSS 4          (Utility CSS)
└── Blade Templates         (Server-side Rendering)

Database:
├── SQLite (Development)
└── MySQL 8.0+ (Production)

Development:
├── Composer 2.9.3          (PHP Dependencies)
├── Node.js 20.x            (Frontend Build)
└── Vite                    (Asset Bundler)
```

---

## Migration Path Summary

```
BEFORE (Next.js):
├── Hardcoded content in React components
├── No admin panel
├── Requires developer for content changes
└── No customer management

AFTER (TALL Stack):
├── Database-driven content
├── Filament admin panel (2 groups, 7 resources)
├── Non-technical content editors
└── Customer management system ready
```

---

## Key Files Reference

```
Configuration:
├── .env                            (Environment variables)
├── config/database.php             (Database config)
└── config/filament.php             (Admin panel config)

Documentation:
├── CLAUDE.md                       (Project rules)
├── DEPLOYMENT.md                   (Production deployment)
├── ADMIN_GUIDE.md                  (Content editor guide)
├── MIGRATION_SUMMARY.md            (Migration overview)
└── SYSTEM_ARCHITECTURE.md          (This file)

Models:
├── app/Models/Service.php
├── app/Models/TeamMember.php
├── app/Models/Faq.php
├── app/Models/Page.php
├── app/Models/ContactSubmission.php
├── app/Models/Event.php
└── app/Models/Booking.php

View Components:
├── app/View/Components/TeamSection.php
└── app/View/Components/Faq.php
```

---

## Future Enhancement Opportunities

### Phase 1: Contact Form Integration
```
├── Create frontend contact form
├── POST to /api/contact endpoint
├── Store in ContactSubmission table
├── Email notification to admin
└── Auto-response to customer
```

### Phase 2: Event Booking System
```
├── Public event listing page
├── Event detail pages
├── Booking request form
├── Store in Bookings table
├── Email confirmation
└── Admin booking management
```

### Phase 3: Advanced Features
```
├── Testimonials section
├── Photo gallery
├── Service packages (multi-service bookings)
├── Calendar integration
└── Invoice generation
```

---

## Monitoring & Maintenance

### Health Checks
```
Database:
├── Connection status
├── Query performance
└── Storage space

Application:
├── Error logs (storage/logs/)
├── Queue status (if enabled)
└── Cache performance

Admin Panel:
├── Login success rate
├── Resource usage
└── User activity logs
```

### Backup Strategy
```
Daily:
├── Database backup (mysqldump)
└── Uploaded files (rsync)

Weekly:
├── Full application backup
└── Configuration backup

Monthly:
├── Offsite backup copy
└── Restore test verification
```

---

## Support & Resources

**Documentation:**
- DEPLOYMENT.md - Production deployment guide
- ADMIN_GUIDE.md - Content editor training
- MIGRATION_SUMMARY.md - Migration overview

**External Resources:**
- Laravel Docs: https://laravel.com/docs/12.x
- Filament Docs: https://filamentphp.com/docs/4.x
- Livewire Docs: https://livewire.laravel.com/docs/3.x

**Repository:**
- GitHub: github.com/NickHeymann/musikfuerfirmen

---

**Document Version:** 1.0  
**Last Updated:** 2026-01-15  
**System Status:** Production Ready ✅
