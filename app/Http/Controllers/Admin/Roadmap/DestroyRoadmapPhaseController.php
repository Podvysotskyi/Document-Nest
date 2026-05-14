<?php

namespace App\Http\Controllers\Admin\Roadmap;

use App\Http\Controllers\Controller;
use App\Services\RoadmapService;
use Illuminate\Http\RedirectResponse;

class DestroyRoadmapPhaseController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(int $roadmapPhase): RedirectResponse
    {
        $this->roadmap->deletePhase($roadmapPhase);

        return redirect()->route('admin.roadmap.index');
    }
}
