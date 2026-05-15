<?php

namespace Tests\Feature\Documents\Reminders;

use App\Enums\DocumentActivityType;
use App\Events\Documents\DocumentReminderSent;
use App\Models\Document;
use App\Models\User;
use App\Services\DocumentActivityPayloadFactory;
use Tests\TestCase;

class ReminderActivityListenerTest extends TestCase
{
    public function test_reminder_event_records_activity_row_via_in_memory_service(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'title' => 'Passport',
            'expiry_date' => '2026-04-01',
        ]);
        $payload = app(DocumentActivityPayloadFactory::class)
            ->forReminderSent($document, '2026-03-25', 'reminder-uuid');

        DocumentReminderSent::dispatch($payload);

        $recorded = $this->documentActivityService->payloads();
        $this->assertCount(1, $recorded);

        $payload = $recorded[0];
        $this->assertSame($document->id, $payload->documentId);
        $this->assertSame(DocumentActivityType::ReminderSent, $payload->type);
        $this->assertSame('2026-03-25', $payload->metadata['remind_on']);
        $this->assertSame('reminder-uuid', $payload->metadata['reminder_id']);
        $this->assertSame('2026-04-01', $payload->metadata['expiry_date']);
    }
}
