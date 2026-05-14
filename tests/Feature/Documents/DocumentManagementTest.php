<?php

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated user can view documents index with filters', function () {
    $user = User::factory()->create();
    $category = $user->categories()->where('slug', 'finance')->first();
    $tag = Tag::factory()->for($user)->create(['name' => 'Tax']);

    Document::factory()->for($user)->create([
        'category_id' => $category->id,
        'title' => 'Finance Doc',
    ])->tags()->attach($tag);

    Document::factory()->for($user)->create([
        'title' => 'Other Doc',
    ]);

    $response = $this->actingAs($user)->get(route('documents.index', [
        'category_id' => $category->id,
        'tag_id' => $tag->id,
    ]));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Documents/Index')
        ->has('documents.data', 1)
        ->where('documents.data.0.title', 'Finance Doc')
        ->has('categories', 1)
        ->where('categories.0.name', 'Finance')
    );
});

test('authenticated user can sort documents by category', function () {
    $user = User::factory()->create();

    // The categories are created by the UserObserver
    $categoryA = $user->categories()->orderBy('name')->first();
    $categoryB = $user->categories()->orderBy('name', 'desc')->first();

    Document::factory()->for($user)->create([
        'category_id' => $categoryA->id,
        'title' => 'Doc in Category A',
    ]);

    Document::factory()->for($user)->create([
        'category_id' => $categoryB->id,
        'title' => 'Doc in Category B',
    ]);

    // Sorting by category ASC
    $response = $this->actingAs($user)->get(route('documents.index', [
        'sort' => 'category',
        'direction' => 'asc',
    ]));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->has('documents.data', 2)
        ->where('documents.data.0.title', 'Doc in Category A')
        ->where('documents.data.1.title', 'Doc in Category B')
    );

    // Sorting by category DESC
    $response = $this->actingAs($user)->get(route('documents.index', [
        'sort' => 'category',
        'direction' => 'desc',
    ]));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->has('documents.data', 2)
        ->where('documents.data.0.title', 'Doc in Category B')
        ->where('documents.data.1.title', 'Doc in Category A')
    );
});

test('authenticated user can view create document page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('documents.create'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Documents/Create')
        ->has('categories', 9)
        ->has('statuses', 3)
    );
});

test('authenticated user can store a document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $category = $user->categories()->where('slug', 'finance')->first();
    $tag = Tag::factory()->for($user)->create(['name' => 'Tax']);
    $file = UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf');

    $response = $this->actingAs($user)->post(route('documents.store'), [
        'file' => $file,
        'title' => 'My Contract',
        'category_id' => $category->id,
        'tag_ids' => [$tag->id],
        'notes' => 'Some notes',
        'status' => DocumentStatus::Active->value,
    ]);

    $document = Document::first();
    $response->assertRedirect(route('documents.show', $document));

    expect($document->title)->toBe('My Contract');
    expect($document->user_id)->toBe($user->id);
    Storage::disk('local')->assertExists($document->stored_path);
});

test('authenticated user can view a document', function () {
    $user = User::factory()->create();
    $document = Document::factory()->for($user)->create(['title' => 'My Document']);

    $response = $this->actingAs($user)->get(route('documents.show', $document));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Documents/Show')
        ->where('document.title', 'My Document')
        ->has('previewUrl')
        ->has('downloadUrl')
    );
});

test('authenticated user can view edit document page', function () {
    $user = User::factory()->create();
    $document = Document::factory()->for($user)->create();

    $response = $this->actingAs($user)->get(route('documents.edit', $document));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Documents/Edit')
        ->where('document.id', $document->id)
        ->has('categories', 9)
    );
});

test('authenticated user can update a document', function () {
    $user = User::factory()->create();
    $document = Document::factory()->for($user)->create(['title' => 'Old Title']);

    $response = $this->actingAs($user)->put(route('documents.update', $document), [
        'title' => 'New Title',
        'category_id' => $document->category_id,
        'status' => DocumentStatus::Archived->value,
    ]);

    $response->assertRedirect(route('documents.show', $document));
    expect($document->fresh()->title)->toBe('New Title');
    expect($document->fresh()->status)->toBe(DocumentStatus::Archived);
});

test('authenticated user can delete a document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $document = Document::factory()->for($user)->create([
        'stored_path' => 'documents/test.pdf',
    ]);
    Storage::disk('local')->put('documents/test.pdf', 'content');

    $response = $this->actingAs($user)->delete(route('documents.destroy', $document));

    $response->assertRedirect(route('documents.index'));
    $this->assertDatabaseMissing('documents', ['id' => $document->id]);
    Storage::disk('local')->assertMissing('documents/test.pdf');
});

test('authenticated user can archive a document', function () {
    $user = User::factory()->create();
    $document = Document::factory()->for($user)->create(['status' => DocumentStatus::Active]);

    $response = $this->actingAs($user)->post(route('documents.archive', $document));

    $response->assertRedirect();
    expect($document->fresh()->status)->toBe(DocumentStatus::Archived);
    expect($document->fresh()->archived_at)->not->toBeNull();
});

test('authenticated user can restore a document', function () {
    $user = User::factory()->create();
    $document = Document::factory()->for($user)->create([
        'status' => DocumentStatus::Archived,
        'archived_at' => now(),
    ]);

    $response = $this->actingAs($user)->post(route('documents.restore', $document));

    $response->assertRedirect();
    expect($document->fresh()->status)->toBe(DocumentStatus::Active);
    expect($document->fresh()->archived_at)->toBeNull();
});

test('authenticated user can preview a document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $document = Document::factory()->for($user)->create([
        'stored_path' => 'documents/test.pdf',
        'mime_type' => 'application/pdf',
    ]);
    Storage::disk('local')->put('documents/test.pdf', 'content');

    $response = $this->actingAs($user)->get(route('documents.preview', $document));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
});

test('authenticated user can download a document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $document = Document::factory()->for($user)->create([
        'stored_path' => 'documents/test.pdf',
        'original_filename' => 'my-file.pdf',
    ]);
    Storage::disk('local')->put('documents/test.pdf', 'content');

    $response = $this->actingAs($user)->get(route('documents.download', $document));

    $response->assertStatus(200);
    $response->assertHeader('Content-Disposition', 'attachment; filename=my-file.pdf');
});

test('user cannot view or manage another users document', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $document = Document::factory()->for($otherUser)->create();

    $this->actingAs($user)->get(route('documents.show', $document))->assertStatus(403);
    $this->actingAs($user)->put(route('documents.update', $document), ['title' => 'Hacked'])->assertStatus(403);
    $this->actingAs($user)->delete(route('documents.destroy', $document))->assertStatus(403);
});
