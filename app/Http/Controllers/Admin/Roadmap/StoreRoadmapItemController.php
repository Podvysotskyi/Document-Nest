<?php

namespace App\Http\Controllers\Admin\Roadmap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoadmapItemRequest;
use App\Services\RoadmapService;
use Illuminate\Http\RedirectResponse;

class StoreRoadmapItemController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(StoreRoadmapItemRequest $request, int $roadmapPhase): RedirectResponse
    {
        $this->roadmap->createItem($roadmapPhase, $request->formData());

        return redirect()->route('admin.roadmap.index');
    }
}
