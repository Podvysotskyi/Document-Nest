<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    public function test_authenticated_user_can_view_categories_page(): void
    {
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
    }

    public function test_authenticated_user_can_create_category(): void
    {
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
    }

    public function test_authenticated_user_can_update_category(): void
    {
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
    }

    public function test_authenticated_user_can_delete_category(): void
    {
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
    }

    public function test_authenticated_user_cannot_delete_category_that_has_documents(): void
    {
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
    }

    public function test_user_cannot_update_or_delete_another_users_category(): void
    {
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
    }

    public function test_guest_cannot_access_categories_management(): void
    {
        $this->get(route('categories.index'))->assertRedirect(route('login'));
        $this->post(route('categories.store'), ['name' => 'Insurance'])->assertRedirect(route('login'));
    }
}
