<?php

namespace Tests\Unit\Repositories;

use App\DTOs\DocumentFiltersData;
use App\DTOs\StoreDocumentData;
use App\DTOs\UpdateDocumentData;
use App\Enums\DocumentStatus;
use App\Models\Category;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\DocumentRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentRepositoryTest extends TestCase
{
    public function test_document_repository_paginates_user_owned_documents_with_filters(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = $user->categories()->where('slug', 'finance')->first();
        $tag = Tag::factory()->for($user)->create(['name' => 'tax', 'slug' => 'tax']);

        $matchingDocument = Document::factory()->for($user)->create([
            'category_id' => $category->id,
            'title' => 'Doc A',
            'status' => DocumentStatus::Active,
            'expiry_date' => now()->addDays(10)->toDateString(),
            'original_filename' => 'a.pdf',
            'stored_path' => 'documents/a.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1000,
        ]);
        $matchingDocument->tags()->sync([$tag->id]);

        Document::factory()->for($otherUser)->create([
            'title' => 'Other User Doc',
            'status' => DocumentStatus::Active,
            'original_filename' => 'x.pdf',
            'stored_path' => 'documents/x.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1000,
        ]);

        $filters = new DocumentFiltersData(
            query: null,
            categoryId: $category->id,
            uncategorizedOnly: false,
            tagId: $tag->id,
            status: DocumentStatus::Active,
            expiryFrom: now()->toDateString(),
            expiryTo: now()->addDays(30)->toDateString(),
            sort: 'newest',
            direction: 'desc',
        );

        $paginator = app(DocumentRepository::class)->paginateForUser($user, $filters);

        $this->assertSame(1, $paginator->total());
        $this->assertSame($matchingDocument->id, $paginator->items()[0]->id);
    }

    public function test_document_repository_can_filter_uncategorized_documents(): void
    {
        $user = User::factory()->create();
        $category = $user->categories()->where('slug', 'finance')->first();

        $uncategorized = Document::factory()->for($user)->create([
            'category_id' => null,
            'title' => 'No Category',
            'status' => DocumentStatus::Active,
            'original_filename' => 'uncategorized.pdf',
            'stored_path' => 'documents/uncategorized.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1200,
        ]);

        Document::factory()->for($user)->create([
            'category_id' => $category->id,
            'title' => 'With Category',
            'status' => DocumentStatus::Active,
            'original_filename' => 'categorized.pdf',
            'stored_path' => 'documents/categorized.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1200,
        ]);

        $filters = new DocumentFiltersData(
            query: null,
            categoryId: null,
            uncategorizedOnly: true,
            tagId: null,
            status: null,
            expiryFrom: null,
            expiryTo: null,
            sort: 'newest',
            direction: 'desc',
        );

        $paginator = app(DocumentRepository::class)->paginateForUser($user, $filters);

        $this->assertSame(1, $paginator->total());
        $this->assertSame($uncategorized->id, $paginator->items()[0]->id);
    }

    public function test_document_repository_can_create_update_archive_and_delete_a_document(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $category = $user->categories()->where('slug', 'finance')->first();
        $tag = Tag::factory()->for($user)->create(['name' => 'tax', 'slug' => 'tax']);
        $file = UploadedFile::fake()->create('passport.pdf', 20, 'application/pdf');

        $repository = app(DocumentRepository::class);
        $document = $repository->createForUser($user, new StoreDocumentData(
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

        $repository->update($document, new UpdateDocumentData(
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

        $repository->delete($document);
        $this->assertFalse(Document::query()->whereKey($document->id)->exists());
        Storage::disk('local')->assertMissing($document->stored_path);
    }

    public function test_document_repository_load_and_stream_helpers_work(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        $tag = Tag::factory()->for($user)->create();
        $document = Document::factory()->for($user)->create([
            'category_id' => $category->id,
            'original_filename' => 'license.pdf',
            'stored_path' => 'documents/license.pdf',
            'mime_type' => 'application/pdf',
        ]);
        $document->tags()->sync([$tag->id]);

        Storage::disk('local')->put($document->stored_path, 'content');

        $repository = app(DocumentRepository::class);

        $loaded = $repository->loadForShow($document);
        $this->assertTrue($loaded->relationLoaded('category'));
        $this->assertTrue($loaded->relationLoaded('tags'));

        $repository->archive($document);
        $document->refresh();
        $this->assertSame(DocumentStatus::Archived, $document->status);
        $this->assertNotNull($document->archived_at);

        $repository->restore($document);
        $document->refresh();
        $this->assertSame(DocumentStatus::Active, $document->status);
        $this->assertNull($document->archived_at);

        $previewResponse = $repository->streamPreview($document);
        $downloadResponse = $repository->streamDownload($document);

        $this->assertSame(200, $previewResponse->getStatusCode());
        $this->assertSame(200, $downloadResponse->getStatusCode());
        $this->assertStringContainsString('license.pdf', $downloadResponse->headers->get('content-disposition'));
    }
}
