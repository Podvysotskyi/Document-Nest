<?php

namespace App\Http\Controllers\Admin\Roadmap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoadmapPhaseRequest;
use App\Services\RoadmapService;
use Illuminate\Http\RedirectResponse;

class StoreRoadmapPhaseController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(StoreRoadmapPhaseRequest $request): RedirectResponse
    {
        $this->roadmap->createPhase($request->formData());

        return redirect()->route('admin.roadmap.index');
    }
}
