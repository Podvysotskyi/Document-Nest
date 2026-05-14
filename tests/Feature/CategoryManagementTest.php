<?php

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated user can view categories page', function () {
    $user = User::factory()->create();

    Category::factory()->for($user)->create([
        'name' => 'Custom Category',
        'slug' => 'custom-category',
    ]);

    $this->actingAs($user)
        ->get(route('categories.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Categories/Index')
            ->has('categories')
        );
});

test('authenticated user can create category', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('categories.store'), [
            'name' => 'Insurance',
        ])
        ->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', [
        'user_id' => $user->id,
        'name' => 'Insurance',
        'slug' => 'insurance',
    ]);
});

test('authenticated user can update category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create([
        'name' => 'Insurance',
        'slug' => 'insurance',
    ]);

    $this->actingAs($user)
        ->patch(route('categories.update', $category), [
            'name' => 'Insurance Updated',
        ])
        ->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Insurance Updated',
        'slug' => 'insurance-updated',
    ]);
});

test('authenticated user can delete category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create([
        'name' => 'Insurance',
        'slug' => 'insurance',
    ]);

    $this->actingAs($user)
        ->delete(route('categories.destroy', $category))
        ->assertRedirect(route('categories.index'));

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});

test('authenticated user cannot delete category that has documents', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create([
        'name' => 'Insurance',
        'slug' => 'insurance',
    ]);

    Document::factory()->for($user)->for($category)->create();

    $this->actingAs($user)
        ->delete(route('categories.destroy', $category))
        ->assertStatus(403);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
    ]);
});

test('user cannot update or delete another users category', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $category = Category::factory()->for($otherUser)->create([
        'name' => 'Private',
        'slug' => 'private',
    ]);

    $this->actingAs($user)
        ->patch(route('categories.update', $category), ['name' => 'Hacked'])
        ->assertStatus(403);

    $this->actingAs($user)
        ->delete(route('categories.destroy', $category))
        ->assertStatus(403);
});

test('guest cannot access categories management', function () {
    $this->get(route('categories.index'))->assertRedirect(route('login'));
    $this->post(route('categories.store'), ['name' => 'Insurance'])->assertRedirect(route('login'));
});
