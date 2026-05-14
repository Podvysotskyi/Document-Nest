<?php

use App\Models\User;
use Laravel\Dusk\Browser;

test('guest can see landing page and login page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertSee('Document Nest')
            ->assertSee('Securely organize your important life documents')
            ->assertSee('Sign in to get started')
            ->clickLink('Sign in to get started')
            ->assertPathIs('/login')
            ->assertSee('Sign in to your account')
            ->assertSee('Continue with Google');
    });
});

test('authenticated user sees dashboard link on landing page and is redirected from login', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/')
            ->assertSee('Go to Dashboard')
            ->visit('/login')
            ->assertPathIs('/dashboard');
    });
});
