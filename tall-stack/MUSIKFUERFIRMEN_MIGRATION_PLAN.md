# musikfuerfirmen.de - TALL Stack Migration Plan

> **Status**: Ready to implement  
> **Created**: 2026-01-10  
> **Based on**: kathrin-coaching migration patterns  
> **Stack**: Laravel 12 + Filament 4 + Livewire 3 + Alpine.js + Tailwind CSS

---

## Executive Summary

**Goal**: Migrate musikfuerfirmen.de from Next.js to TALL Stack using proven patterns from kathrin-coaching project.

**Current State**:
- ✅ Next.js frontend deployed (Apache, production)
- ✅ TALL Stack backend built locally (90% complete)
- ✅ Docker + GitLab CI/CD configured
- ❌ NOT deployed to Hetzner (91.99.177.238)
- ❌ Missing: Service, TeamMember, Page, FAQ models/tables
- ❌ Missing: NextJsContentSeeder to import TypeScript data

**Migration Strategy**: Sequential implementation (not parallel) for compound learning, same as kathrin-coaching.

**Estimated Timeline**: 4-6 weeks (simpler than kathrin-coaching due to smaller content scope)

---

## Content Inventory

### Existing Next.js Content

| Content Type | Source File | Count | Status |
|--------------|-------------|-------|--------|
| Service Blocks | `src/data/services.ts` | 3 | Needs migration |
| Team Members | `src/data/team.ts` | 2 | Needs migration |
| FAQ Items | `src/data/faq.ts` | 7 | Needs migration |
| Static Pages | `src/app/**/page.tsx` | 5 | Needs migration |
| Site Config | `src/config/site.ts` | 1 | Needs migration |

**Static Pages**:
1. Homepage (`src/app/page.tsx`)
2. Über Uns (`src/app/ueber-uns/page.tsx`)
3. Impressum (`src/app/impressum/page.tsx`)
4. Datenschutz (`src/app/datenschutz/page.tsx`)
5. Referenz (`src/app/referenz/page.tsx`)

### Existing TALL Stack Components

**Already Built** ✅:
- Models: Event, Booking, Musician, User, ContactSubmission
- Livewire: Homepage, EventsIndex, EventShow, ContactForm
- Filament: EventResource, BookingResource, ContactSubmissionResource
- Migrations: events, bookings, musicians, contact_submissions tables

**Missing** ❌:
- Models: Service, TeamMember, Page, FAQ
- Livewire: ServicesSection, TeamSection, FAQSection, PageShow
- Filament: ServiceResource, TeamMemberResource, PageResource, FAQResource
- Migrations: services, team_members, pages, faqs tables
- Seeder: NextJsContentSeeder (to import TypeScript data)

---

## Database Schema Design

Following kathrin-coaching patterns with musikfuerfirmen-specific adaptations.

### 1. Services Table

```php
// database/migrations/2026_01_10_create_services_table.php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->string('title');                    // "60 Sekunden", "24 Stunden", "Rundum-Service"
    $table->string('slug')->unique();           // "60-sekunden", "24-stunden", "rundum-service"
    $table->text('text')->nullable();           // "Schickt uns eure Anfrage innerhalb von"
    $table->string('highlight')->nullable();    // "60 Sekunden" (highlighted text)
    $table->text('description')->nullable();    // " über unser Formular. Schnell..."
    $table->string('icon')->nullable();         // Icon name or path (future)
    $table->integer('display_order')->default(0); // For ordering: 1, 2, 3
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
});
```

**Rationale**: Services in musikfuerfirmen are **process steps**, not traditional services like in kathrin-coaching. Structure reflects the current TypeScript data model.

### 2. Team Members Table

```php
// database/migrations/2026_01_10_create_team_members_table.php
Schema::create('team_members', function (Blueprint $table) {
    $table->id();
    $table->string('name');                     // "Jonas Glamann", "Nick Heymann"
    $table->string('role');                     // "Direkter Kontakt vor Ort"
    $table->string('role_second_line')->nullable(); // "Koordination von Band + Technik, Gitarrist"
    $table->string('image')->nullable();        // "/images/team/jonas.png"
    $table->string('bio_title')->nullable();    // "Der Kreative", "Der Macher"
    $table->text('bio_text')->nullable();       // Full bio
    $table->enum('position', ['left', 'right', 'center'])->default('center'); // Layout position
    $table->integer('display_order')->default(0); // For ordering
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
});
```

