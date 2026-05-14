<?php

namespace App\Repositories;

use App\DTOs\DocumentFiltersData;
use App\DTOs\StoreDocumentData;
use App\DTOs\UpdateDocumentData;
use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentRepository
{
    public function paginateForUser(User $user, DocumentFiltersData $filters): LengthAwarePaginator
    {
        $query = Document::query()
            ->ownedBy($user)
            ->with(['category:id,name', 'tags:id,name'])
            ->when($filters->query, function ($builder, string $search): void {
                $builder->where(function ($inner) use ($search): void {
                    $inner->where('title', 'ilike', "%{$search}%")
                        ->orWhere('notes', 'ilike', "%{$search}%")
                        ->orWhere('original_filename', 'ilike', "%{$search}%");
                });
            })
            ->when($filters->categoryId, fn ($builder, string $id) => $builder->where('category_id', $id))
            ->when($filters->status, fn ($builder, DocumentStatus $status) => $builder->where('status', $status))
            ->when($filters->tagId, fn ($builder, string $tagId) => $builder->whereHas('tags', fn ($tagQuery) => $tagQuery->where('tags.id', $tagId)))
            ->when($filters->expiryFrom, fn ($builder, string $date) => $builder->whereDate('expiry_date', '>=', $date))
            ->when($filters->expiryTo, fn ($builder, string $date) => $builder->whereDate('expiry_date', '<=', $date));

        match ($filters->sort) {
            'oldest' => $query->orderBy('created_at'),
            'title' => $query->orderBy('title'),
            'expiry_date' => $query->orderBy('expiry_date')->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at'),
        };

        return $query->paginate(12)->withQueryString();
    }

    public function createForUser(User $user, StoreDocumentData $data): Document
    {
        $storedPath = $data->file->store('documents/'.$user->id, 'local');

        $document = Document::query()->create([
            'user_id' => $user->id,
            'category_id' => $data->categoryId,
            'title' => $data->title,
            'notes' => $data->notes,
            'status' => $data->status ?? DocumentStatus::Active,
            'issue_date' => $data->issueDate,
            'expiry_date' => $data->expiryDate,
            'original_filename' => $data->file->getClientOriginalName(),
            'stored_path' => $storedPath,
            'mime_type' => $data->file->getMimeType() ?? 'application/octet-stream',
            'file_size' => $data->file->getSize(),
        ]);

        $document->tags()->sync($data->tagIds);

        return $document;
    }

    public function update(Document $document, UpdateDocumentData $data): Document
    {
        $document->update([
            'title' => $data->title,
            'category_id' => $data->categoryId,
            'notes' => $data->notes,
            'status' => $data->status,
            'issue_date' => $data->issueDate,
            'expiry_date' => $data->expiryDate,
            'archived_at' => $data->status === DocumentStatus::Archived ? now() : null,
        ]);

        $document->tags()->sync($data->tagIds);

        return $document;
    }

    public function loadForShow(Document $document): Document
    {
        return $document->load(['category:id,name', 'tags:id,name']);
    }

    public function archive(Document $document): Document
    {
        $document->update([
            'status' => DocumentStatus::Archived,
            'archived_at' => now(),
        ]);

        return $document;
    }

    public function restore(Document $document): Document
    {
        $document->update([
            'status' => DocumentStatus::Active,
            'archived_at' => null,
        ]);

        return $document;
    }

    public function delete(Document $document): void
    {
        Storage::disk('local')->delete($document->stored_path);
        $document->delete();
    }

    public function streamPreview(Document $document): StreamedResponse
    {
        return Storage::disk('local')->response(
            $document->stored_path,
            headers: ['Content-Type' => $document->mime_type],
        );
    }

    public function streamDownload(Document $document): StreamedResponse
    {
        return Storage::disk('local')->download($document->stored_path, $document->original_filename);
    }
}
