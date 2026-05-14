<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private DashboardRepository $dashboardRepository) {}

    public function __invoke(Request $request): Response
    {
        return Inertia::render('Dashboard', $this->dashboardRepository->buildForUser($request->user()));
    }
}
