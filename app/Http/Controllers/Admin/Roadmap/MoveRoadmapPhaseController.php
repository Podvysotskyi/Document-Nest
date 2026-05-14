<?php

namespace App\Http\Controllers\Admin\Roadmap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MoveRoadmapRequest;
use App\Services\RoadmapService;
use Illuminate\Http\RedirectResponse;

class MoveRoadmapPhaseController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(MoveRoadmapRequest $request, int $roadmapPhase): RedirectResponse
    {
        $this->roadmap->movePhase($roadmapPhase, $request->direction());

        return redirect()->route('admin.roadmap.index');
    }
}
