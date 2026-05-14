<?php

namespace App\Repositories;

use App\DTOs\DocumentFiltersData;
use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentRepository
{
    public function paginateForUser(User $user, DocumentFiltersData $filters): LengthAwarePaginator
    {
        $query = Document::query()
            ->ownedBy($user)
            ->with(['category:id,name', 'tags:id,name'])
            ->when($filters->query, function ($builder, string $search): void {
                $like = config('database.default') === 'pgsql' ? 'ilike' : 'like';
                $builder->where(function ($inner) use ($search, $like): void {
                    $inner->where('documents.title', $like, "%{$search}%")
                        ->orWhere('documents.notes', $like, "%{$search}%")
                        ->orWhere('documents.original_filename', $like, "%{$search}%");
                });
            })
            ->when($filters->uncategorizedOnly, fn ($builder) => $builder->whereNull('documents.category_id'))
            ->when($filters->categoryId, fn ($builder, string $id) => $builder->where('documents.category_id', $id))
            ->when($filters->status, fn ($builder, DocumentStatus $status) => $builder->where('documents.status', $status))
            ->when($filters->tagId, fn ($builder, string $tagId) => $builder->whereHas('tags', fn ($tagQuery) => $tagQuery->where('tags.id', $tagId)))
            ->when($filters->expiryFrom, fn ($builder, string $date) => $builder->whereDate('documents.expiry_date', '>=', $date))
            ->when($filters->expiryTo, fn ($builder, string $date) => $builder->whereDate('documents.expiry_date', '<=', $date));

        if ($filters->sort && $filters->direction) {
            match ($filters->sort) {
                'title' => $query->orderBy('documents.title', $filters->direction),
                'expiry_date' => $query->orderBy('documents.expiry_date', $filters->direction)->orderByDesc('documents.created_at'),
                'status' => $query->orderBy('documents.status', $filters->direction),
                'category' => $query->leftJoin('categories', 'documents.category_id', '=', 'categories.id')
                    ->select('documents.*')
                    ->orderBy('categories.name', $filters->direction),
                'newest' => $query->orderByDesc('documents.created_at'),
                'oldest' => $query->orderBy('documents.created_at'),
                default => $query->orderBy('documents.created_at', $filters->direction),
            };
        } else {
            match ($filters->sort) {
                'oldest' => $query->orderBy('documents.created_at'),
                'title' => $query->orderBy('documents.title'),
                'expiry_date' => $query->orderBy('documents.expiry_date')->orderByDesc('documents.created_at'),
                'category' => $query->leftJoin('categories', 'documents.category_id', '=', 'categories.id')
                    ->select('documents.*')
                    ->orderBy('categories.name'),
                default => $query->orderByDesc('documents.created_at'),
            };
        }

        return $query->paginate(12)->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Document
    {
        return Document::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Document $document, array $attributes): Document
    {
        $document->update($attributes);

        return $document;
    }

    /**
     * @param  array<int, string>  $tagIds
     */
    public function syncTags(Document $document, array $tagIds): void
    {
        $document->tags()->sync($tagIds);
    }

    public function loadForShow(Document $document): Document
    {
        return $document->load(['category:id,name', 'tags:id,name']);
    }

    public function delete(Document $document): void
    {
        $document->delete();
    }
}
