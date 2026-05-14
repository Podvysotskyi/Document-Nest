<?php

namespace Tests\Feature\Documents;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    public function test_authenticated_user_can_view_documents_index_with_filters(): void
    {
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
            ->where('filters.category_id', $category->id)
            ->where('filters.tag_id', $tag->id)
            ->has('categories', 1)
            ->where('categories.0.name', 'Finance')
        );
    }

    public function test_authenticated_user_can_filter_documents_by_uncategorized_category(): void
    {
        $user = User::factory()->create();
        $category = $user->categories()->where('slug', 'finance')->first();

        Document::factory()->for($user)->create([
            'category_id' => null,
            'title' => 'Uncategorized Doc',
        ]);

        Document::factory()->for($user)->create([
            'category_id' => $category->id,
            'title' => 'Categorized Doc',
        ]);

        $response = $this->actingAs($user)->get(route('documents.index', [
            'category_id' => 'uncategorized',
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Documents/Index')
            ->has('documents.data', 1)
            ->where('documents.data.0.title', 'Uncategorized Doc')
            ->where('filters.category_id', 'uncategorized')
        );
    }

    public function test_authenticated_user_can_sort_documents_by_category(): void
    {
        $user = User::factory()->create();

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
    }

    public function test_authenticated_user_can_view_create_document_page(): void
    {
        $user = User::factory()->create();
        Tag::factory()->for($user)->create([
            'name' => 'Important',
            'slug' => 'important',
        ]);

        $response = $this->actingAs($user)->get(route('documents.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Documents/Create')
            ->has('categories', 9)
            ->has('tags', 1)
            ->has('statuses', 3)
        );
    }

    public function test_authenticated_user_can_store_a_document(): void
    {
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

        $this->assertSame('My Contract', $document->title);
        $this->assertSame($user->id, $document->user_id);
        Storage::disk('local')->assertExists($document->stored_path);
    }

    public function test_authenticated_user_can_view_a_document(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->for($user)->create([
            'name' => 'Important',
            'slug' => 'important',
        ]);
        $document = Document::factory()->for($user)->create(['title' => 'My Document']);
        $document->tags()->attach($tag);

        $response = $this->actingAs($user)->get(route('documents.show', $document));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Documents/Show')
            ->where('document.title', 'My Document')
            ->where('document.tags.0.id', $tag->id)
            ->where('document.tags.0.name', 'Important')
            ->has('previewUrl')
            ->has('downloadUrl')
        );
    }

    public function test_authenticated_user_can_view_edit_document_page(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->for($user)->create([
            'name' => 'Important',
            'slug' => 'important',
        ]);
        $document = Document::factory()->for($user)->create();
        $document->tags()->attach($tag);

        $response = $this->actingAs($user)->get(route('documents.edit', $document));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Documents/Edit')
            ->where('document.id', $document->id)
            ->where('document.tag_ids.0', $tag->id)
            ->has('categories', 9)
            ->has('tags', 1)
        );
    }

    public function test_authenticated_user_can_update_a_document(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create(['title' => 'Old Title']);

        $response = $this->actingAs($user)->put(route('documents.update', $document), [
            'title' => 'New Title',
            'category_id' => $document->category_id,
            'status' => DocumentStatus::Archived->value,
        ]);

        $response->assertRedirect(route('documents.show', $document));
        $this->assertSame('New Title', $document->fresh()->title);
        $this->assertSame(DocumentStatus::Archived, $document->fresh()->status);
    }

    public function test_authenticated_user_can_delete_a_document(): void
    {
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
    }

    public function test_authenticated_user_can_archive_a_document(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create(['status' => DocumentStatus::Active]);

        $response = $this->actingAs($user)->post(route('documents.archive', $document));

        $response->assertRedirect();
        $this->assertSame(DocumentStatus::Archived, $document->fresh()->status);
        $this->assertNotNull($document->fresh()->archived_at);
    }

    public function test_authenticated_user_can_restore_a_document(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'status' => DocumentStatus::Archived,
            'archived_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('documents.restore', $document));

        $response->assertRedirect();
        $this->assertSame(DocumentStatus::Active, $document->fresh()->status);
        $this->assertNull($document->fresh()->archived_at);
    }

    public function test_authenticated_user_can_preview_a_document(): void
    {
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
    }

    public function test_authenticated_user_can_download_a_document(): void
    {
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
    }

    public function test_user_cannot_view_or_manage_another_users_document(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $document = Document::factory()->for($otherUser)->create();

        $this->actingAs($user)->get(route('documents.show', $document))->assertStatus(403);
        $this->actingAs($user)->put(route('documents.update', $document), ['title' => 'Hacked'])->assertStatus(403);
        $this->actingAs($user)->delete(route('documents.destroy', $document))->assertStatus(403);
    }
}
