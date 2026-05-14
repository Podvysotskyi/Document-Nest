<?php

namespace App\Http\Controllers\Admin\Roadmap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MoveRoadmapRequest;
use App\Services\RoadmapService;
use Illuminate\Http\RedirectResponse;

class MoveRoadmapItemController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(MoveRoadmapRequest $request, int $roadmapItem): RedirectResponse
    {
        $this->roadmap->moveItem($roadmapItem, $request->direction());

        return redirect()->route('admin.roadmap.index');
    }
}
