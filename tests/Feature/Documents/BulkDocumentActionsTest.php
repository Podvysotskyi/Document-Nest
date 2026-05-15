<?php

namespace Tests\Feature\Documents;

use App\Enums\DocumentStatus;
use App\Events\Documents\DocumentsBulkActionCompleted;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BulkDocumentActionsTest extends TestCase
{
    public function test_authenticated_user_can_bulk_archive_documents(): void
    {
        $user = User::factory()->create();
        $documents = Document::factory()->for($user)->count(2)->create([
            'status' => DocumentStatus::Active,
            'archived_at' => null,
        ]);

        $response = $this->actingAs($user)->post(route('documents.bulk.archive'), [
            'document_ids' => $documents->pluck('id')->all(),
        ]);

        $response->assertRedirect(route('documents.index'));
        $documents->each(function (Document $document): void {
            $freshDocument = $document->fresh();
            $this->assertSame(DocumentStatus::Archived, $freshDocument?->status);
            $this->assertNotNull($freshDocument?->archived_at);
        });
    }

    public function test_authenticated_user_can_bulk_restore_documents(): void
    {
        $user = User::factory()->create();
        $documents = Document::factory()->for($user)->count(2)->create([
            'status' => DocumentStatus::Archived,
            'archived_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('documents.bulk.restore'), [
            'document_ids' => $documents->pluck('id')->all(),
        ]);

        $response->assertRedirect(route('documents.index'));
        $documents->each(function (Document $document): void {
            $freshDocument = $document->fresh();
            $this->assertSame(DocumentStatus::Active, $freshDocument?->status);
            $this->assertNull($freshDocument?->archived_at);
        });
    }

    public function test_authenticated_user_can_bulk_delete_documents_and_files(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $firstDocument = Document::factory()->for($user)->create([
            'stored_path' => 'documents/first.pdf',
        ]);
        $secondDocument = Document::factory()->for($user)->create([
            'stored_path' => 'documents/second.pdf',
        ]);
        Storage::disk('local')->put('documents/first.pdf', 'first');
        Storage::disk('local')->put('documents/second.pdf', 'second');

        $response = $this->actingAs($user)->post(route('documents.bulk.delete'), [
            'document_ids' => [$firstDocument->id, $secondDocument->id],
        ]);

        $response->assertRedirect(route('documents.index'));
        $this->assertDatabaseMissing('documents', ['id' => $firstDocument->id]);
        $this->assertDatabaseMissing('documents', ['id' => $secondDocument->id]);
        Storage::disk('local')->assertMissing('documents/first.pdf');
        Storage::disk('local')->assertMissing('documents/second.pdf');
    }

    public function test_bulk_document_actions_reject_document_ids_from_another_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $ownedDocument = Document::factory()->for($user)->create([
            'status' => DocumentStatus::Active,
        ]);
        $otherDocument = Document::factory()->for($otherUser)->create([
            'status' => DocumentStatus::Active,
        ]);

        $response = $this->actingAs($user)->from(route('documents.index'))->post(route('documents.bulk.archive'), [
            'document_ids' => [$ownedDocument->id, $otherDocument->id],
        ]);

        $response->assertRedirect(route('documents.index'));
        $response->assertSessionHasErrors(['document_ids.1']);
        $this->assertSame(DocumentStatus::Active, $ownedDocument->fresh()?->status);
    }

    public function test_bulk_archive_dispatches_activity_event_for_each_changed_document(): void
    {
        Event::fake([DocumentsBulkActionCompleted::class]);

        $user = User::factory()->create();
        $activeDocument = Document::factory()->for($user)->create([
            'status' => DocumentStatus::Active,
            'archived_at' => null,
        ]);
        $alreadyArchivedDocument = Document::factory()->for($user)->create([
            'status' => DocumentStatus::Archived,
            'archived_at' => now(),
        ]);

        $this->actingAs($user)->post(route('documents.bulk.archive'), [
            'document_ids' => [$activeDocument->id, $alreadyArchivedDocument->id],
        ])->assertRedirect(route('documents.index'));

        Event::assertDispatchedTimes(DocumentsBulkActionCompleted::class, 1);
        Event::assertDispatched(
            DocumentsBulkActionCompleted::class,
            fn (DocumentsBulkActionCompleted $event): bool => $event->documentId === $activeDocument->id
                && $event->type === 'bulk_archived'
        );
    }
}
