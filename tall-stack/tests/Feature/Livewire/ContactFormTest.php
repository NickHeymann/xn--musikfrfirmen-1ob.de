<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ContactForm;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_submit_contact_form(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@test.com')
            ->set('phone', '+49 123 456789')
            ->set('company', 'Test Corp')
            ->set('inquiry_type', 'booking')
            ->set('message', 'I need a band for our corporate event')
            ->call('submit')
            ->assertHasNoErrors();

        // Verify contact submission was created
        $this->assertDatabaseCount('contact_submissions', 1);

        $submission = ContactSubmission::first();
        $this->assertEquals('John Doe', $submission->name);
        $this->assertEquals('john@test.com', $submission->email);
        $this->assertEquals('booking', $submission->inquiry_type);
        $this->assertEquals('I need a band for our corporate event', $submission->message);
    }

    #[Test]
    public function it_validates_required_fields(): void
    {
        Livewire::test(ContactForm::class)
            ->set('inquiry_type', '') // Clear default value to test validation
            ->call('submit')
            ->assertHasErrors([
                'name',
                'email',
                'message',
            ]);

        // No submission should be created
        $this->assertDatabaseCount('contact_submissions', 0);
    }

    #[Test]
    public function it_validates_email_format(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'invalid-email')
            ->set('inquiry_type', 'general')
            ->set('message', 'Test message')
            ->call('submit')
            ->assertHasErrors(['email']);

        $this->assertDatabaseCount('contact_submissions', 0);
    }

    #[Test]
    public function it_validates_inquiry_type_is_valid(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@test.com')
            ->set('inquiry_type', 'invalid_type')
            ->set('message', 'Test message')
            ->call('submit')
            ->assertHasErrors(['inquiry_type']);

        $this->assertDatabaseCount('contact_submissions', 0);
    }

    #[Test]
    public function it_accepts_all_valid_inquiry_types(): void
    {
        $validTypes = ['general', 'booking', 'partnership', 'other'];

        foreach ($validTypes as $type) {
            ContactSubmission::truncate(); // Clear previous submissions

            Livewire::test(ContactForm::class)
                ->set('name', 'John Doe')
                ->set('email', 'john@test.com')
                ->set('inquiry_type', $type)
                ->set('message', 'Test message')
                ->call('submit')
                ->assertHasNoErrors();

            $this->assertDatabaseHas('contact_submissions', [
                'inquiry_type' => $type,
            ]);
        }
    }

    #[Test]
    public function it_allows_optional_phone_and_company_fields(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@test.com')
            ->set('inquiry_type', 'general')
            ->set('message', 'Test message')
            ->call('submit')
            ->assertHasNoErrors();

        $submission = ContactSubmission::first();
        // Livewire sets empty fields to empty strings, not null
        $this->assertTrue(empty($submission->phone));
        $this->assertTrue(empty($submission->company));
    }

    #[Test]
    public function it_resets_form_fields_after_successful_submission(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@test.com')
            ->set('phone', '+49 123 456789')
            ->set('company', 'Test Corp')
            ->set('inquiry_type', 'booking')
            ->set('message', 'Test message')
            ->call('submit')
            ->assertSet('name', '')
            ->assertSet('email', '')
            ->assertSet('phone', '')
            ->assertSet('company', '')
            ->assertSet('inquiry_type', 'general') // Reset to default
            ->assertSet('message', '');
    }

    #[Test]
    public function it_creates_contact_submission_with_new_status_by_default(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@test.com')
            ->set('inquiry_type', 'general')
            ->set('message', 'Test message')
            ->call('submit');

        $this->assertDatabaseHas('contact_submissions', [
            'email' => 'john@test.com',
            'status' => 'new',
        ]);
    }
}
