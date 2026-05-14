<?php

namespace App\Http\Controllers\Admin\Roadmap;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminRoadmapPhaseResource;
use App\Services\RoadmapService;
use Inertia\Inertia;
use Inertia\Response;

class IndexRoadmapController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(): Response
    {
        return Inertia::render('Admin/Roadmap/Index', [
            'phases' => AdminRoadmapPhaseResource::collection(
                $this->roadmap->allPhasesForAdmin()
            )->resolve(),
        ]);
    }
}
