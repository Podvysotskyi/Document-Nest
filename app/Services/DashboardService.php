<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use App\Repositories\DashboardRepository;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function __construct(private DashboardRepository $dashboardRepository) {}

    /**
     * @return array{total_documents: int, expiring_soon_count: int, missing_expiry_count: int}
     */
    public function stats(User $user): array
    {
        return [
            'total_documents' => $this->dashboardRepository->totalDocuments($user),
            'expiring_soon_count' => $this->dashboardRepository->expiringSoonCount($user),
            'missing_expiry_count' => $this->dashboardRepository->missingExpiryCount($user),
        ];
    }

    /**
     * @return Collection<int, Document>
     */
    public function recentUploads(User $user): Collection
    {
        return $this->dashboardRepository->recentUploads($user);
    }

    /**
     * @return Collection<int, Document>
     */
    public function expiringSoon(User $user): Collection
    {
        return $this->dashboardRepository->expiringSoon($user);
    }

    /**
     * @return Collection<int, Document>
     */
    public function missingExpiry(User $user): Collection
    {
        return $this->dashboardRepository->missingExpiry($user);
    }

    /**
     * @return Collection<int, Category>
     */
    public function documentsByCategory(User $user): Collection
    {
        return $this->dashboardRepository->documentsByCategory($user);
    }

    public function uncategorizedCount(User $user): int
    {
        return $this->dashboardRepository->uncategorizedCount($user);
    }
}
