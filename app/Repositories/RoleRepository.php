<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    /**
     * @return Collection<int, Role>
     */
    public function listAll(): Collection
    {
        return Role::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
