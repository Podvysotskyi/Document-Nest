<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TagManagementTest extends TestCase
{
    public function test_authenticated_user_can_view_tags_page(): void
    {
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
    }

    public function test_authenticated_user_can_create_tag(): void
    {
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
    }

    public function test_authenticated_user_can_update_tag(): void
    {
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
    }

    public function test_authenticated_user_can_delete_tag(): void
    {
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
    }

    public function test_user_cannot_update_or_delete_another_users_tag(): void
    {
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
    }

    public function test_guest_cannot_access_tags_management(): void
    {
        $this->get(route('tags.index'))->assertRedirect(route('login'));
        $this->post(route('tags.store'), ['name' => 'Important'])->assertRedirect(route('login'));
    }
}
