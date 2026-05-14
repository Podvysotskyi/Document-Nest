<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'google_id' => (string) Str::uuid(),
            'avatar_url' => fake()->imageUrl(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->withRole(UserRole::Admin);
    }

    public function developer(): static
    {
        return $this->withRole(UserRole::Developer);
    }

    private function withRole(UserRole $role): static
    {
        return $this->afterCreating(function (User $user) use ($role): void {
            $user->roles()->syncWithoutDetaching([$role->value]);
        });
    }
}
