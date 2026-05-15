<?php

namespace Tests\Unit\Services;

use App\Enums\DocumentActivityType;
use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use App\Services\DocumentActivityPayloadFactory;
use Tests\TestCase;

class DocumentActivityPayloadFactoryTest extends TestCase
{
    public function test_snapshot_includes_user_editable_fields_and_tag_ids(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'title' => 'Passport scan',
            'status' => DocumentStatus::Active,
            'notes' => 'travel',
            'issue_date' => '2021-08-12',
            'expiry_date' => '2026-08-12',
        ]);
        $tag = Tag::factory()->for($user)->create();
        $document->tags()->attach($tag);

        $factory = new DocumentActivityPayloadFactory;
        $snapshot = $factory->snapshot($document->fresh());

        $this->assertSame('Passport scan', $snapshot['title']);
        $this->assertSame('active', $snapshot['status']);
        $this->assertSame('travel', $snapshot['notes']);
        $this->assertSame('2021-08-12', $snapshot['issue_date']);
        $this->assertSame('2026-08-12', $snapshot['expiry_date']);
        $this->assertSame([$tag->id], $snapshot['tag_ids']);
    }

    public function test_for_created_payload_has_null_original_and_full_updated_snapshot(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create(['title' => 'New doc']);

        $payload = (new DocumentActivityPayloadFactory)->forCreated($document, (string) $user->id);

        $this->assertSame(DocumentActivityType::Created, $payload->type);
        $this->assertNull($payload->metadata['original']);
        $this->assertSame('New doc', $payload->metadata['updated']['title']);
        $this->assertSame(['created'], $payload->metadata['changed_fields']);
    }

    public function test_for_updated_payload_lists_changed_fields(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'title' => 'Original',
            'status' => DocumentStatus::Active,
        ]);

        $factory = new DocumentActivityPayloadFactory;
        $original = $factory->snapshot($document);

        $document->update([
            'title' => 'Renamed',
            'status' => DocumentStatus::Archived,
        ]);

        $payload = $factory->forUpdated($document->fresh(), $original, (string) $user->id);

        $this->assertSame(DocumentActivityType::Updated, $payload->type);
        $this->assertContains('title', $payload->metadata['changed_fields']);
        $this->assertContains('status', $payload->metadata['changed_fields']);
        $this->assertSame('Original', $payload->metadata['original']['title']);
        $this->assertSame('Renamed', $payload->metadata['updated']['title']);
    }

    public function test_for_deleted_payload_has_null_updated(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create(['title' => 'Bye']);

        $factory = new DocumentActivityPayloadFactory;
        $original = $factory->snapshot($document);

        $payload = $factory->forDeleted($document, $original, (string) $user->id);

        $this->assertSame(DocumentActivityType::Deleted, $payload->type);
        $this->assertNull($payload->metadata['updated']);
        $this->assertSame('Bye', $payload->metadata['original']['title']);
        $this->assertSame(['deleted'], $payload->metadata['changed_fields']);
    }

    public function test_bulk_payload_includes_bulk_action_id_and_source(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'status' => DocumentStatus::Active,
        ]);

        $factory = new DocumentActivityPayloadFactory;
        $original = $factory->snapshot($document);

        $document->update(['status' => DocumentStatus::Archived]);

        $payload = $factory->forBulkArchived($document->fresh(), $original, (string) $user->id, 'bulk-123');

        $this->assertSame(DocumentActivityType::BulkArchived, $payload->type);
        $this->assertSame('bulk-123', $payload->metadata['bulk_action_id']);
        $this->assertSame('documents.index', $payload->metadata['source']);
    }
}
