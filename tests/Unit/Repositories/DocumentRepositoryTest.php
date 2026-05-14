<?php

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

test('document repository paginates user-owned documents with filters', function () {
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
        tagId: $tag->id,
        status: DocumentStatus::Active,
        expiryFrom: now()->toDateString(),
        expiryTo: now()->addDays(30)->toDateString(),
        sort: 'newest',
    );

    $paginator = app(DocumentRepository::class)->paginateForUser($user, $filters);

    expect($paginator->total())->toBe(1);
    expect($paginator->items()[0]->id)->toBe($matchingDocument->id);
});

test('document repository can create update archive and delete a document', function () {
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

    expect($document->exists)->toBeTrue();
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
    expect($document->title)->toBe('Passport Updated');
    expect($document->status)->toBe(DocumentStatus::Archived);
    expect($document->archived_at)->not->toBeNull();
    expect($document->tags()->count())->toBe(0);

    $repository->delete($document);
    expect(Document::query()->whereKey($document->id)->exists())->toBeFalse();
    Storage::disk('local')->assertMissing($document->stored_path);
});

test('document repository load and stream helpers work', function () {
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
    expect($loaded->relationLoaded('category'))->toBeTrue();
    expect($loaded->relationLoaded('tags'))->toBeTrue();

    $repository->archive($document);
    $document->refresh();
    expect($document->status)->toBe(DocumentStatus::Archived);
    expect($document->archived_at)->not->toBeNull();

    $repository->restore($document);
    $document->refresh();
    expect($document->status)->toBe(DocumentStatus::Active);
    expect($document->archived_at)->toBeNull();

    $previewResponse = $repository->streamPreview($document);
    $downloadResponse = $repository->streamDownload($document);

    expect($previewResponse->getStatusCode())->toBe(200);
    expect($downloadResponse->getStatusCode())->toBe(200);
    expect($downloadResponse->headers->get('content-disposition'))->toContain('license.pdf');
});
