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
    public function listForUser(User $user, bool $onlyWithDocuments = false): Collection
    {
        $query = Category::query()
            ->ownedBy($user);

        if ($onlyWithDocuments) {
            $query->has('documents');
        }

        return $query->orderBy('name')
            ->get(['id', 'name']);
    }
}
