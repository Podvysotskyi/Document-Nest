<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository
{
    public function findByGoogleIdOrEmail(string $googleId, string $email): ?User
    {
        return User::query()
            ->where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): User
    {
        return User::query()->create($attributes);
    }

    public function updateAvatarUrl(User $user, string $url): void
    {
        $user->forceFill(['avatar_url' => $url])->save();
    }

    public function updateGoogleId(User $user, string $googleId): void
    {
        $user->forceFill(['google_id' => $googleId])->save();
    }

    public function isFirstUser(): bool
    {
        return User::query()->count() === 1;
    }

    /**
     * @param  array<int, int>  $roleIds
     */
    public function syncRoles(User $user, array $roleIds): void
    {
        $user->roles()->sync($roleIds);
    }
}
