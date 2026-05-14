<?php

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
