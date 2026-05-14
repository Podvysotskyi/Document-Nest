<?php

namespace Tests\Browser;

use App\Models\Document;
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
}
