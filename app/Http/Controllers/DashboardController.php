<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardCategoryCountResource;
use App\Http\Resources\DocumentListResource;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private DashboardRepository $dashboardRepository) {}

    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $documentsByCategory = DashboardCategoryCountResource::collection(
            $this->dashboardRepository->documentsByCategory($user)
        )->resolve();

        $uncategorizedCount = $this->dashboardRepository->uncategorizedCount($user);

        if ($uncategorizedCount > 0) {
            $documentsByCategory[] = [
                'category_name' => 'Uncategorized',
                'total' => $uncategorizedCount,
            ];
        }

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_documents' => $this->dashboardRepository->totalDocuments($user),
                'expiring_soon_count' => $this->dashboardRepository->expiringSoonCount($user),
                'missing_expiry_count' => $this->dashboardRepository->missingExpiryCount($user),
            ],
            'recent_uploads' => DocumentListResource::collection($this->dashboardRepository->recentUploads($user))->resolve(),
            'expiring_soon' => DocumentListResource::collection($this->dashboardRepository->expiringSoon($user))->resolve(),
            'missing_expiry' => DocumentListResource::collection($this->dashboardRepository->missingExpiry($user))->resolve(),
            'documents_by_category' => $documentsByCategory,
        ]);
    }
}
