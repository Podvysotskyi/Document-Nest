<?php

namespace Tests\Unit\Repositories;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Tests\TestCase;

class CategoryRepositoryTest extends TestCase
{
    public function test_category_repository_returns_only_categories_owned_by_user_ordered_by_name(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Category::factory()->for($user)->create(['name' => 'WorkX', 'slug' => 'work-x']);
        Category::factory()->for($user)->create(['name' => 'FinanceX', 'slug' => 'finance-x']);
        Category::factory()->for($otherUser)->create(['name' => 'OtherX', 'slug' => 'other-x']);

        $categories = app(CategoryRepository::class)->listForUser($user);

        $this->assertCount(11, $categories);
        $this->assertSame([
            'Education',
            'Finance',
            'FinanceX',
            'Health',
            'Home',
            'Identity',
            'Legal',
            'Other',
            'Vehicle',
            'Work',
            'WorkX',
        ], $categories->pluck('name')->all());
    }

    public function test_category_repository_can_filter_categories_that_have_documents(): void
    {
        $user = User::factory()->create();
        $categoryWithDoc = $user->categories()->where('name', 'Finance')->first();

        Document::factory()->for($user)->for($categoryWithDoc)->create();

        $categories = app(CategoryRepository::class)->listForUser($user, onlyWithDocuments: true);

        $this->assertCount(1, $categories);
        $this->assertSame($categoryWithDoc->id, $categories->first()->id);
    }

    public function test_category_repository_can_delete_category(): void
    {
        $user = User::factory()->create();
        $repository = app(CategoryRepository::class);

        $category = Category::factory()->for($user)->create([
            'name' => 'Temp',
            'slug' => 'temp',
        ]);

        $repository->delete($category);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
