<?php

namespace App\Repositories;

use App\DTOs\StoreTagData;
use App\DTOs\UpdateTagData;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

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

    public function createForUser(User $user, StoreTagData $data): Tag
    {
        return Tag::query()->create([
            'user_id' => $user->id,
            'name' => $data->name,
            'slug' => $this->generateUniqueSlug($user, $data->name),
        ]);
    }

    public function update(Tag $tag, UpdateTagData $data): Tag
    {
        $tag->update([
            'name' => $data->name,
            'slug' => $this->generateUniqueSlug($tag->user, $data->name, $tag),
        ]);

        return $tag;
    }

    public function delete(Tag $tag): void
    {
        $tag->delete();
    }

    private function generateUniqueSlug(User $user, string $name, ?Tag $ignore = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug === '' ? 'tag' : $baseSlug;
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->slugExistsForUser($user, $slug, $ignore)) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function slugExistsForUser(User $user, string $slug, ?Tag $ignore = null): bool
    {
        return Tag::query()
            ->ownedBy($user)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->where('slug', $slug)
            ->exists();
    }
}