### 3. Pages Table

```php
// database/migrations/2026_01_10_create_pages_table.php
Schema::create('pages', function (Blueprint $table) {
    $table->id();
    $table->string('title');                    // "Impressum", "Datenschutz", "Über Uns"
    $table->string('slug')->unique();           // "impressum", "datenschutz", "ueber-uns"
    $table->longText('content');                // Full page HTML content
    $table->string('meta_description')->nullable();
    $table->json('meta_keywords')->nullable();
    $table->string('template')->default('default'); // 'default', 'legal', 'about'
    $table->enum('status', ['published', 'draft'])->default('published');
    $table->boolean('show_in_nav')->default(false);
    $table->integer('display_order')->default(0);
    $table->timestamps();
});
```

### 4. FAQs Table

```php
// database/migrations/2026_01_10_create_faqs_table.php
Schema::create('faqs', function (Blueprint $table) {
    $table->id();
    $table->text('question');                   // "Sind Anfragen verbindlich?"
    $table->text('answer');                     // "Nein, Anfragen sind komplett..."
    $table->boolean('has_link')->default(false); // CTA link in answer
    $table->string('category')->nullable();     // "Buchung", "Ablauf", "Technik" (future)
    $table->integer('display_order')->default(0);
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
});
```

---

## NextJsContentSeeder Implementation

**Pattern**: Similar to kathrin-coaching's `HtmlContentSeeder`, but adapted for TypeScript data files.

### Seeder Structure

