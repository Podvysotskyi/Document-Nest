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

    /**
     * @return Collection<int, Category>
     */
    public function listForUserWithDocumentCounts(User $user): Collection
    {
        return Category::query()
            ->ownedBy($user)
            ->withCount('documents')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @param  array{name: string, slug: string}  $attributes
     */
    public function createForUser(User $user, array $attributes): Category
    {
        return Category::query()->create([
            'user_id' => $user->id,
            ...$attributes,
        ]);
    }

    /**
     * @param  array{name: string, slug: string}  $attributes
     */
    public function update(Category $category, array $attributes): Category
    {
        $category->update($attributes);

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    public function slugExistsForUser(User $user, string $slug, ?Category $ignore = null): bool
    {
        return Category::query()
            ->ownedBy($user)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->where('slug', $slug)
            ->exists();
    }
}
