<?php

namespace Tests\Unit\Repositories;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use App\Repositories\DashboardRepository;
use Tests\TestCase;

class DashboardRepositoryTest extends TestCase
{
    public function test_dashboard_repository_returns_scoped_aggregate_metrics(): void
    {
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

        $this->assertSame(2, $repository->totalDocuments($user));
        $this->assertSame(1, $repository->expiringSoonCount($user));
        $this->assertSame(1, $repository->missingExpiryCount($user));
        $this->assertCount(2, $repository->recentUploads($user));
        $this->assertCount(1, $repository->expiringSoon($user));
        $this->assertCount(1, $repository->missingExpiry($user));
        $this->assertSame('Finance', $repository->documentsByCategory($user)->first()->name);
        $this->assertCount(1, $repository->documentsByCategory($user));
        $this->assertSame(1, $repository->uncategorizedCount($user));
    }
}
