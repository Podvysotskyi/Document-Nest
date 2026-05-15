<?php

namespace Tests\Feature\Documents\Reminders;

use App\Enums\DocumentActivityType;
use App\Enums\DocumentStatus;
use App\Events\Documents\DocumentReminderSent;
use App\Models\Document;
use App\Models\DocumentReminder;
use App\Models\User;
use App\Notifications\DocumentExpiringSoon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendExpiryRemindersCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-03-25 07:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_command_notifies_owner_for_due_unsent_reminders_and_marks_them_sent(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'title' => 'Passport',
            'expiry_date' => '2026-04-01',
            'status' => DocumentStatus::Active,
        ]);
        $reminder = DocumentReminder::factory()
            ->for($document)
            ->for($user)
            ->dueOn('2026-03-25')
            ->create();

        $this->artisan('documents:send-expiry-reminders')->assertExitCode(0);

        Notification::assertSentTo($user, DocumentExpiringSoon::class, function (DocumentExpiringSoon $notification) use ($document): bool {
            return $notification->document->is($document);
        });

        $this->assertNotNull($reminder->fresh()->sent_at);
    }

    public function test_command_skips_reminders_with_future_dates(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-05-01',
        ]);
        $reminder = DocumentReminder::factory()
            ->for($document)
            ->for($user)
            ->dueOn('2026-04-24')
            ->create();

        $this->artisan('documents:send-expiry-reminders')->assertExitCode(0);

        Notification::assertNothingSent();
        $this->assertNull($reminder->fresh()->sent_at);
    }

    public function test_command_skips_archived_documents(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'status' => DocumentStatus::Archived,
            'archived_at' => now(),
            'expiry_date' => '2026-04-01',
        ]);
        $reminder = DocumentReminder::factory()
            ->for($document)
            ->for($user)
            ->dueOn('2026-03-25')
            ->create();

        $this->artisan('documents:send-expiry-reminders')->assertExitCode(0);

        Notification::assertNothingSent();
        $this->assertNull($reminder->fresh()->sent_at);
    }

    public function test_command_does_not_resend_already_sent_reminders(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
        ]);
        DocumentReminder::factory()
            ->for($document)
            ->for($user)
            ->sent()
            ->dueOn('2026-03-25')
            ->create();

        $this->artisan('documents:send-expiry-reminders')->assertExitCode(0);

        Notification::assertNothingSent();
    }

    public function test_command_does_not_send_reminder_for_another_users_document(): void
    {
        Notification::fake();

        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $document = Document::factory()->for($owner)->create([
            'expiry_date' => '2026-04-01',
        ]);
        $reminder = DocumentReminder::factory()
            ->for($document)
            // Reminder row points at a different user — simulates corrupted data we must defend against.
            ->for($intruder)
            ->dueOn('2026-03-25')
            ->create();

        $this->artisan('documents:send-expiry-reminders')->assertExitCode(0);

        Notification::assertNothingSent();
        $this->assertNull($reminder->fresh()->sent_at);
    }

    public function test_command_dispatches_reminder_sent_event(): void
    {
        Notification::fake();
        Event::fake([DocumentReminderSent::class]);

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
        ]);
        $reminder = DocumentReminder::factory()
            ->for($document)
            ->for($user)
            ->dueOn('2026-03-25')
            ->create();

        $this->artisan('documents:send-expiry-reminders')->assertExitCode(0);

        Event::assertDispatchedTimes(DocumentReminderSent::class, 1);
        Event::assertDispatched(DocumentReminderSent::class, function (DocumentReminderSent $event) use ($document, $reminder): bool {
            return $event->payload->documentId === $document->id
                && $event->payload->type === DocumentActivityType::ReminderSent
                && $event->payload->metadata['remind_on'] === '2026-03-25'
                && $event->payload->metadata['reminder_id'] === $reminder->id;
        });
    }
}
