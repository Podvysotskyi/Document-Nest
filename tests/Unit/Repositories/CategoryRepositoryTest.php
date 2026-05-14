<?php

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
