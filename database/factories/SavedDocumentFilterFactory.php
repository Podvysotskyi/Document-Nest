<?php

namespace Database\Factories;

use App\Models\SavedDocumentFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavedDocumentFilter>
 */
class SavedDocumentFilterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(2, true),
            'filters' => [
                'q' => fake()->word(),
            ],
            'sort' => 'newest',
            'direction' => 'desc',
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn (): array => [
            'is_default' => true,
        ]);
    }
}
