<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    /**
     * @return Collection<int, Category>
     */
    public function listForUser(User $user): Collection
    {
        return Category::query()
            ->ownedBy($user)
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
