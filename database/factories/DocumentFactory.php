<?php

namespace Database\Factories;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
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
            'category_id' => null,
            'title' => fake()->sentence(3),
            'notes' => fake()->optional()->sentence(),
            'status' => DocumentStatus::Active,
            'issue_date' => fake()->dateTimeBetween('-2 years', '-1 month')->format('Y-m-d'),
            'expiry_date' => fake()->optional()->dateTimeBetween('+1 month', '+2 years')?->format('Y-m-d'),
            'original_filename' => fake()->bothify('document-####.pdf'),
            'stored_path' => 'documents/'.fake()->uuid().'.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(50_000, 5_000_000),
            'archived_at' => null,
        ];
    }
}
