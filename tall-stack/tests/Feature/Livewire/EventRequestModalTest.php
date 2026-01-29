<?php

namespace Tests\Feature\Livewire;

use App\Livewire\EventRequestModal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EventRequestModalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_component()
    {
        Livewire::test(EventRequestModal::class)
            ->assertStatus(200);
    }

    /** @test */
    public function it_starts_on_step_1()
    {
        Livewire::test(EventRequestModal::class)
            ->assertSet('step', 1)
            ->assertSet('showModal', false);
    }

    /** @test */
    public function it_can_open_modal()
    {
        Livewire::test(EventRequestModal::class)
            ->call('openModal')
            ->assertSet('showModal', true)
            ->assertDispatched('modal-opened');
    }

    /** @test */
    public function it_can_close_modal()
    {
        Livewire::test(EventRequestModal::class)
            ->call('openModal')
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertSet('step', 1) // Should reset to step 1
            ->assertDispatched('modal-closed');
    }

    /** @test */
    public function it_validates_step_1_fields()
    {
        Livewire::test(EventRequestModal::class)
            ->call('nextStep')
            ->assertHasErrors(['date', 'city', 'guests'])
            ->assertSet('step', 1); // Should stay on step 1
    }

    /** @test */
    public function it_advances_to_step_2_with_valid_step_1_data()
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->assertHasNoErrors()
            ->assertSet('step', 2);
    }

    /** @test */
    public function it_validates_package_on_step_2()
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

    /** @test */
    public function it_advances_to_step_3_with_valid_package()
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

    /** @test */
    public function it_can_navigate_back_to_previous_steps()
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

    /** @test */
    public function it_validates_contact_details_on_submit()
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->call('submit')
            ->assertHasErrors(['name', 'email', 'phone', 'privacy']);
    }

    /** @test */
    public function it_can_submit_with_valid_data()
    {
        // Without BREVO_API_KEY configured, submission still succeeds (graceful degradation)
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->set('name', 'Max Mustermann')
            ->set('email', 'max@test.de')
            ->set('phone', '+49 123 456789')
            ->set('privacy', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitStatus', 'success');
    }

    /** @test */
    public function it_validates_email_format()
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->set('name', 'Max Mustermann')
            ->set('email', 'invalid-email')
            ->set('phone', '+49 123 456789')
            ->set('privacy', true)
            ->call('submit')
            ->assertHasErrors(['email']);
    }

    /** @test */
    public function it_requires_privacy_acceptance()
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->set('name', 'Max Mustermann')
            ->set('email', 'max@test.de')
            ->set('phone', '+49 123 456789')
            ->set('privacy', false)
            ->call('submit')
            ->assertHasErrors(['privacy']);
    }

    /** @test */
    public function it_allows_optional_fields()
    {
        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            // time, budget are optional
            ->call('nextStep')
            ->set('package', 'dj')
            ->call('nextStep')
            ->set('name', 'Max Mustermann')
            ->set('email', 'max@test.de')
            ->set('phone', '+49 123 456789')
            // company, message are optional
            ->set('privacy', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitStatus', 'success');
    }

    /** @test */
    public function it_has_correct_package_options()
    {
        $component = Livewire::test(EventRequestModal::class);

        $this->assertEquals([
            ['value' => 'dj', 'label' => 'Nur DJ'],
            ['value' => 'band', 'label' => 'Full Band'],
            ['value' => 'band_dj', 'label' => 'Full Band + DJ'],
        ], $component->get('packageOptions'));
    }

    /** @test */
    public function it_has_correct_guest_options()
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
