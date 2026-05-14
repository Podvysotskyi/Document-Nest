<?php

use App\DTOs\StoreTagData;
use App\DTOs\UpdateTagData;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\TagRepository;

test('tag repository returns only tags owned by user ordered by name', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Tag::factory()->for($user)->create(['name' => 'zeta', 'slug' => 'zeta']);
    Tag::factory()->for($user)->create(['name' => 'alpha', 'slug' => 'alpha']);
    Tag::factory()->for($otherUser)->create(['name' => 'other', 'slug' => 'other']);

    $tags = app(TagRepository::class)->listForUser($user);

    expect($tags)->toHaveCount(2);
    expect($tags->pluck('name')->all())->toBe(['alpha', 'zeta']);
});

test('tag repository can return tags with document counts', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->for($user)->create(['name' => 'Important', 'slug' => 'important']);

    Document::factory()->for($user)->create()->tags()->attach($tag);

    $tags = app(TagRepository::class)->listForUserWithDocumentCounts($user);

    expect($tags)->toHaveCount(1);
    expect($tags->first()->documents_count)->toBe(1);
});

test('tag repository can create tag for user and generate unique slug', function () {
    $user = User::factory()->create();
    $repository = app(TagRepository::class);

    $first = $repository->createForUser($user, new StoreTagData(name: 'Needs Review'));
    $second = $repository->createForUser($user, new StoreTagData(name: 'Needs Review'));

    expect($first->name)->toBe('Needs Review');
    expect($first->slug)->toBe('needs-review');
    expect($second->slug)->toBe('needs-review-2');
});

test('tag repository can update tag and keep slug unique', function () {
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

    expect($updated->name)->toBe('Work Notes');
    expect($updated->slug)->toBe('work-notes-2');
});

test('tag repository can delete tag', function () {
    $user = User::factory()->create();
    $repository = app(TagRepository::class);

    $tag = Tag::factory()->for($user)->create([
        'name' => 'Temp',
        'slug' => 'temp',
    ]);

    $repository->delete($tag);

    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});
