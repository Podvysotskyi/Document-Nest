<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardCategoryCountResource;
use App\Http\Resources\DocumentListResource;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $documentsByCategory = DashboardCategoryCountResource::collection(
            $this->dashboardService->documentsByCategory($user)
        )->resolve();

        $uncategorizedCount = $this->dashboardService->uncategorizedCount($user);

        if ($uncategorizedCount > 0) {
            $documentsByCategory[] = [
                'category_name' => 'Uncategorized',
                'total' => $uncategorizedCount,
            ];
        }

        return Inertia::render('Dashboard', [
            'stats' => $this->dashboardService->stats($user),
            'recent_uploads' => DocumentListResource::collection($this->dashboardService->recentUploads($user))->resolve(),
            'expiring_soon' => DocumentListResource::collection($this->dashboardService->expiringSoon($user))->resolve(),
            'missing_expiry' => DocumentListResource::collection($this->dashboardService->missingExpiry($user))->resolve(),
            'documents_by_category' => $documentsByCategory,
        ]);
    }
}
