<?php

use App\DTOs\StoreCategoryData;
use App\DTOs\UpdateCategoryData;
use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use App\Repositories\CategoryRepository;

test('category repository returns only categories owned by user ordered by name', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Category::factory()->for($user)->create(['name' => 'WorkX', 'slug' => 'work-x']);
    Category::factory()->for($user)->create(['name' => 'FinanceX', 'slug' => 'finance-x']);
    Category::factory()->for($otherUser)->create(['name' => 'OtherX', 'slug' => 'other-x']);

    $categories = app(CategoryRepository::class)->listForUser($user);

    expect($categories)->toHaveCount(11);
    expect($categories->pluck('name')->all())->toBe([
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
    ]);
});

test('category repository can filter categories that have documents', function () {
    $user = User::factory()->create();
    $categoryWithDoc = $user->categories()->where('name', 'Finance')->first();
    $categoryWithoutDoc = $user->categories()->where('name', 'Health')->first();

    Document::factory()->for($user)->for($categoryWithDoc)->create();

    $categories = app(CategoryRepository::class)->listForUser($user, onlyWithDocuments: true);

    expect($categories)->toHaveCount(1);
    expect($categories->first()->id)->toBe($categoryWithDoc->id);
});

test('category repository can create category for user and generate unique slug', function () {
    $user = User::factory()->create();
    $repository = app(CategoryRepository::class);

    $first = $repository->createForUser($user, new StoreCategoryData(name: 'Travel Docs'));
    $second = $repository->createForUser($user, new StoreCategoryData(name: 'Travel Docs'));

    expect($first->name)->toBe('Travel Docs');
    expect($first->slug)->toBe('travel-docs');
    expect($second->slug)->toBe('travel-docs-2');
});

test('category repository can update category and keep slug unique', function () {
    $user = User::factory()->create();
    $repository = app(CategoryRepository::class);

    $existing = Category::factory()->for($user)->create([
        'name' => 'Work X',
        'slug' => 'work-x',
    ]);

    $category = Category::factory()->for($user)->create([
        'name' => 'ArchiveX',
        'slug' => 'archive-x',
    ]);

    $updated = $repository->update($category, new UpdateCategoryData(name: $existing->name));

    expect($updated->name)->toBe('Work X');
    expect($updated->slug)->toBe('work-x-2');
});

test('category repository can delete category', function () {
    $user = User::factory()->create();
    $repository = app(CategoryRepository::class);

    $category = Category::factory()->for($user)->create([
        'name' => 'Temp',
        'slug' => 'temp',
    ]);

    $repository->delete($category);

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});
