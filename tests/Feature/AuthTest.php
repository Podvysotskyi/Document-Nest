<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_guest_is_redirected_to_google_for_authentication(): void
    {
        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    public function test_landing_page_is_accessible(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Document Nest');
    }

    public function test_login_page_is_accessible_to_guests(): void
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('Sign in to Document Nest')
            ->assertSee('License')
            ->assertSee('Developed by');
    }

    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_guest_can_login_with_google(): void
    {
        Storage::fake('public');
        Http::fake([
            'example.com/*' => Http::response('fake-image-bytes', 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('12345');
        $abstractUser->shouldReceive('getEmail')->andReturn('john@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('John Doe');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

        Socialite::shouldReceive('driver->stateless->user')->andReturn($abstractUser);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('John Doe', $user->name);
        $this->assertSame(9, $user->categories()->count());
        Storage::disk('public')->assertExists("users/{$user->id}.jpg");
        $this->assertTrue($user->hasRole(UserRole::Admin));
        $this->assertTrue($user->hasRole(UserRole::Developer));
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_documents(): void
    {
        $this->get(route('documents.index'))
            ->assertRedirect(route('login'));
    }
}
