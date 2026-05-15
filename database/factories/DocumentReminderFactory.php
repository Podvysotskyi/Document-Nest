<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentReminder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentReminder>
 */
class DocumentReminderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'user_id' => User::factory(),
            'remind_on' => fake()->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'sent_at' => null,
        ];
    }

    public function sent(): static
    {
        return $this->state(fn (): array => [
            'sent_at' => now(),
        ]);
    }

    public function dueOn(string $date): static
    {
        return $this->state(fn (): array => [
            'remind_on' => $date,
        ]);
    }
}
