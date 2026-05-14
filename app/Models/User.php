<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'google_id', 'avatar_url'])]
#[Hidden(['remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable;

    public const DEFAULT_CATEGORY_NAMES = [
        'Finance',
        'Health',
        'Identity',
        'Home',
        'Vehicle',
        'Work',
        'Education',
        'Legal',
        'Other',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function ensureDefaultCategories(): void
    {
        $defaultCategories = collect(self::DEFAULT_CATEGORY_NAMES)
            ->map(fn (string $name): array => [
                'user_id' => $this->id,
                'name' => $name,
                'slug' => Str::slug($name),
            ])
            ->all();

        Category::query()->upsert(
            $defaultCategories,
            uniqueBy: ['user_id', 'slug'],
            update: ['name'],
        );
    }
}
