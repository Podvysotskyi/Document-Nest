<?php

use App\Models\User;

test('user observer creates default categories on user creation', function () {
    $user = User::factory()->create();

    expect($user->categories()->count())->toBe(9);
    expect($user->categories()->pluck('name')->all())->toEqualCanonicalizing([
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
