<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\AuthRepository;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Tests\TestCase;

class AuthRepositoryTest extends TestCase
{
    public function test_auth_repository_creates_and_updates_user_from_google_profile(): void
    {
        $socialiteUser = new class implements SocialiteUser
        {
            public function getId()
            {
                return 'google-123';
            }

            public function getNickname()
            {
                return 'serg';
            }

            public function getName()
            {
                return 'Serg';
            }

            public function getEmail()
            {
                return 'serg@example.com';
            }

            public function getAvatar()
            {
                return 'https://cdn.example.com/a.png';
            }
        };

        $repository = app(AuthRepository::class);
        $user = $repository->findOrCreateFromGoogleUser($socialiteUser);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('google-123', $user->google_id);
        $this->assertSame('serg@example.com', $user->email);
        $this->assertSame(9, $user->categories()->count());

        $updatedSocialiteUser = new class implements SocialiteUser
        {
            public function getId()
            {
                return 'google-123';
            }

            public function getNickname()
            {
                return 'serg';
            }

            public function getName()
            {
                return 'Serg Updated';
            }

            public function getEmail()
            {
                return 'serg@example.com';
            }

            public function getAvatar()
            {
                return 'https://cdn.example.com/new.png';
            }
        };

        $updatedUser = $repository->findOrCreateFromGoogleUser($updatedSocialiteUser);

        $this->assertSame($user->id, $updatedUser->id);
        $this->assertSame('Serg Updated', $updatedUser->name);
        $this->assertSame('https://cdn.example.com/new.png', $updatedUser->avatar_url);
        $this->assertSame(9, $updatedUser->categories()->count());
    }
}
