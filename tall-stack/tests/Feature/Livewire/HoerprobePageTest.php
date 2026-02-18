<?php

namespace Tests\Feature\Livewire;

use App\Livewire\HoerprobePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HoerprobePageTest extends TestCase
{
    use RefreshDatabase;

    // ─── Route & HTTP ───────────────────────────────────────────────────────

    #[Test]
    public function hoerprobe_route_returns_200(): void
    {
        $this->get('/hoerprobe')->assertStatus(200);
    }

    #[Test]
    public function hoerprobe_route_is_named_correctly(): void
    {
        $this->assertEquals('/hoerprobe', route('hoerprobe', absolute: false));
    }

    #[Test]
    public function hoerprobe_page_uses_standalone_layout(): void
    {
        $response = $this->get('/hoerprobe');

        // Standalone layout: no main nav, no footer nav
        $response->assertDontSee('nav-link', false);

        // Has correct SEO title
        $response->assertSee('Hörprobe', false);
    }

    #[Test]
    public function hoerprobe_page_contains_correct_meta_title(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('<title>Hörprobe', false);
    }

    #[Test]
    public function hoerprobe_page_contains_meta_description(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('meta name="description"', false);
        $response->assertSee('Hörprobe', false);
    }

    // ─── Livewire Component ─────────────────────────────────────────────────

    #[Test]
    public function livewire_component_renders_successfully(): void
    {
        Livewire::test(HoerprobePage::class)
            ->assertStatus(200);
    }

    #[Test]
    public function component_is_mounted_on_route(): void
    {
        $this->get('/hoerprobe')
            ->assertSeeLivewire(HoerprobePage::class);
    }

    // ─── Content & UI ───────────────────────────────────────────────────────

    #[Test]
    public function page_shows_brand_name_in_header(): void
    {
        Livewire::test(HoerprobePage::class)
            ->assertSee('musikfürfirmen.de');
    }

    #[Test]
    public function page_shows_hero_headline(): void
    {
        Livewire::test(HoerprobePage::class)
            ->assertSee('Hör mal rein.');
    }

    #[Test]
    public function page_shows_audio_player(): void
    {
        Livewire::test(HoerprobePage::class)
            ->assertSee('hoerprobe.mp3', false);
    }

    #[Test]
    public function audio_element_has_preload_metadata(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('preload="metadata"', false);
    }

    #[Test]
    public function page_shows_waveform_label(): void
    {
        Livewire::test(HoerprobePage::class)
            ->assertSee('Zusammenschnitt aus echten Events');
    }

    #[Test]
    public function page_shows_company_description(): void
    {
        Livewire::test(HoerprobePage::class)
            ->assertSee('Musik ist auf den meisten Firmenevents');
    }

    #[Test]
    public function page_shows_link_to_main_site(): void
    {
        Livewire::test(HoerprobePage::class)
            ->assertSee('Mehr über uns erfahren');
    }

    #[Test]
    public function page_shows_cta_buttons(): void
    {
        Livewire::test(HoerprobePage::class)
            ->assertSee('Jetzt Angebot einholen')
            ->assertSee('Kostenloses Erstgespräch');
    }

    #[Test]
    public function cta_buttons_dispatch_correct_livewire_events(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('openMFFCalculator', false);
        $response->assertSee('openBookingModal', false);
    }

    #[Test]
    public function page_shows_footer_with_legal_links(): void
    {
        Livewire::test(HoerprobePage::class)
            ->assertSee('Impressum')
            ->assertSee('Datenschutz');
    }

    #[Test]
    public function page_shows_cta_section_headline(): void
    {
        $this->get('/hoerprobe')
            ->assertSee('Bereit für unvergessliche Musik?', false);
    }

    // ─── Modals eingebunden ─────────────────────────────────────────────────

    #[Test]
    public function event_request_modal_is_present_on_page(): void
    {
        $this->get('/hoerprobe')
            ->assertSeeLivewire('event-request-modal');
    }

    #[Test]
    public function booking_calendar_modal_is_present_on_page(): void
    {
        $this->get('/hoerprobe')
            ->assertSeeLivewire('booking-calendar-modal');
    }

    // ─── Layout ─────────────────────────────────────────────────────────────

    #[Test]
    public function page_loads_poppins_font(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('Poppins', false);
    }

    #[Test]
    public function page_loads_vite_assets(): void
    {
        $response = $this->get('/hoerprobe');

        // In test env Vite outputs hashed filenames (app-XXXXXXXX.css)
        $response->assertSee('/build/assets/app', false);
    }

    #[Test]
    public function page_includes_livewire_scripts(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('livewire', false);
    }

    #[Test]
    public function page_has_light_background(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('bg-white', false);
    }

    #[Test]
    public function page_has_correct_lang_attribute(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('lang="de"', false);
    }

    // ─── Alpine.js Audio Player ──────────────────────────────────────────────

    #[Test]
    public function audio_player_has_alpine_data(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('x-data', false);
        $response->assertSee('playing', false);
        $response->assertSee('currentTime', false);
        $response->assertSee('duration', false);
    }

    #[Test]
    public function audio_player_has_toggle_function(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('toggle()', false);
    }

    #[Test]
    public function audio_player_has_seek_function(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('seek(', false);
    }

    #[Test]
    public function audio_player_has_format_time_function(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('formatTime(', false);
    }

    #[Test]
    public function audio_player_displays_time_placeholders(): void
    {
        $response = $this->get('/hoerprobe');

        // Initial time display placeholders
        $response->assertSee('0:00', false);
        $response->assertSee('--:--', false);
    }

    // ─── Edge Cases ──────────────────────────────────────────────────────────

    #[Test]
    public function page_accessible_without_authentication(): void
    {
        // No auth required – public marketing page
        $this->get('/hoerprobe')->assertStatus(200);
    }

    #[Test]
    public function page_does_not_require_database_records(): void
    {
        // Unlike homepage, hoerprobe needs no DB data to render
        $this->get('/hoerprobe')->assertStatus(200);
    }

    #[Test]
    public function page_renders_with_missing_audio_file(): void
    {
        // Audio file may not exist yet – page should still render (404 handled by browser)
        $response = $this->get('/hoerprobe');

        $response->assertStatus(200);
        $response->assertSee('hoerprobe.mp3', false);
    }

    #[Test]
    public function page_does_not_include_main_site_header_component(): void
    {
        $response = $this->get('/hoerprobe');

        // Standalone layout – no <x-header /> with full navigation
        $response->assertDontSee('id="main-nav"', false);
    }

    #[Test]
    public function page_does_not_include_main_site_footer_component(): void
    {
        $response = $this->get('/hoerprobe');

        // Standalone layout – no <x-footer /> with full site links
        $response->assertDontSee('id="main-footer"', false);
    }

    #[Test]
    public function page_renders_founder_images(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('images/team/nick.png', false);
        $response->assertSee('images/team/jonas.png', false);
    }

    #[Test]
    public function copyright_year_is_current(): void
    {
        $response = $this->get('/hoerprobe');

        // Footer uses &copy; entity which renders as ©
        $response->assertSee('&copy; '.date('Y'), false);
    }

    #[Test]
    public function hoerprobe_slug_has_no_umlaut(): void
    {
        // Route must be /hoerprobe not /hörprobe
        $this->get('/hoerprobe')->assertStatus(200);
        $this->get('/hörprobe')->assertStatus(404);
    }

    #[Test]
    public function page_has_scroll_smooth_on_html(): void
    {
        $response = $this->get('/hoerprobe');

        $response->assertSee('scroll-smooth', false);
    }
}
