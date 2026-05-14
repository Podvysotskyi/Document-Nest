<?php

namespace App\Http\Controllers\Admin\Roadmap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRoadmapPhaseRequest;
use App\Services\RoadmapService;
use Illuminate\Http\RedirectResponse;

class UpdateRoadmapPhaseController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(UpdateRoadmapPhaseRequest $request, int $roadmapPhase): RedirectResponse
    {
        $this->roadmap->updatePhase($roadmapPhase, $request->formData());

        return redirect()->route('admin.roadmap.index');
    }
}
