<?php

namespace Tests\Unit\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_auth_service_creates_and_updates_user_from_google_profile(): void
    {
        Storage::fake('public');
        Http::fake([
            'cdn.example.com/*' => Http::response('fake-image-bytes', 200, ['Content-Type' => 'image/png']),
        ]);

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

        $service = app(AuthService::class);
        $user = $service->findOrCreateFromGoogleUser($socialiteUser);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('google-123', $user->google_id);
        $this->assertSame('serg@example.com', $user->email);
        $this->assertSame(9, $user->categories()->count());

        $expectedPath = "users/{$user->id}.png";
        Storage::disk('public')->assertExists($expectedPath);
        $this->assertSame(Storage::disk('public')->url($expectedPath), $user->avatar_url);

        $this->assertTrue($user->hasRole(UserRole::Admin));
        $this->assertTrue($user->hasRole(UserRole::Developer));

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

        $updatedUser = $service->findOrCreateFromGoogleUser($updatedSocialiteUser);

        $this->assertSame($user->id, $updatedUser->id);
        $this->assertSame('Serg', $updatedUser->name);
        $this->assertSame($user->avatar_url, $updatedUser->avatar_url);
        $this->assertSame(9, $updatedUser->categories()->count());
    }

    public function test_only_the_first_user_receives_admin_and_developer_roles(): void
    {
        Storage::fake('public');
        Http::fake([
            'cdn.example.com/*' => Http::response('fake-image-bytes', 200, ['Content-Type' => 'image/png']),
        ]);

        $service = app(AuthService::class);

        $first = $service->findOrCreateFromGoogleUser($this->makeSocialiteUser('google-1', 'first@example.com'));
        $second = $service->findOrCreateFromGoogleUser($this->makeSocialiteUser('google-2', 'second@example.com'));

        $this->assertTrue($first->hasRole(UserRole::Admin));
        $this->assertTrue($first->hasRole(UserRole::Developer));

        $this->assertFalse($second->hasRole(UserRole::Admin));
        $this->assertFalse($second->hasRole(UserRole::Developer));
    }

    private function makeSocialiteUser(string $id, string $email): SocialiteUser
    {
        return new class($id, $email) implements SocialiteUser
        {
            public function __construct(private string $id, private string $email) {}

            public function getId()
            {
                return $this->id;
            }

            public function getNickname()
            {
                return null;
            }

            public function getName()
            {
                return 'Test User';
            }

            public function getEmail()
            {
                return $this->email;
            }

            public function getAvatar()
            {
                return 'https://cdn.example.com/avatar.png';
            }
        };
    }
}
