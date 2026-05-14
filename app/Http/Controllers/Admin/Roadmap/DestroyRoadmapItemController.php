<?php

namespace App\Http\Controllers\Admin\Roadmap;

use App\Http\Controllers\Controller;
use App\Services\RoadmapService;
use Illuminate\Http\RedirectResponse;

class DestroyRoadmapItemController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(int $roadmapItem): RedirectResponse
    {
        $this->roadmap->deleteItem($roadmapItem);

        return redirect()->route('admin.roadmap.index');
    }
}
