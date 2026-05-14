<?php

namespace Tests\Unit\Repositories;

use App\DTOs\StoreTagData;
use App\DTOs\UpdateTagData;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\TagRepository;
use Tests\TestCase;

class TagRepositoryTest extends TestCase
{
    public function test_tag_repository_returns_only_tags_owned_by_user_ordered_by_name(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Tag::factory()->for($user)->create(['name' => 'zeta', 'slug' => 'zeta']);
        Tag::factory()->for($user)->create(['name' => 'alpha', 'slug' => 'alpha']);
        Tag::factory()->for($otherUser)->create(['name' => 'other', 'slug' => 'other']);

        $tags = app(TagRepository::class)->listForUser($user);

        $this->assertCount(2, $tags);
        $this->assertSame(['alpha', 'zeta'], $tags->pluck('name')->all());
    }

    public function test_tag_repository_can_return_tags_with_document_counts(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->for($user)->create(['name' => 'Important', 'slug' => 'important']);

        Document::factory()->for($user)->create()->tags()->attach($tag);

        $tags = app(TagRepository::class)->listForUserWithDocumentCounts($user);

        $this->assertCount(1, $tags);
        $this->assertSame(1, $tags->first()->documents_count);
    }

    public function test_tag_repository_can_create_tag_for_user_and_generate_unique_slug(): void
    {
        $user = User::factory()->create();
        $repository = app(TagRepository::class);

        $first = $repository->createForUser($user, new StoreTagData(name: 'Needs Review'));
        $second = $repository->createForUser($user, new StoreTagData(name: 'Needs Review'));

        $this->assertSame('Needs Review', $first->name);
        $this->assertSame('needs-review', $first->slug);
        $this->assertSame('needs-review-2', $second->slug);
    }

    public function test_tag_repository_can_update_tag_and_keep_slug_unique(): void
    {
        $user = User::factory()->create();
        $repository = app(TagRepository::class);

        $existing = Tag::factory()->for($user)->create([
            'name' => 'Work Notes',
            'slug' => 'work-notes',
        ]);

        $tag = Tag::factory()->for($user)->create([
            'name' => 'Archive',
            'slug' => 'archive',
        ]);

        $updated = $repository->update($tag, new UpdateTagData(name: $existing->name));

        $this->assertSame('Work Notes', $updated->name);
        $this->assertSame('work-notes-2', $updated->slug);
    }

    public function test_tag_repository_can_delete_tag(): void
    {
        $user = User::factory()->create();
        $repository = app(TagRepository::class);

        $tag = Tag::factory()->for($user)->create([
            'name' => 'Temp',
            'slug' => 'temp',
        ]);

        $repository->delete($tag);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}