```php
// database/seeders/NextJsContentSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NextJsContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Next.js content migration...');

        // Import in order
        $this->seedServices();
        $this->seedTeamMembers();
        $this->seedFAQs();
        $this->seedPages();

        $this->command->info('✓ Next.js content migration complete!');
    }

    private function seedServices(): void
    {
        $this->command->info('Importing services...');

        $services = [
            [
                'title' => '60 Sekunden',
                'slug' => '60-sekunden',
                'text' => 'Schickt uns eure Anfrage innerhalb von ',
                'highlight' => '60 Sekunden',
                'description' => ' über unser Formular. Schnell, einfach und unkompliziert.',
                'display_order' => 1,
                'status' => 'active',
            ],
            [
                'title' => '24 Stunden',
                'slug' => '24-stunden',
                'text' => 'Erhaltet ein kostenloses Angebot innerhalb von ',
                'highlight' => '24 Stunden',
                'description' => '. Durch das von euch ausgefüllte Formular liefern wir euch ein individuelles Angebot.',
                'display_order' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'Rundum-Service',
                'slug' => 'rundum-service',
                'text' => 'Genießt ',
                'highlight' => 'professionelle Betreuung',
                'description' => ' bis zum großen Tag! Wir sind 24/7 erreichbar. Über WhatsApp, Telefon oder E-Mail.',
                'display_order' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert(array_merge($service, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Imported ' . count($services) . ' services');
    }

    private function seedTeamMembers(): void
    {
        $this->command->info('Importing team members...');

        $teamMembers = [
            [
                'name' => 'Jonas Glamann',
                'role' => 'Direkter Kontakt vor Ort',
                'role_second_line' => 'Koordination von Band + Technik, Gitarrist',
                'image' => '/images/team/jonas.png',
                'bio_title' => 'Der Kreative',
                'bio_text' => 'Hi, ich bin Jonas. Ich bin euer direkter Kontakt vor Ort. Mit 7 Jahren habe ich angefangen Gitarre zu spielen und stehe seitdem auf der Bühne. Ich bin selbst Teil der Band und koordiniere diese, sowie alles rund um Technik. Ich halte die Abläufe zusammen.

Vor Musikfürfirmen.de habe ich selbst in vielen Coverbands gespielt. Parallel dazu habe ich als Techniker Bands wie Revolverheld und Adel Tawil auf Tour begleitet. Ich bin dadurch mit beiden Seiten von Events vertraut und weiß genau, wie ich mit allen Beteiligten kommunizieren muss.',
                'position' => 'left',
                'display_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Nick Heymann',
                'role' => 'Marketingspezialist',
                'role_second_line' => 'Ansprechpartner und Videograf',
                'image' => '/images/team/nick.png',
                'bio_title' => 'Der Macher',
                'bio_text' => 'Hi, ich bin Nick. Ich bin euer allgemeiner Ansprechpartner und kümmere ich mich um das Marketing und den visuellen Auftritt bei Musikfürfirmen.de.

Nach meinem Musikstudium habe ich mich als Videograf und Marketingberater selbständig gemacht. Meine Videos von verschiedenen Events sammelten über 100 Millionen Aufrufe auf Social Media.

Auf Wunsch begleite ich euer Event videographisch und liefere Material für interne Kommunikation oder Socials und eure Website.',
                'position' => 'right',
                'display_order' => 2,
                'status' => 'active',
            ],
        ];

        foreach ($teamMembers as $member) {
            DB::table('team_members')->insert(array_merge($member, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Imported ' . count($teamMembers) . ' team members');
    }

    private function seedFAQs(): void
    {
        $this->command->info('Importing FAQs...');

        $faqs = [
            [
                'question' => 'Sind Anfragen verbindlich?',
                'answer' => 'Nein, Anfragen sind komplett unverbindlich und werden innerhalb von 24 Stunden beantwortet. Gerne bieten wir dir auch ein kostenloses Erstgespräch an.',
                'has_link' => false,
                'display_order' => 1,
            ],
            [
                'question' => 'Wie läuft eine Anfrage bei euch ab?',
                'answer' => 'In nur drei Schritten:

1) Klick auf "Unverbindliches Angebot anfragen"
2) Fülle das Formular mit den wichtigsten Infos zu deinem Event aus
3) Drücke auf Absenden.

Innerhalb von 24 Stunden hast du dein individuelles Angebot im Postfach.',
                'has_link' => true,
                'display_order' => 2,
            ],
            [
                'question' => 'Kann ich Songwünsche nennen?',
                'answer' => 'Auf jeden Fall! Unsere Bands haben zwar ein festes Repertoire, freuen sich aber über besondere Songwünsche. Erwähne sie einfach im Erstgespräch, so bleibt genug Zeit für die Vorbereitung.',
                'has_link' => false,
                'display_order' => 3,
            ],
            [
                'question' => 'Kann man euch deutschlandweit buchen?',
                'answer' => 'Absolut! Egal wo in Deutschland dein Event stattfindet, du kannst auf uns zählen. Um Anfahrt, Logistik und Unterkunft kümmern wir uns.',
                'has_link' => false,
                'display_order' => 4,
            ],
            [
                'question' => 'Was passiert, wenn die Sängerin/Sänger krank wird?',
                'answer' => 'Keine Sorge, dafür sind wir vorbereitet! Für alle unsere Künstler:innen haben wir erfahrene Ersatzleute parat, die kurzfristig einspringen können. Sollte es dazu kommen, melden wir uns natürlich sofort bei dir.',
                'has_link' => false,
                'display_order' => 5,
            ],
            [
                'question' => 'Muss ich mich noch um irgendetwas kümmern?',
                'answer' => 'Nein! Wir nehmen dir alles ab, was mit Musik zu tun hat: von der Auswahl der passenden Künstler:innen über Equipment und Technik bis hin zu Anfahrt und Übernachtung. Du kannst dich entspannt auf dein Event freuen.',
                'has_link' => false,
                'display_order' => 6,
            ],
            [
                'question' => 'Warum sollte ich nicht alles über eine Eventagentur buchen?',
                'answer' => 'Gute Frage! Bei den meisten Eventagenturen läuft Musik eher nebenbei. Ob die Band gut ist, wird dann zur Glückssache. Wir sehen das anders: Musik prägt die Stimmung und bleibt in Erinnerung. Deshalb geben wir ihr die Aufmerksamkeit, die sie verdient.',
                'has_link' => false,
                'display_order' => 7,
            ],
        ];

        foreach ($faqs as $faq) {
            DB::table('faqs')->insert(array_merge($faq, [
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Imported ' . count($faqs) . ' FAQs');
    }

    private function seedPages(): void
    {
        $this->command->info('Importing static pages...');

        // Note: This is a placeholder. Actual content would be extracted from Next.js page.tsx files
        $pages = [
            [
                'title' => 'Impressum',
                'slug' => 'impressum',
                'content' => '<!-- Content to be extracted from src/app/impressum/page.tsx -->',
                'template' => 'legal',
                'status' => 'published',
                'show_in_nav' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'Datenschutz',
                'slug' => 'datenschutz',
                'content' => '<!-- Content to be extracted from src/app/datenschutz/page.tsx -->',
                'template' => 'legal',
                'status' => 'published',
                'show_in_nav' => true,
                'display_order' => 2,
            ],
            [
                'title' => 'Über Uns',
                'slug' => 'ueber-uns',
                'content' => '<!-- Content to be extracted from src/app/ueber-uns/page.tsx -->',
                'template' => 'about',
                'status' => 'published',
                'show_in_nav' => true,
                'display_order' => 3,
            ],
            [
                'title' => 'Referenz',
                'slug' => 'referenz',
                'content' => '<!-- Content to be extracted from src/app/referenz/page.tsx -->',
                'template' => 'default',
                'status' => 'published',
                'show_in_nav' => false,
                'display_order' => 4,
            ],
        ];

        foreach ($pages as $page) {
            DB::table('pages')->insert(array_merge($page, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Imported ' . count($pages) . ' pages (content extraction pending)');
    }
}
```

