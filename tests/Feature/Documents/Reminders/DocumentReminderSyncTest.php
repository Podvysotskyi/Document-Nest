<?php

namespace Tests\Feature\Documents\Reminders;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\DocumentReminder;
use App\Models\User;
use App\Services\DocumentReminderService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentReminderSyncTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-01-01 09:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_sync_creates_three_default_reminders_when_expiry_is_far_in_the_future(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
            'status' => DocumentStatus::Active,
        ]);

        app(DocumentReminderService::class)->syncForDocument($document);

        $this->assertSame(3, $document->reminders()->count());
        $this->assertEqualsCanonicalizing(
            ['2026-03-02', '2026-03-25', '2026-03-31'],
            $document->reminders()->pluck('remind_on')->map(fn (Carbon $d): string => $d->toDateString())->all(),
        );
    }

    public function test_sync_does_not_duplicate_reminders_on_repeat_calls(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
        ]);

        app(DocumentReminderService::class)->syncForDocument($document);
        app(DocumentReminderService::class)->syncForDocument($document);

        $this->assertSame(3, $document->reminders()->count());
    }

    public function test_sync_drops_unsent_reminders_when_expiry_is_cleared(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
        ]);
        app(DocumentReminderService::class)->syncForDocument($document);

        $document->update(['expiry_date' => null]);
        app(DocumentReminderService::class)->syncForDocument($document->fresh());

        $this->assertSame(0, $document->reminders()->count());
    }

    public function test_sync_drops_unsent_reminders_when_document_is_archived(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
        ]);
        app(DocumentReminderService::class)->syncForDocument($document);

        $document->update(['status' => DocumentStatus::Archived, 'archived_at' => now()]);
        app(DocumentReminderService::class)->syncForDocument($document->fresh());

        $this->assertSame(0, $document->reminders()->count());
    }

    public function test_sync_preserves_already_sent_reminders_when_expiry_changes(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
        ]);
        $sent = DocumentReminder::factory()
            ->for($document)
            ->for($user)
            ->sent()
            ->dueOn('2026-03-02')
            ->create();

        $document->update(['expiry_date' => '2026-05-01']);
        app(DocumentReminderService::class)->syncForDocument($document->fresh());

        $reloaded = $sent->fresh();
        $this->assertNotNull($reloaded);
        $this->assertSame('2026-03-02', $reloaded->remind_on->toDateString());
        $this->assertNotNull($reloaded->sent_at);
        $this->assertSame(4, $document->reminders()->count());
    }

    public function test_document_service_create_schedules_reminders(): void
    {
        $user = User::factory()->create();
        $category = $user->categories()->first();

        $this->actingAs($user)->post(route('documents.store'), [
            'file' => UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
            'title' => 'Passport',
            'category_id' => $category->id,
            'expiry_date' => '2026-04-01',
            'status' => DocumentStatus::Active->value,
        ]);

        $document = Document::firstOrFail();
        $this->assertSame(3, $document->reminders()->count());
    }

    public function test_document_service_update_syncs_reminders_to_new_expiry(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
        ]);
        app(DocumentReminderService::class)->syncForDocument($document);

        $this->actingAs($user)->put(route('documents.update', $document), [
            'title' => $document->title,
            'category_id' => $document->category_id,
            'status' => DocumentStatus::Active->value,
            'expiry_date' => '2026-06-01',
        ]);

        $document->refresh();
        $this->assertEqualsCanonicalizing(
            ['2026-05-02', '2026-05-25', '2026-05-31'],
            $document->reminders()->whereNull('sent_at')->pluck('remind_on')->map(fn (Carbon $d): string => $d->toDateString())->all(),
        );
    }

    public function test_document_service_archive_removes_unsent_reminders(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
        ]);
        app(DocumentReminderService::class)->syncForDocument($document);

        $this->actingAs($user)->post(route('documents.archive', $document));

        $this->assertSame(0, $document->fresh()->reminders()->whereNull('sent_at')->count());
    }

    public function test_deleting_a_document_cascades_reminders(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
            'stored_path' => 'documents/test.pdf',
        ]);
        Storage::fake('local');
        Storage::disk('local')->put('documents/test.pdf', 'x');

        app(DocumentReminderService::class)->syncForDocument($document);
        $this->assertSame(3, DocumentReminder::query()->count());

        $this->actingAs($user)->delete(route('documents.destroy', $document));

        $this->assertSame(0, DocumentReminder::query()->count());
    }
}
