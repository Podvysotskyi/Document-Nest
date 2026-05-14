<?php

use App\Models\Tag;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated user can view tags page', function () {
    $user = User::factory()->create();

    Tag::factory()->for($user)->create([
        'name' => 'Custom Tag',
        'slug' => 'custom-tag',
    ]);

    $this->actingAs($user)
        ->get(route('tags.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tags/Index')
            ->has('tags')
        );
});

test('authenticated user can create tag', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tags.store'), [
            'name' => 'Important',
        ])
        ->assertRedirect(route('tags.index'));

    $this->assertDatabaseHas('tags', [
        'user_id' => $user->id,
        'name' => 'Important',
        'slug' => 'important',
    ]);
});

test('authenticated user can update tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->for($user)->create([
        'name' => 'Important',
        'slug' => 'important',
    ]);

    $this->actingAs($user)
        ->patch(route('tags.update', $tag), [
            'name' => 'Important Updated',
        ])
        ->assertRedirect(route('tags.index'));

    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => 'Important Updated',
        'slug' => 'important-updated',
    ]);
});

test('authenticated user can delete tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->for($user)->create([
        'name' => 'Important',
        'slug' => 'important',
    ]);

    $this->actingAs($user)
        ->delete(route('tags.destroy', $tag))
        ->assertRedirect(route('tags.index'));

    $this->assertDatabaseMissing('tags', [
        'id' => $tag->id,
    ]);
});

test('user cannot update or delete another users tag', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $tag = Tag::factory()->for($otherUser)->create([
        'name' => 'Private',
        'slug' => 'private',
    ]);

    $this->actingAs($user)
        ->patch(route('tags.update', $tag), ['name' => 'Hacked'])
        ->assertStatus(403);

    $this->actingAs($user)
        ->delete(route('tags.destroy', $tag))
        ->assertStatus(403);
});

test('guest cannot access tags management', function () {
    $this->get(route('tags.index'))->assertRedirect(route('login'));
    $this->post(route('tags.store'), ['name' => 'Important'])->assertRedirect(route('login'));
});