---

## Livewire Components Plan

Following kathrin-coaching patterns with musikfuerfirmen-specific adaptations.

### 1. ServicesSection Component

```php
// app/Livewire/ServicesSection.php
<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;

class ServicesSection extends Component
{
    public function render()
    {
        $services = Service::query()
            ->where('status', 'active')
            ->orderBy('display_order')
            ->get();

        return view('livewire.services-section', [
            'services' => $services,
        ]);
    }
}
```

**Blade Template**: `resources/views/livewire/services-section.blade.php`

```blade
<section id="waswirbieten" class="services-section">
    <h2>Wie wir arbeiten</h2>
    
    <div class="service-blocks">
        @foreach($services as $service)
            <div class="service-block">
                <h3>{{ $service->title }}</h3>
                <p>
                    {{ $service->text }}
                    <span class="highlight">{{ $service->highlight }}</span>
                    {{ $service->description }}
                </p>
            </div>
        @endforeach
    </div>
</section>
```

### 2. TeamSection Component

```php
// app/Livewire/TeamSection.php
<?php

namespace App\Livewire;

use App\Models\TeamMember;
use Livewire\Component;

class TeamSection extends Component
{
    public function render()
    {
        $teamMembers = TeamMember::query()
            ->where('status', 'active')
            ->orderBy('display_order')
            ->get();

        return view('livewire.team-section', [
            'teamMembers' => $teamMembers,
        ]);
    }
}
```

**Blade Template**: `resources/views/livewire/team-section.blade.php`

```blade
<section id="team" class="team-section">
    <h2>Unser Team</h2>
    
    <div class="team-grid">
        @foreach($teamMembers as $member)
            <div class="team-member team-member--{{ $member->position }}">
                <img src="{{ $member->image }}" alt="{{ $member->name }}" />
                <h3>{{ $member->name }}</h3>
                <p class="role">{{ $member->role }}</p>
                @if($member->role_second_line)
                    <p class="role-second">{{ $member->role_second_line }}</p>
                @endif
                
                <div class="bio">
                    <h4>{{ $member->bio_title }}</h4>
                    <p>{{ $member->bio_text }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
```

### 3. FAQSection Component

```php
// app/Livewire/FAQSection.php
<?php

namespace App\Livewire;

use App\Models\FAQ;
use Livewire\Component;

class FAQSection extends Component
{
    public array $openItems = [];

    public function toggle(int $id): void
    {
        if (in_array($id, $this->openItems)) {
            $this->openItems = array_filter($this->openItems, fn($item) => $item !== $id);
        } else {
            $this->openItems[] = $id;
        }
    }

    public function render()
    {
        $faqs = FAQ::query()
            ->where('status', 'active')
            ->orderBy('display_order')
            ->get();

        return view('livewire.faq-section', [
            'faqs' => $faqs,
        ]);
    }
}
```

