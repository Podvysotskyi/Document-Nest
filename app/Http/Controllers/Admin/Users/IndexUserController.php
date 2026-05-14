<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminUserResource;
use App\Services\AdminUserService;
use Inertia\Inertia;
use Inertia\Response;

class IndexUserController extends Controller
{
    public function __construct(private AdminUserService $adminUserService) {}

    public function __invoke(): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => AdminUserResource::collection(
                $this->adminUserService->paginateWithDocumentCounts()
            )->response()->getData(true),
            'roles' => $this->adminUserService->availableRoles()->all(),
        ]);
    }
}
