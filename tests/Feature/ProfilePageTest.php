<?php

namespace Tests\Feature;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    public function test_guest_cannot_access_profile_page(): void
    {
        $this->get(route('profile'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Profile User',
            'email' => 'profile@example.com',
        ]);

        $this->actingAs($user)
            ->get(route('profile'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Profile')
                ->where('auth.user.name', 'Profile User')
                ->where('auth.user.email', 'profile@example.com')
            );
    }
}
