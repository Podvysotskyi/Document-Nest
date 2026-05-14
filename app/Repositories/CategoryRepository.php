<?php

namespace App\Repositories;

use App\DTOs\StoreCategoryData;
use App\DTOs\UpdateCategoryData;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

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

    public function createForUser(User $user, StoreCategoryData $data): Category
    {
        return Category::query()->create([
            'user_id' => $user->id,
            'name' => $data->name,
            'slug' => $this->generateUniqueSlug($user, $data->name),
        ]);
    }

    public function update(Category $category, UpdateCategoryData $data): Category
    {
        $category->update([
            'name' => $data->name,
            'slug' => $this->generateUniqueSlug($category->user, $data->name, $category),
        ]);

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    private function generateUniqueSlug(User $user, string $name, ?Category $ignore = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug === '' ? 'category' : $baseSlug;
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->slugExistsForUser($user, $slug, $ignore)) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function slugExistsForUser(User $user, string $slug, ?Category $ignore = null): bool
    {
        return Category::query()
            ->ownedBy($user)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->where('slug', $slug)
            ->exists();
    }
}
