<?php

use App\Models\Document;
use App\Models\User;
use Laravel\Dusk\Browser;

test('authenticated user can navigate through dashboard and documents', function () {
    $user = User::factory()->create();

    // Create a document for the user
    $document = Document::factory()->for($user)->create([
        'title' => 'Browser Test Document',
    ]);

    $this->browse(function (Browser $browser) use ($user, $document) {
        $browser->loginAs($user)
            ->visit('/dashboard')
            ->assertSee('Dashboard')
            ->assertSee('Total documents')
            ->assertSee('Browser Test Document')

            // Navigate to documents index
            ->click('nav a[href="/documents"]')
            ->waitForText('Documents')
            ->assertPathIs('/documents')
            ->assertSee('Browser Test Document')

            // Navigate to document show page
            ->clickLink('Browser Test Document')
            ->waitForText('Edit')
            ->assertPathIs('/documents/'.$document->id)
            ->assertSee('Browser Test Document')
            ->assertSee('Edit')
            ->assertSee('Archive')

            // Archive the document
            ->press('Archive')
            ->waitForText('Restore')
            ->assertSee('Restore')
            ->assertSee('archived')

            // Restore the document
            ->press('Restore')
            ->waitForText('Archive')
            ->assertSee('Archive')
            ->assertSee('active')

            // Navigate to edit page
            ->clickLink('Edit')
            ->waitForText('Edit Document')
            ->assertPathIs('/documents/'.$document->id.'/edit')
            ->assertSee('Edit Document')
            ->assertValue('input[placeholder="Title"]', 'Browser Test Document');
    });
});
