<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\AuthRepository;

class UserOnboardingService
{
    public function __construct(private AuthRepository $authRepository) {}

    public function onboard(User $user): void
    {
        if ($this->authRepository->isFirstUser()) {
            $this->authRepository->syncRoles($user, [
                UserRole::Admin->value,
                UserRole::Developer->value,
            ]);
        }
    }
}
