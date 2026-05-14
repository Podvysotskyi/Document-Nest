<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthService
{
    public function __construct(
        private AuthRepository $authRepository,
        private UserAvatarService $avatarService,
        private UserOnboardingService $onboardingService,
    ) {}

    public function findOrCreateFromGoogleUser(SocialiteUser $googleUser): User
    {
        $existing = $this->authRepository->findByGoogleIdOrEmail(
            $googleUser->getId(),
            $googleUser->getEmail(),
        );

        if ($existing !== null) {
            $this->authRepository->updateGoogleId($existing, $googleUser->getId());

            return $existing;
        }

        $user = $this->authRepository->create([
            'google_id' => $googleUser->getId(),
            'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? $googleUser->getEmail(),
            'email' => $googleUser->getEmail(),
        ]);

        $avatarUrl = $this->avatarService->storeFromUrl($googleUser->getAvatar(), $user->id);

        if ($avatarUrl !== null) {
            $this->authRepository->updateAvatarUrl($user, $avatarUrl);
        }

        $this->onboardingService->onboard($user);

        return $user;
    }
}
