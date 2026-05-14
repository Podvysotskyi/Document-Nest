<?php

namespace Tests\Unit\Repositories;

use App\DTOs\DocumentFiltersData;
use App\Enums\DocumentStatus;
use App\Models\Category;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\DocumentRepository;
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

    public function test_document_repository_loads_for_show_with_relations(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        $tag = Tag::factory()->for($user)->create();
        $document = Document::factory()->for($user)->create([
            'category_id' => $category->id,
        ]);
        $document->tags()->sync([$tag->id]);

        $loaded = app(DocumentRepository::class)->loadForShow($document);

        $this->assertTrue($loaded->relationLoaded('category'));
        $this->assertTrue($loaded->relationLoaded('tags'));
    }
}
