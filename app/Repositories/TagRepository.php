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

    /**
     * @return Collection<int, Tag>
     */
    public function listForUserWithDocumentCounts(User $user): Collection
    {
        return Tag::query()
            ->ownedBy($user)
            ->withCount('documents')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @param  array{name: string, slug: string}  $attributes
     */
    public function createForUser(User $user, array $attributes): Tag
    {
        return Tag::query()->create([
            'user_id' => $user->id,
            ...$attributes,
        ]);
    }

    /**
     * @param  array{name: string, slug: string}  $attributes
     */
    public function update(Tag $tag, array $attributes): Tag
    {
        $tag->update($attributes);

        return $tag;
    }

    public function delete(Tag $tag): void
    {
        $tag->delete();
    }

    public function slugExistsForUser(User $user, string $slug, ?Tag $ignore = null): bool
    {
        return Tag::query()
            ->ownedBy($user)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->where('slug', $slug)
            ->exists();
    }
}
