<?php

namespace Tests\Feature\Documents\Activities;

use App\Enums\DocumentActivityType;
use App\Enums\DocumentStatus;
use App\Events\Documents\DocumentArchived;
use App\Events\Documents\DocumentCreated;
use App\Events\Documents\DocumentDeleted;
use App\Events\Documents\DocumentDownloaded;
use App\Events\Documents\DocumentRestored;
use App\Events\Documents\DocumentUpdated;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentActivityEventsTest extends TestCase
{
    public function test_storing_a_document_dispatches_document_created_event(): void
    {
        Storage::fake('local');
        Event::fake([DocumentCreated::class]);

        $user = User::factory()->create();
        $category = $user->categories()->where('slug', 'finance')->first();
        $file = UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf');

        $this->actingAs($user)->post(route('documents.store'), [
            'file' => $file,
            'title' => 'My Contract',
            'category_id' => $category->id,
            'notes' => 'Some notes',
            'status' => DocumentStatus::Active->value,
        ])->assertRedirect(route('documents.index'));

        Event::assertDispatchedTimes(DocumentCreated::class, 1);
        Event::assertDispatched(DocumentCreated::class, function (DocumentCreated $event) use ($user): bool {
            return $event->payload->userId === $user->id
                && $event->payload->actorId === $user->id
                && $event->payload->type === DocumentActivityType::Created
                && $event->payload->metadata['original'] === null
                && $event->payload->metadata['updated']['title'] === 'My Contract';
        });
    }

    public function test_updating_a_document_dispatches_document_updated_event_with_changed_fields(): void
    {
        Event::fake([DocumentUpdated::class]);

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'title' => 'Old Title',
            'status' => DocumentStatus::Active,
        ]);

        $this->actingAs($user)->put(route('documents.update', $document), [
            'title' => 'New Title',
            'category_id' => $document->category_id,
            'status' => DocumentStatus::Archived->value,
        ])->assertRedirect(route('documents.show', $document));

        Event::assertDispatchedTimes(DocumentUpdated::class, 1);
        Event::assertDispatched(DocumentUpdated::class, function (DocumentUpdated $event) use ($document): bool {
            return $event->payload->documentId === $document->id
                && $event->payload->type === DocumentActivityType::Updated
                && in_array('title', $event->payload->metadata['changed_fields'], true)
                && in_array('status', $event->payload->metadata['changed_fields'], true)
                && $event->payload->metadata['original']['title'] === 'Old Title'
                && $event->payload->metadata['updated']['title'] === 'New Title';
        });
    }

    public function test_archiving_a_document_dispatches_document_archived_event(): void
    {
        Event::fake([DocumentArchived::class]);

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'status' => DocumentStatus::Active,
        ]);

        $this->actingAs($user)->post(route('documents.archive', $document))->assertRedirect();

        Event::assertDispatchedTimes(DocumentArchived::class, 1);
        Event::assertDispatched(DocumentArchived::class, function (DocumentArchived $event) use ($document): bool {
            return $event->payload->documentId === $document->id
                && $event->payload->type === DocumentActivityType::Archived
                && $event->payload->metadata['updated']['status'] === DocumentStatus::Archived->value;
        });
    }

    public function test_restoring_a_document_dispatches_document_restored_event(): void
    {
        Event::fake([DocumentRestored::class]);

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'status' => DocumentStatus::Archived,
            'archived_at' => now(),
        ]);

        $this->actingAs($user)->post(route('documents.restore', $document))->assertRedirect();

        Event::assertDispatchedTimes(DocumentRestored::class, 1);
        Event::assertDispatched(DocumentRestored::class, function (DocumentRestored $event) use ($document): bool {
            return $event->payload->documentId === $document->id
                && $event->payload->type === DocumentActivityType::Restored
                && $event->payload->metadata['updated']['status'] === DocumentStatus::Active->value;
        });
    }

    public function test_deleting_a_document_dispatches_document_deleted_event(): void
    {
        Storage::fake('local');
        Event::fake([DocumentDeleted::class]);

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'stored_path' => 'documents/delete-me.pdf',
            'title' => 'Goodbye',
        ]);
        Storage::disk('local')->put('documents/delete-me.pdf', 'content');

        $this->actingAs($user)->delete(route('documents.destroy', $document))->assertRedirect(route('documents.index'));

        Event::assertDispatchedTimes(DocumentDeleted::class, 1);
        Event::assertDispatched(DocumentDeleted::class, function (DocumentDeleted $event) use ($document): bool {
            return $event->payload->documentId === $document->id
                && $event->payload->type === DocumentActivityType::Deleted
                && $event->payload->metadata['original']['title'] === 'Goodbye'
                && $event->payload->metadata['updated'] === null;
        });
    }

    public function test_downloading_a_document_dispatches_document_downloaded_event(): void
    {
        Storage::fake('local');
        Event::fake([DocumentDownloaded::class]);

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'stored_path' => 'documents/file.pdf',
            'original_filename' => 'file.pdf',
        ]);
        Storage::disk('local')->put('documents/file.pdf', 'content');

        $this->actingAs($user)->get(route('documents.download', $document))->assertStatus(200);

        Event::assertDispatchedTimes(DocumentDownloaded::class, 1);
        Event::assertDispatched(DocumentDownloaded::class, function (DocumentDownloaded $event) use ($document): bool {
            return $event->payload->documentId === $document->id
                && $event->payload->type === DocumentActivityType::Downloaded;
        });
    }
}
