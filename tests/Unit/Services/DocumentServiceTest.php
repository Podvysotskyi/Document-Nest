<?php

namespace Tests\Unit\Services;

use App\DTOs\StoreDocumentData;
use App\DTOs\UpdateDocumentData;
use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use App\Services\DocumentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentServiceTest extends TestCase
{
    public function test_document_service_creates_updates_and_deletes_a_document(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $category = $user->categories()->where('slug', 'finance')->first();
        $tag = Tag::factory()->for($user)->create(['name' => 'tax', 'slug' => 'tax']);
        $file = UploadedFile::fake()->create('passport.pdf', 20, 'application/pdf');

        $service = app(DocumentService::class);
        $document = $service->createForUser($user, new StoreDocumentData(
            file: $file,
            title: 'Passport',
            categoryId: $category->id,
            tagIds: [$tag->id],
            notes: 'Important',
            issueDate: now()->subYear()->toDateString(),
            expiryDate: now()->addYear()->toDateString(),
            status: DocumentStatus::Active,
        ));

        $this->assertTrue($document->exists);
        Storage::disk('local')->assertExists($document->stored_path);

        $service->update($document, new UpdateDocumentData(
            title: 'Passport Updated',
            categoryId: $category->id,
            tagIds: [],
            notes: 'Updated notes',
            issueDate: now()->subYear()->toDateString(),
            expiryDate: now()->addYears(2)->toDateString(),
            status: DocumentStatus::Archived,
        ));

        $document->refresh();
        $this->assertSame('Passport Updated', $document->title);
        $this->assertSame(DocumentStatus::Archived, $document->status);
        $this->assertNotNull($document->archived_at);
        $this->assertSame(0, $document->tags()->count());

        $service->delete($document);
        $this->assertFalse(Document::query()->whereKey($document->id)->exists());
        Storage::disk('local')->assertMissing($document->stored_path);
    }

    public function test_document_service_archive_and_restore_toggle_status_and_archived_at(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'status' => DocumentStatus::Active,
            'archived_at' => null,
        ]);

        $service = app(DocumentService::class);

        $service->archive($document);
        $document->refresh();
        $this->assertSame(DocumentStatus::Archived, $document->status);
        $this->assertNotNull($document->archived_at);

        $service->restore($document);
        $document->refresh();
        $this->assertSame(DocumentStatus::Active, $document->status);
        $this->assertNull($document->archived_at);
    }
}
