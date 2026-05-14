<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRolesRequest;
use App\Models\User;
use App\Services\AdminUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class UpdateUserRolesController extends Controller
{
    public function __construct(private AdminUserService $adminUserService) {}

    public function __invoke(UpdateUserRolesRequest $request, User $user): RedirectResponse
    {
        if ($request->triesToRemoveOwnAdminAccess()) {
            throw ValidationException::withMessages([
                'role_ids' => 'You cannot remove your own admin access.',
            ]);
        }

        $this->adminUserService->syncRoles($user, $request->requestedRoleIds());

        return redirect()->route('admin.users.index');
    }
}
