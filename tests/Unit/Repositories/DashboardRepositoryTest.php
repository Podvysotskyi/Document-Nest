<?php

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use App\Repositories\DashboardRepository;

test('dashboard repository returns scoped aggregate metrics', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $category = $user->categories()->where('slug', 'finance')->first();

    Document::factory()->for($user)->create([
        'category_id' => $category->id,
        'title' => 'Soon Expiring',
        'status' => DocumentStatus::Active,
        'expiry_date' => now()->addDays(5)->toDateString(),
        'original_filename' => 'a.pdf',
        'stored_path' => 'documents/a.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 1000,
    ]);
    Document::factory()->for($user)->create([
        'title' => 'Missing Expiry',
        'status' => DocumentStatus::Active,
        'expiry_date' => null,
        'original_filename' => 'b.pdf',
        'stored_path' => 'documents/b.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 1000,
    ]);
    Document::factory()->for($otherUser)->create([
        'title' => 'Other User',
        'status' => DocumentStatus::Active,
        'original_filename' => 'x.pdf',
        'stored_path' => 'documents/x.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 1000,
    ]);

    $repository = app(DashboardRepository::class);

    expect($repository->totalDocuments($user))->toBe(2);
    expect($repository->expiringSoonCount($user))->toBe(1);
    expect($repository->missingExpiryCount($user))->toBe(1);
    expect($repository->recentUploads($user))->toHaveCount(2);
    expect($repository->expiringSoon($user))->toHaveCount(1);
    expect($repository->missingExpiry($user))->toHaveCount(1);
    expect($repository->documentsByCategory($user)->first()->name)->toBe('Finance');
    expect($repository->documentsByCategory($user))->toHaveCount(1);
    expect($repository->uncategorizedCount($user))->toBe(1);
});
