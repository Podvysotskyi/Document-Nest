<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthenticationBrowserTest extends DuskTestCase
{
    public function test_guest_can_see_landing_page_and_login_page(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->assertSee('Document Nest')
                ->assertSee('Securely organize your important life documents')
                ->assertSee('Sign in to get started')
                ->clickLink('Sign in to get started')
                ->assertPathIs('/login')
                ->assertSee('Sign in to Document Nest')
                ->assertSee('Continue with Google');
        });
    }

    public function test_authenticated_user_sees_dashboard_link_on_landing_page_and_is_redirected_from_login(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user): void {
            $browser->loginAs($user)
                ->visit('/')
                ->assertSee('Go to Dashboard')
                ->visit('/login')
                ->assertPathIs('/dashboard');
        });
    }
}
