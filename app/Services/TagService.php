<?php

namespace App\Services;

use App\DTOs\StoreTagData;
use App\DTOs\UpdateTagData;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\TagRepository;
use Illuminate\Database\Eloquent\Collection;

class TagService
{
    public function __construct(
        private TagRepository $tagRepository,
        private SlugGenerator $slugGenerator,
    ) {}

    /**
     * @return Collection<int, Tag>
     */
    public function listForUser(User $user): Collection
    {
        return $this->tagRepository->listForUser($user);
    }

    /**
     * @return Collection<int, Tag>
     */
    public function listForUserWithDocumentCounts(User $user): Collection
    {
        return $this->tagRepository->listForUserWithDocumentCounts($user);
    }

    public function createForUser(User $user, StoreTagData $data): Tag
    {
        $slug = $this->slugGenerator->generateUnique(
            $data->name,
            fn (string $slug): bool => $this->tagRepository->slugExistsForUser($user, $slug),
            'tag',
        );

        return $this->tagRepository->createForUser($user, [
            'name' => $data->name,
            'slug' => $slug,
        ]);
    }

    public function update(Tag $tag, UpdateTagData $data): Tag
    {
        $slug = $this->slugGenerator->generateUnique(
            $data->name,
            fn (string $slug): bool => $this->tagRepository->slugExistsForUser($tag->user, $slug, $tag),
            'tag',
        );

        return $this->tagRepository->update($tag, [
            'name' => $data->name,
            'slug' => $slug,
        ]);
    }

    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }
}
