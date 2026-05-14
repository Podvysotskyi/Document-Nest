<?php

namespace Tests\Unit\Services;

use App\DTOs\StoreCategoryData;
use App\DTOs\UpdateCategoryData;
use App\Models\Category;
use App\Models\User;
use App\Services\CategoryService;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    public function test_category_service_creates_category_for_user_and_generates_unique_slug(): void
    {
        $user = User::factory()->create();
        $service = app(CategoryService::class);

        $first = $service->createForUser($user, new StoreCategoryData(name: 'Travel Docs'));
        $second = $service->createForUser($user, new StoreCategoryData(name: 'Travel Docs'));

        $this->assertSame('Travel Docs', $first->name);
        $this->assertSame('travel-docs', $first->slug);
        $this->assertSame('travel-docs-2', $second->slug);
    }

    public function test_category_service_updates_category_and_keeps_slug_unique(): void
    {
        $user = User::factory()->create();
        $service = app(CategoryService::class);

        $existing = Category::factory()->for($user)->create([
            'name' => 'Work X',
            'slug' => 'work-x',
        ]);

        $category = Category::factory()->for($user)->create([
            'name' => 'ArchiveX',
            'slug' => 'archive-x',
        ]);

        $updated = $service->update($category, new UpdateCategoryData(name: $existing->name));

        $this->assertSame('Work X', $updated->name);
        $this->assertSame('work-x-2', $updated->slug);
    }
}
