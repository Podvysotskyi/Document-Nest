<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Events\Documents\DocumentArchived;
use App\Events\Documents\DocumentCreated;
use App\Events\Documents\DocumentDeleted;
use App\Events\Documents\DocumentDownloaded;
use App\Events\Documents\DocumentReminderSent;
use App\Events\Documents\DocumentRestored;
use App\Events\Documents\DocumentsBulkActionCompleted;
use App\Events\Documents\DocumentUpdated;
use App\Listeners\CreateDocumentActivity;
use App\Models\Category;
use App\Models\Document;
use App\Models\SavedDocumentFilter;
use App\Models\Tag;
use App\Models\User;
use App\Observers\UserObserver;
use App\Policies\CategoryPolicy;
use App\Policies\DocumentPolicy;
use App\Policies\SavedDocumentFilterPolicy;
use App\Policies\TagPolicy;
use Illuminate\Support\Facades\Event;
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
        User::observe(UserObserver::class);

        Gate::policy(Document::class, DocumentPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);
        Gate::policy(SavedDocumentFilter::class, SavedDocumentFilterPolicy::class);

        Gate::define('access-admin', fn (User $user): bool => $user->hasRole(UserRole::Admin));

        $this->registerDocumentActivityListeners();
    }

    private function registerDocumentActivityListeners(): void
    {
        $events = [
            DocumentCreated::class,
            DocumentUpdated::class,
            DocumentArchived::class,
            DocumentRestored::class,
            DocumentDeleted::class,
            DocumentDownloaded::class,
            DocumentReminderSent::class,
            DocumentsBulkActionCompleted::class,
        ];

        foreach ($events as $event) {
            Event::listen($event, CreateDocumentActivity::class);
        }
    }
}
