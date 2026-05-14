<?php

use App\Models\User;
use Database\Seeders\DefaultCategoriesSeeder;

test('default categories seeder creates expected categories for all users and is idempotent', function () {
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();

    $this->seed(DefaultCategoriesSeeder::class);
    $this->seed(DefaultCategoriesSeeder::class);

    expect($firstUser->categories()->count())->toBe(9);
    expect($secondUser->categories()->count())->toBe(9);

    $firstUserNames = $firstUser->categories()->pluck('name')->all();
    $secondUserNames = $secondUser->categories()->pluck('name')->all();

    expect($firstUserNames)->toEqualCanonicalizing([
        'Finance',
        'Health',
        'Identity',
        'Home',
        'Vehicle',
        'Work',
        'Education',
        'Legal',
        'Other',
    ]);
    expect($secondUserNames)->toEqualCanonicalizing([
        'Finance',
        'Health',
        'Identity',
        'Home',
        'Vehicle',
        'Work',
        'Education',
        'Legal',
        'Other',
    ]);
});
