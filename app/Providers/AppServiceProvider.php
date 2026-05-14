<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use App\Observers\UserObserver;
use App\Policies\CategoryPolicy;
use App\Policies\DocumentPolicy;
use App\Policies\TagPolicy;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->ensureRoadmapDatabaseExists();

        User::observe(UserObserver::class);

        Gate::policy(Document::class, DocumentPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);

        Gate::define('access-admin', fn (User $user): bool => $user->hasRole(UserRole::Admin));
    }

    private function ensureRoadmapDatabaseExists(): void
    {
        $databasePath = config('database.connections.roadmap.database');

        if (! is_string($databasePath) || $databasePath === ':memory:') {
            return;
        }

        File::ensureDirectoryExists(dirname($databasePath));

        if (! File::exists($databasePath)) {
            File::put($databasePath, '');
        }
    }
}
