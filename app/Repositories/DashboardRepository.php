<?php

namespace App\Repositories;

use App\Models\Document;
use App\Models\User;

class DashboardRepository
{
    /**
     * @return array<string, mixed>
     */
    public function buildForUser(User $user): array
    {
        $documentsQuery = Document::query()->ownedBy($user);

        $expiringSoon = (clone $documentsQuery)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->whereDate('expiry_date', '<=', now()->addDays(30)->toDateString())
            ->orderBy('expiry_date')
            ->limit(8)
            ->get(['id', 'title', 'expiry_date', 'status', 'category_id']);

        $missingExpiry = (clone $documentsQuery)
            ->whereNull('expiry_date')
            ->latest()
            ->limit(8)
            ->get(['id', 'title', 'status', 'category_id', 'updated_at']);

        $recentUploads = (clone $documentsQuery)
            ->latest()
            ->limit(8)
            ->get(['id', 'title', 'status', 'created_at', 'category_id']);

        $documentsByCategory = (clone $documentsQuery)
            ->leftJoin('categories', 'documents.category_id', '=', 'categories.id')
            ->selectRaw("coalesce(categories.name, 'Uncategorized') as category_name, count(*) as total")
            ->groupBy('category_name')
            ->orderByDesc('total')
            ->get();

        return [
            'stats' => [
                'total_documents' => (clone $documentsQuery)->count(),
                'expiring_soon_count' => (clone $documentsQuery)
                    ->whereNotNull('expiry_date')
                    ->whereDate('expiry_date', '>=', now()->toDateString())
                    ->whereDate('expiry_date', '<=', now()->addDays(30)->toDateString())
                    ->count(),
                'missing_expiry_count' => (clone $documentsQuery)->whereNull('expiry_date')->count(),
            ],
            'recent_uploads' => $recentUploads,
            'expiring_soon' => $expiringSoon,
            'missing_expiry' => $missingExpiry,
            'documents_by_category' => $documentsByCategory,
        ];
    }
}
