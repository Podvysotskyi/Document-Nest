<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    private const array DEFAULT_CATEGORY_NAMES = [
        'Finance',
        'Health',
        'Identity',
        'Home',
        'Vehicle',
        'Work',
        'Education',
        'Legal',
        'Other',
    ];

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $defaultCategories = collect(self::DEFAULT_CATEGORY_NAMES)
            ->map(fn (string $name): array => [
                'user_id' => $user->id,
                'name' => $name,
                'slug' => Str::slug($name),
            ])
            ->all();

        Category::query()->upsert(
            $defaultCategories,
            uniqueBy: ['user_id', 'slug'],
            update: ['name'],
        );
    }
}
