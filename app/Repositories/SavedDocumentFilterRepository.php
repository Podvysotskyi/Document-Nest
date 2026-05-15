<?php

namespace App\Repositories;

use App\Models\SavedDocumentFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SavedDocumentFilterRepository
{
    /**
     * @return Collection<int, SavedDocumentFilter>
     */
    public function listForUser(User $user): Collection
    {
        return SavedDocumentFilter::query()
            ->ownedBy($user)
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  array{name: string, filters: array<string, mixed>, sort: ?string, direction: ?string, is_default: bool}  $attributes
     */
    public function createForUser(User $user, array $attributes): SavedDocumentFilter
    {
        return SavedDocumentFilter::query()->create([
            'user_id' => $user->id,
            ...$attributes,
        ]);
    }

    public function delete(SavedDocumentFilter $savedDocumentFilter): void
    {
        $savedDocumentFilter->delete();
    }

    public function clearDefaultsForUser(User $user, ?SavedDocumentFilter $except = null): void
    {
        SavedDocumentFilter::query()
            ->ownedBy($user)
            ->where('is_default', true)
            ->when($except, fn ($query) => $query->whereKeyNot($except->getKey()))
            ->update(['is_default' => false]);
    }

    /**
     * @param  array{name: string, filters: array<string, mixed>, sort: ?string, direction: ?string, is_default: bool}  $attributes
     */
    public function update(SavedDocumentFilter $savedDocumentFilter, array $attributes): SavedDocumentFilter
    {
        $savedDocumentFilter->update($attributes);

        return $savedDocumentFilter;
    }
}
