<?php

namespace App\Repositories;

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthRepository
{
    public function findOrCreateFromGoogleUser(SocialiteUser $googleUser): User
    {
        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user === null) {
            $user = User::query()->create([
                'google_id' => $googleUser->getId(),
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? $googleUser->getEmail(),
                'email' => $googleUser->getEmail(),
                'avatar_url' => $googleUser->getAvatar(),
            ]);
        }

        $user->forceFill([
            'google_id' => $googleUser->getId(),
            'name' => $googleUser->getName() ?? $user->name,
            'avatar_url' => $googleUser->getAvatar(),
        ])->save();

        $user->ensureDefaultCategories();

        return $user;
    }
}
