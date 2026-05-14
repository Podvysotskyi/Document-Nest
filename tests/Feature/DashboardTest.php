<?php

use App\Models\Document;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated user can view dashboard', function () {
    $user = User::factory()->create();
    $category = $user->categories()->where('slug', 'finance')->first();

    Document::factory()->for($user)->create([
        'category_id' => $category->id,
        'title' => 'Recent Doc',
        'status' => Document::STATUS_ACTIVE,
        'expiry_date' => null,
    ]);

    Document::factory()->for($user)->create([
        'category_id' => $category->id,
        'title' => 'Expiring Soon',
        'status' => Document::STATUS_ACTIVE,
        'expiry_date' => now()->addDays(5)->toDateString(),
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Dashboard')
        ->has('stats', fn (Assert $page) => $page
            ->where('total_documents', 2)
            ->where('expiring_soon_count', 1)
            ->where('missing_expiry_count', 1)
        )
        ->has('recent_uploads', 2)
        ->has('expiring_soon', 1)
        ->has('missing_expiry', 1)
        ->has('documents_by_category', 9)
        ->where('documents_by_category.0.category_name', 'Finance')
    );
});
