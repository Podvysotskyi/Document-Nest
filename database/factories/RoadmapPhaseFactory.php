<?php

namespace Database\Factories;

use App\Models\Sqlite\RoadmapPhase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoadmapPhase>
 */
class RoadmapPhaseFactory extends Factory
{
    protected $model = RoadmapPhase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->word(),
            'title' => fake()->sentence(3),
            'status' => fake()->randomElement(['Draft', 'Planned', 'Published']),
            'sort_order' => fake()->numberBetween(1, 100),
            'is_visible' => true,
        ];
    }
}