**Blade Template**: `resources/views/livewire/faq-section.blade.php`

```blade
<section id="faq" class="faq-section">
    <h2>Häufig gestellte Fragen</h2>
    
    <div class="faq-list">
        @foreach($faqs as $faq)
            <div class="faq-item {{ in_array($faq->id, $openItems) ? 'faq-item--open' : '' }}">
                <button 
                    wire:click="toggle({{ $faq->id }})" 
                    class="faq-question"
                >
                    {{ $faq->question }}
                    <span class="faq-icon">{{ in_array($faq->id, $openItems) ? '−' : '+' }}</span>
                </button>
                
                @if(in_array($faq->id, $openItems))
                    <div class="faq-answer">
                        {!! nl2br(e($faq->answer)) !!}
                        
                        @if($faq->has_link)
                            <a href="#kontakt" class="faq-cta">Jetzt Anfrage stellen</a>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</section>
```

### 4. PageShow Component

```php
// app/Livewire/PageShow.php
<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class PageShow extends Component
{
    public Page $page;

    public function mount(string $slug)
    {
        $this->page = Page::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.page-show')
            ->layout('layouts.app', [
                'title' => $this->page->title,
                'metaDescription' => $this->page->meta_description,
            ]);
    }
}
```

---

## Filament Resources Plan

Following kathrin-coaching patterns with musikfuerfirmen-specific adaptations.

### 1. ServiceResource

```bash
php artisan make:filament-resource Service --generate
```

**Key Fields**:
- TextInput: title, highlight
- Textarea: text, description
- TextInput (numeric): display_order
- Select: status (active/inactive)

### 2. TeamMemberResource

```bash
php artisan make:filament-resource TeamMember --generate
```

**Key Fields**:
- TextInput: name, role, role_second_line, bio_title
- Textarea: bio_text
- FileUpload: image
- Select: position (left/right/center), status
- TextInput (numeric): display_order

### 3. PageResource

```bash
php artisan make:filament-resource Page --generate
```

**Key Fields**:
- TextInput: title, slug
- RichEditor: content (full WYSIWYG)
- Textarea: meta_description
- TagsInput: meta_keywords
- Select: template, status
- Toggle: show_in_nav
- TextInput (numeric): display_order

### 4. FAQResource

```bash
php artisan make:filament-resource FAQ --generate
```

**Key Fields**:
- Textarea: question, answer
- Toggle: has_link
- TextInput: category (nullable)
- TextInput (numeric): display_order
- Select: status

---

## Implementation Timeline

Following kathrin-coaching's sequential approach for compound learning.

### Week 1: Database & Models Setup
**Tasks**:
1. Create migrations (services, team_members, pages, faqs)
2. Create models (Service, TeamMember, Page, FAQ)
3. Run migrations locally
4. Test model relationships

**Deliverables**:
- ✅ 4 new migrations
- ✅ 4 new models
- ✅ Database schema ready

### Week 2: Data Migration
**Tasks**:
1. Create NextJsContentSeeder
2. Extract page content from Next.js `page.tsx` files
3. Run seeder locally
4. Verify data integrity

**Deliverables**:
- ✅ NextJsContentSeeder complete
- ✅ All data migrated to database
- ✅ 3 services, 2 team members, 7 FAQs, 5 pages

### Week 3: Livewire Components
**Tasks**:
1. Create ServicesSection component + view
2. Create TeamSection component + view
3. Create FAQSection component + view
4. Create PageShow component + view
5. Update Homepage to use new components

**Deliverables**:
- ✅ 4 new Livewire components
- ✅ 4 new Blade views
- ✅ Homepage fully migrated

### Week 4: Filament Admin
**Tasks**:
1. Generate ServiceResource
2. Generate TeamMemberResource
3. Generate PageResource
4. Generate FAQResource
5. Test CRUD operations

**Deliverables**:
- ✅ 4 new Filament resources
- ✅ Admin panel fully functional

### Week 5: Testing & Refinement
**Tasks**:
1. Write Pest tests for models
2. Write Pest tests for Livewire components
3. Test admin panel workflows
4. Fix bugs, refine UI

**Deliverables**:
- ✅ >80% test coverage
- ✅ All features tested
- ✅ UI polished

