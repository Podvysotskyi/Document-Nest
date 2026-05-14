<?php

namespace Tests\Unit\Seeders;

use App\Models\User;
use Database\Seeders\DefaultCategoriesSeeder;
use Tests\TestCase;

class DefaultCategoriesSeederTest extends TestCase
{
    public function test_default_categories_seeder_creates_expected_categories_for_all_users_and_is_idempotent(): void
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $this->seed(DefaultCategoriesSeeder::class);
        $this->seed(DefaultCategoriesSeeder::class);

        $this->assertSame(9, $firstUser->categories()->count());
        $this->assertSame(9, $secondUser->categories()->count());

        $expected = [
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

        $this->assertEqualsCanonicalizing($expected, $firstUser->categories()->pluck('name')->all());
        $this->assertEqualsCanonicalizing($expected, $secondUser->categories()->pluck('name')->all());
    }
}
