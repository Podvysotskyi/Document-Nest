<?php

use App\Models\User;
use App\Repositories\AuthRepository;
use Laravel\Socialite\Contracts\User as SocialiteUser;

test('auth repository creates and updates user from google profile', function () {
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

    expect($user)->toBeInstanceOf(User::class);
    expect($user->google_id)->toBe('google-123');
    expect($user->email)->toBe('serg@example.com');
    expect($user->categories()->count())->toBe(9);

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

    expect($updatedUser->id)->toBe($user->id);
    expect($updatedUser->name)->toBe('Serg Updated');
    expect($updatedUser->avatar_url)->toBe('https://cdn.example.com/new.png');
    expect($updatedUser->categories()->count())->toBe(9);
});
