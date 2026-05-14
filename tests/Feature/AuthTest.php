<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

test('guest is redirected to google for authentication', function () {
    $response = $this->get(route('auth.google.redirect'));

    $response->assertRedirect();
    expect($response->getTargetUrl())->toContain('accounts.google.com');
});

test('landing page is accessible', function () {
    $this->get('/')
        ->assertStatus(200)
        ->assertSee('Document Nest');
});

test('login page is accessible to guests', function () {
    $this->get(route('login'))
        ->assertStatus(200)
        ->assertSee('Sign in to your account');
});

test('authenticated user is redirected from login page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('login'))
        ->assertRedirect(route('dashboard'));
});

test('guest can login with google', function () {
    $abstractUser = Mockery::mock(SocialiteUser::class);
    $abstractUser->shouldReceive('getId')->andReturn('12345');
    $abstractUser->shouldReceive('getEmail')->andReturn('john@example.com');
    $abstractUser->shouldReceive('getName')->andReturn('John Doe');
    $abstractUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

    Socialite::shouldReceive('driver->stateless->user')->andReturn($abstractUser);

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect('/');
    $this->assertAuthenticated();

    $user = User::where('email', 'john@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('John Doe');
    expect($user->categories()->count())->toBe(9); // Default categories
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('guest cannot access dashboard', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

test('guest cannot access documents', function () {
    $this->get(route('documents.index'))
        ->assertRedirect(route('login'));
});
