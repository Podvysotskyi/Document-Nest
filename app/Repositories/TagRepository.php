<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TagRepository
{
    /**
     * @return Collection<int, Tag>
     */
    public function listForUser(User $user): Collection
    {
        return Tag::query()
            ->ownedBy($user)
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
