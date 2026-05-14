<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminUserRepository
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function paginateWithDocumentCounts(): LengthAwarePaginator
    {
        return User::query()
            ->with('roles')
            ->withCount('documents')
            ->orderBy('name')
            ->paginate(15);
    }

    /**
     * @param  array<int, string>  $roleIds
     */
    public function syncRoles(User $user, array $roleIds): User
    {
        $user->roles()->sync($roleIds);

        return $user->load('roles');
    }
}
