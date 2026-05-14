<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ErrorPageTest extends TestCase
{
    public function test_it_renders_the_error_page_for_404(): void
    {
        $response = $this->get('/non-existent-page');

        $response->assertStatus(404);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Error')
            ->where('status', 404)
        );
    }

    public function test_it_renders_the_error_page_for_403(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $document = Document::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('documents.show', $document));

        $response->assertStatus(403);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Error')
            ->where('status', 403)
        );
    }
}
