<?php

namespace Tests\Browser;

use App\Models\Document;
use App\Models\SavedDocumentFilter;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DocumentsBrowserTest extends DuskTestCase
{
    public function test_authenticated_user_can_navigate_through_dashboard_and_documents(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'title' => 'Browser Test Document',
        ]);

        $this->browse(function (Browser $browser) use ($user, $document): void {
            $browser->loginAs($user)
                ->visit('/dashboard')
                ->assertSee('Dashboard')
                ->assertSee('Total documents')
                ->assertSee('Browser Test Document')
                ->click('nav a[href="/documents"]')
                ->waitForText('Documents')
                ->assertPathIs('/documents')
                ->assertSee('Browser Test Document')
                ->click('table tbody tr:first-child')
                ->waitForText('Edit')
                ->assertPathIs('/documents/'.$document->id)
                ->assertSee('Browser Test Document')
                ->assertSee('Edit')
                ->assertSee('Archive')
                ->press('Archive')
                ->waitForText('Restore')
                ->assertSee('Restore')
                ->assertSee('archived')
                ->press('Restore')
                ->waitForText('Archive')
                ->assertSee('Archive')
                ->assertSee('active')
                ->click('a[href$="/edit"]')
                ->waitForText('Edit Document')
                ->assertPathIs('/documents/'.$document->id.'/edit')
                ->assertSee('Edit Document')
                ->assertValue('input[placeholder="e.g. Identity Card"]', 'Browser Test Document');
        });
    }

    public function test_authenticated_user_can_access_top_navigation_on_mobile(): void
    {
        $user = User::factory()->create([
            'name' => 'Mobile Hidden Name',
        ]);

        $this->browse(function (Browser $browser) use ($user): void {
            $browser->resize(390, 844)
                ->loginAs($user)
                ->visit('/dashboard')
                ->assertSee('Dashboard')
                ->assertDontSee('Mobile Hidden Name');

            $topbarLayout = $browser->script(<<<'JS'
                const menuButton = document.querySelector('[data-testid="mobile-navigation-button"]').getBoundingClientRect();
                const accountButton = document.querySelector('[data-testid="account-menu-button"]').getBoundingClientRect();
                const accountName = window.getComputedStyle(document.querySelector('[data-testid="account-menu-name"]')).display;
                const accountChevron = window.getComputedStyle(document.querySelector('[data-testid="account-menu-chevron"]')).display;

                return [menuButton.left, accountButton.left, accountName, accountChevron];
            JS
            )[0];

            $this->assertLessThan($topbarLayout[1], $topbarLayout[0]);
            $this->assertSame('none', $topbarLayout[2]);
            $this->assertSame('none', $topbarLayout[3]);

            $browser->click('[data-testid="mobile-navigation-button"]')
                ->waitFor('nav#mobile-navigation')
                ->assertVisible('nav#mobile-navigation a[href="/documents"]')
                ->click('nav#mobile-navigation a[href="/documents"]')
                ->waitForText('Documents')
                ->assertPathIs('/documents');
        });
    }

    public function test_authenticated_user_can_use_account_dropdown(): void
    {
        $email = fake()->safeEmail();
        $user = User::factory()->create([
            'name' => 'Dropdown User',
            'email' => $email,
        ]);

        $this->browse(function (Browser $browser) use ($email, $user): void {
            $browser->loginAs($user)
                ->visit('/dashboard')
                ->assertSee('Dropdown User')
                ->click('[data-testid="account-menu-button"]')
                ->waitFor('[data-testid="account-menu"]')
                ->assertSee('Profile')
                ->assertSee('Logout')
                ->click('a[href="/profile"]')
                ->waitForText('Profile')
                ->assertPathIs('/profile')
                ->assertSee($email)
                ->click('[data-testid="account-menu-button"]')
                ->waitFor('[data-testid="account-menu"]')
                ->press('Logout')
                ->waitForLocation('/login')
                ->assertGuest();
        });
    }

    public function test_authenticated_user_can_open_and_close_mobile_document_preview_panel(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'title' => 'Mobile Preview Document',
            'mime_type' => 'application/pdf',
        ]);

        $this->browse(function (Browser $browser) use ($document, $user): void {
            $browser->resize(390, 844)
                ->loginAs($user)
                ->visit('/documents/'.$document->id)
                ->waitForText('Mobile Preview Document')
                ->assertVisible('[data-testid="mobile-preview-open"]')
                ->click('[data-testid="mobile-preview-open"]')
                ->waitFor('[data-testid="mobile-preview-panel"]')
                ->assertVisible('[data-testid="mobile-preview-download"]')
                ->click('[data-testid="mobile-preview-close"]')
                ->waitUntilMissing('[data-testid="mobile-preview-panel"]')
                ->assertPathIs('/documents/'.$document->id);
        });
    }

    public function test_authenticated_user_can_save_and_apply_document_filter_view(): void
    {
        $user = User::factory()->create();
        Document::factory()->for($user)->create(['title' => 'Passport Scan']);
        Document::factory()->for($user)->create(['title' => 'Tax Return']);

        $this->browse(function (Browser $browser) use ($user): void {
            $browser->loginAs($user)
                ->visit('/documents')
                ->waitForText('Documents')
                ->type('input[placeholder="Search documents..."]', 'Passport')
                ->waitUntilMissingText('Tax Return')
                ->type('input[placeholder="Expiring passports"]', 'Passport view')
                ->press('Save current')
                ->waitForText('Saved view created.')
                ->visit('/documents')
                ->waitForText('Tax Return');

            $browser->script(<<<'JS'
                const select = document.querySelector('select[name="saved_filter_id"]');
                const option = Array.from(select.options).find((item) => item.textContent.includes('Passport view'));

                select.value = option.value;
                select.dispatchEvent(new Event('change', { bubbles: true }));
            JS
            );

            $browser->press('Apply')
                ->waitUntilMissingText('Tax Return')
                ->assertSee('Passport Scan');
        });

        $this->assertTrue(SavedDocumentFilter::query()
            ->where('user_id', $user->id)
            ->where('name', 'Passport view')
            ->exists());
    }
}
