<?php

namespace App\Http\Controllers;

use App\Services\RoadmapService;
use Illuminate\View\View;

class RoadmapController extends Controller
{
    public function __construct(private RoadmapService $roadmap) {}

    public function __invoke(): View
    {
        return view('roadmap', [
            'phases' => $this->roadmap->visiblePhases(),
        ]);
    }
}
