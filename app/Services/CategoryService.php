<?php

namespace App\Services;

use App\DTOs\StoreCategoryData;
use App\DTOs\UpdateCategoryData;
use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private SlugGenerator $slugGenerator,
    ) {}

    /**
     * @return Collection<int, Category>
     */
    public function listForUser(User $user, bool $onlyWithDocuments = false): Collection
    {
        return $this->categoryRepository->listForUser($user, $onlyWithDocuments);
    }

    /**
     * @return Collection<int, Category>
     */
    public function listForUserWithDocumentCounts(User $user): Collection
    {
        return $this->categoryRepository->listForUserWithDocumentCounts($user);
    }

    public function createForUser(User $user, StoreCategoryData $data): Category
    {
        $slug = $this->slugGenerator->generateUnique(
            $data->name,
            fn (string $slug): bool => $this->categoryRepository->slugExistsForUser($user, $slug),
            'category',
        );

        return $this->categoryRepository->createForUser($user, [
            'name' => $data->name,
            'slug' => $slug,
        ]);
    }

    public function update(Category $category, UpdateCategoryData $data): Category
    {
        $slug = $this->slugGenerator->generateUnique(
            $data->name,
            fn (string $slug): bool => $this->categoryRepository->slugExistsForUser($category->user, $slug, $category),
            'category',
        );

        return $this->categoryRepository->update($category, [
            'name' => $data->name,
            'slug' => $slug,
        ]);
    }

    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }
}
