<?php

namespace App\Http\Controllers\Admin\Roadmap;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRoadmapItemRequest;
use App\Services\RoadmapService;
use Illuminate\Http\RedirectResponse;

class UpdateRoadmapItemController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(UpdateRoadmapItemRequest $request, int $roadmapItem): RedirectResponse
    {
        $this->roadmap->updateItem($roadmapItem, $request->formData());

        return redirect()->route('admin.roadmap.index');
    }
}
