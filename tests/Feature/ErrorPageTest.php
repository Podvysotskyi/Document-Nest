<?php

use App\Models\Document;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('it renders the error page for 404', function () {
    $response = $this->get('/non-existent-page');

    $response->assertStatus(404);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Error')
        ->where('status', 404)
    );
});

test('it renders the error page for 403', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $document = Document::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('documents.show', $document));

    $response->assertStatus(403);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Error')
        ->where('status', 403)
    );
});
