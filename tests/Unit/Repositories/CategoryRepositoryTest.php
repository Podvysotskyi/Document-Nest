<?php

use App\Models\Category;
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
