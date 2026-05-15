<?php

namespace Tests\Unit;

use App\DTOs\SavedDocumentFilterData;
use App\Models\SavedDocumentFilter;
use App\Models\User;
use App\Services\SavedDocumentFilterService;
use Tests\TestCase;

class SavedDocumentFilterServiceTest extends TestCase
{
    public function test_it_normalizes_filter_payloads_before_persisting(): void
    {
        $service = app(SavedDocumentFilterService::class);

        $this->assertSame([
            'q' => 'passport',
            'category_id' => 'uncategorized',
        ], $service->normalizeFilters([
            'q' => ' passport ',
            'category_id' => 'uncategorized',
            'tag_id' => '',
            'unexpected' => 'ignored',
        ]));
    }

    public function test_creating_default_filter_clears_existing_default(): void
    {
        $user = User::factory()->create();
        $oldDefault = SavedDocumentFilter::factory()->for($user)->default()->create();

        app(SavedDocumentFilterService::class)->createForUser($user, new SavedDocumentFilterData(
            name: 'New default',
            filters: ['q' => 'tax'],
            sort: 'newest',
            direction: 'desc',
            isDefault: true,
        ));

        $this->assertFalse($oldDefault->fresh()->is_default);
        $this->assertTrue(SavedDocumentFilter::query()->where('name', 'New default')->first()->is_default);
    }
}
