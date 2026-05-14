<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\AdminUserRepository;
use App\Repositories\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdminUserService
{
    public function __construct(
        private AdminUserRepository $adminUserRepository,
        private RoleRepository $roleRepository,
    ) {}

    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function paginateWithDocumentCounts(): LengthAwarePaginator
    {
        return $this->adminUserRepository->paginateWithDocumentCounts();
    }

    /**
     * @return Collection<int, Role>
     */
    public function availableRoles(): Collection
    {
        return $this->roleRepository->listAll();
    }

    /**
     * @param  array<int, int>  $roleIds
     */
    public function syncRoles(User $user, array $roleIds): User
    {
        return $this->adminUserRepository->syncRoles($user, $roleIds);
    }
}