### Week 6: Deployment
**Tasks**:
1. Final local testing
2. Push to GitLab → trigger CI/CD pipeline
3. Deploy to Hetzner (91.99.177.238)
4. Verify all 5 Docker containers running
5. Test production site
6. Archive Next.js frontend

**Deliverables**:
- ✅ TALL Stack deployed to Hetzner
- ✅ musikfuerfirmen.de running on TALL Stack
- ✅ Next.js archived for 2-week rollback safety

---

## Deployment Checklist

### Pre-Deployment
- [ ] All migrations tested locally
- [ ] All seeders tested locally
- [ ] All Livewire components working
- [ ] All Filament resources working
- [ ] Pest tests passing (>80% coverage)
- [ ] `.env.production` configured
- [ ] `APP_KEY` generated

### Deployment Steps
1. Push to GitLab main branch
2. GitLab CI/CD pipeline triggers
3. Docker images built
4. Deploy to Hetzner `/opt/apps/musikfuerfirmen/`
5. Verify containers: `docker ps` (5 expected)
6. Run migrations: `docker exec musikfuerfirmen-app php artisan migrate --force`
7. Run seeders: `docker exec musikfuerfirmen-app php artisan db:seed --force`
8. Clear cache: `docker exec musikfuerfirmen-app php artisan optimize:clear`

### Post-Deployment Verification
- [ ] Homepage loads
- [ ] Services section displays
- [ ] Team section displays
- [ ] FAQ section displays
- [ ] Static pages load (impressum, datenschutz, über-uns)
- [ ] Contact form works
- [ ] Events index works
- [ ] Filament admin accessible
- [ ] Filament login works
- [ ] CRUD operations work

### Rollback Safety
- [ ] Next.js archived to `_archive/nextjs-frontend-deprecated-2026-01-10/`
- [ ] Keep Next.js for 2 weeks (until 2026-01-24)
- [ ] Monitor TALL Stack for critical issues
- [ ] If issues: revert Apache config to Next.js

---

## Success Criteria

**Migration Complete When**:
1. ✅ All Next.js content migrated to database
2. ✅ All Livewire components working
3. ✅ All Filament resources working
4. ✅ Deployed to Hetzner (91.99.177.238)
5. ✅ musikfuerfirmen.de serving from TALL Stack
6. ✅ Tests passing (>80% coverage)
7. ✅ No critical bugs
8. ✅ Performance acceptable (< 2s page load)

**KPIs**:
- Page load time: < 2 seconds (target: < 1s)
- Lighthouse score: > 90 (all metrics)
- Test coverage: > 80%
- Zero critical bugs
- Admin panel response time: < 500ms

---

## Comparison: kathrin-coaching vs musikfuerfirmen

| Aspect | kathrin-coaching | musikfuerfirmen |
|--------|------------------|-----------------|
| **Content Source** | 168 HTML files | TypeScript config files |
| **Content Volume** | High (blog, videos, quizzes, podcasts) | Low (services, team, FAQ, pages) |
| **Seeder** | HtmlContentSeeder (Symfony DomCrawler) | NextJsContentSeeder (hardcoded arrays) |
| **Migration Timeline** | 18 weeks (58% at Week 10) | 4-6 weeks (simpler) |
| **Complexity** | High (multimedia, dynamic content) | Low (static content, simple structure) |
| **Models** | 9 (BlogPost, Video, Quiz, Podcast, etc.) | 8 (Event, Booking, Service, Team, Page, FAQ, etc.) |
| **Current Status** | Week 10 (Video Library 70%) | Not started (ready to begin) |

**Key Insight**: musikfuerfirmen migration is significantly simpler due to smaller content scope and simpler data structure (TypeScript configs vs HTML parsing).

---

## Next Steps

1. **Review this plan** with stakeholders
2. **Create Week 1 tasks** in todo list
3. **Start implementation** following sequential timeline
4. **Use kathrin-coaching as reference** for any uncertainties

**Recommended Start**: Create migrations first, then models, then test locally before proceeding to seeders.

---

**Last Updated**: 2026-01-10  
**Status**: Ready to implement  
**Based on**: kathrin-coaching patterns (HtmlContentSeeder → NextJsContentSeeder)
