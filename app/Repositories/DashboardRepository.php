<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class DashboardRepository
{
    public function totalDocuments(User $user): int
    {
        return Document::query()->ownedBy($user)->count();
    }

    public function expiringSoonCount(User $user): int
    {
        return Document::query()
            ->ownedBy($user)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->whereDate('expiry_date', '<=', now()->addDays(30)->toDateString())
            ->count();
    }

    public function missingExpiryCount(User $user): int
    {
        return Document::query()->ownedBy($user)->whereNull('expiry_date')->count();
    }

    /**
     * @return Collection<int, Document>
     */
    public function recentUploads(User $user): Collection
    {
        return Document::query()
            ->ownedBy($user)
            ->with('category:id,name')
            ->latest()
            ->limit(8)
            ->get(['id', 'title', 'status', 'created_at', 'category_id']);
    }

    /**
     * @return Collection<int, Document>
     */
    public function expiringSoon(User $user): Collection
    {
        return Document::query()
            ->ownedBy($user)
            ->with('category:id,name')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->whereDate('expiry_date', '<=', now()->addDays(30)->toDateString())
            ->orderBy('expiry_date')
            ->limit(8)
            ->get(['id', 'title', 'expiry_date', 'status', 'category_id']);
    }

    /**
     * @return Collection<int, Document>
     */
    public function missingExpiry(User $user): Collection
    {
        return Document::query()
            ->ownedBy($user)
            ->whereNull('expiry_date')
            ->latest()
            ->limit(8)
            ->get(['id', 'title', 'status', 'category_id', 'updated_at']);
    }

    /**
     * @return Collection<int, Category>
     */
    public function documentsByCategory(User $user): Collection
    {
        return Category::query()
            ->ownedBy($user)
            ->withCount('documents')
            ->orderByDesc('documents_count')
            ->get(['id', 'name']);
    }

    public function uncategorizedCount(User $user): int
    {
        return Document::query()->ownedBy($user)->whereNull('category_id')->count();
    }
}
