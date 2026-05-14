<?php

namespace Database\Factories;

use App\Models\Roadmap\RoadmapItem;
use App\Models\Roadmap\RoadmapPhase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoadmapItem>
 */
class RoadmapItemFactory extends Factory
{
    protected $model = RoadmapItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'roadmap_phase_id' => RoadmapPhase::factory(),
            'title' => fake()->sentence(4),
            'sort_order' => fake()->numberBetween(1, 100),
            'is_visible' => true,
        ];
    }
}
