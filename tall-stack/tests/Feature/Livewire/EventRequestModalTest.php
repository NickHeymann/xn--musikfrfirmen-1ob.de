<?php

namespace Tests\Feature\Livewire;

use App\Livewire\EventRequestModal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventRequestModalTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_the_component(): void
    {
        Livewire::test(EventRequestModal::class)
            ->assertStatus(200);
    }

    #[Test]
    public function it_starts_on_step_1(): void
    {
        Livewire::test(EventRequestModal::class)
            ->assertSet('step', 1)
            ->assertSet('showModal', false);
    }

    #[Test]
    public function it_can_open_modal(): void
    {
        Livewire::test(EventRequestModal::class)
            ->call('openModal')
            ->assertSet('showModal', true)
            ->assertDispatched('modal-opened');
    }

    #[Test]
    public function it_can_close_modal(): void
    {
        Livewire::test(EventRequestModal::class)
            ->call('openModal')
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertSet('step', 1) // Should reset to step 1
            ->assertDispatched('modal-closed');
    }

    #[Test]
    public function it_validates_step_1_fields(): void
    {
        Livewire::test(EventRequestModal::class)
            ->call('nextStep')
            ->assertHasErrors(['date', 'city', 'guests'])
            ->assertSet('step', 1); // Should stay on step 1
    }

    #[Test]
    public function it_advances_to_step_2_with_valid_step_1_data(): void
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->assertHasNoErrors()
            ->assertSet('step', 2);
    }

    #[Test]
    public function it_validates_package_on_step_2(): void
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->assertSet('step', 2)
            ->call('nextStep')
            ->assertHasErrors(['package'])
            ->assertSet('step', 2);
    }

    #[Test]
    public function it_advances_to_step_3_with_valid_package(): void
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->assertHasNoErrors()
            ->assertSet('step', 3);
    }

    #[Test]
    public function it_can_navigate_back_to_previous_steps(): void
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->assertSet('step', 2)
            ->call('prevStep')
            ->assertSet('step', 1);
    }

    #[Test]
    public function it_validates_contact_details_on_submit(): void
    {
        // Note: phone is optional, so only firstname, company, email, and privacy are required
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->call('submit')
            ->assertHasErrors(['firstname', 'company', 'email', 'privacy']);
    }

    #[Test]
    public function it_can_submit_with_valid_data(): void
    {
        // Without BREVO_API_KEY configured, submission still succeeds (graceful degradation)
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->set('firstname', 'Max')
            ->set('lastname', 'Mustermann')
            ->set('company', 'Test GmbH')
            ->set('email', 'max@test.de')
            ->set('phone', '+49 123 456789')
            ->set('privacy', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitStatus', 'success');
    }

    #[Test]
    public function it_validates_email_format(): void
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->set('firstname', 'Max')
            ->set('company', 'Test GmbH')
            ->set('email', 'invalid-email')
            ->set('privacy', true)
            ->call('submit')
            ->assertHasErrors(['email']);
    }

    #[Test]
    public function it_requires_privacy_acceptance(): void
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->set('firstname', 'Max')
            ->set('company', 'Test GmbH')
            ->set('email', 'max@test.de')
            ->set('privacy', false)
            ->call('submit')
            ->assertHasErrors(['privacy']);
    }

    #[Test]
    public function it_allows_optional_fields(): void
    {
        // Test that phone, message, lastname, time, and budget are optional
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            // time, budget are optional
            ->call('nextStep')
            ->set('package', 'dj')
            ->call('nextStep')
            ->set('firstname', 'Max')
            // lastname is optional
            ->set('company', 'Test GmbH')
            ->set('email', 'max@test.de')
            // phone is optional
            // message is optional
            ->set('privacy', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitStatus', 'success');
    }

    #[Test]
    public function it_allows_phone_to_be_optional(): void
    {
        // Verify that phone field is truly optional (can be empty)
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->set('firstname', 'Max')
            ->set('company', 'Test GmbH')
            ->set('email', 'max@test.de')
            ->set('phone', '') // Explicitly empty
            ->set('privacy', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitStatus', 'success');
    }

    #[Test]
    public function it_has_storage_consent_property(): void
    {
        // Verify the storageConsent property exists for GDPR-compliant localStorage
        Livewire::test(EventRequestModal::class)
            ->assertSet('storageConsent', false);
    }

    #[Test]
    public function it_dispatches_clear_storage_event_on_successful_submit(): void
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->set('firstname', 'Max')
            ->set('company', 'Test GmbH')
            ->set('email', 'max@test.de')
            ->set('privacy', true)
            ->call('submit')
            ->assertDispatched('clear-calculator-storage');
    }

    #[Test]
    public function it_has_correct_package_options(): void
    {
        $component = Livewire::test(EventRequestModal::class);

        $this->assertEquals([
            ['value' => 'dj', 'label' => 'Nur DJ'],
            ['value' => 'band', 'label' => 'Full Band'],
            ['value' => 'band_dj', 'label' => 'Full Band + DJ'],
        ], $component->get('packageOptions'));
    }

    #[Test]
    public function it_has_correct_guest_options(): void
    {
        $component = Livewire::test(EventRequestModal::class);

        $this->assertEquals([
            ['value' => 'lt100', 'label' => 'Unter 100'],
            ['value' => '100-300', 'label' => '100 - 300'],
            ['value' => '300-500', 'label' => '300 - 500'],
            ['value' => 'gt500', 'label' => '>500'],
        ], $component->get('guestOptions'));
    }
}
