<?php

namespace App\Services;

use App\DTOs\DocumentFiltersData;
use App\DTOs\StoreDocumentData;
use App\DTOs\UpdateDocumentData;
use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use App\Repositories\DocumentRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentService
{
    public function __construct(
        private DocumentRepository $documentRepository,
        private DocumentStorageService $storage,
    ) {}

    public function paginateForUser(User $user, DocumentFiltersData $filters): LengthAwarePaginator
    {
        return $this->documentRepository->paginateForUser($user, $filters);
    }

    public function loadForShow(Document $document): Document
    {
        return $this->documentRepository->loadForShow($document);
    }

    public function createForUser(User $user, StoreDocumentData $data): Document
    {
        $storedPath = $this->storage->store($data->file, $user->id);

        $document = $this->documentRepository->create([
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

        $this->documentRepository->syncTags($document, $data->tagIds);

        return $document;
    }

    public function update(Document $document, UpdateDocumentData $data): Document
    {
        $document = $this->documentRepository->update($document, [
            'title' => $data->title,
            'category_id' => $data->categoryId,
            'notes' => $data->notes,
            'status' => $data->status,
            'issue_date' => $data->issueDate,
            'expiry_date' => $data->expiryDate,
            'archived_at' => $data->status === DocumentStatus::Archived ? now() : null,
        ]);

        $this->documentRepository->syncTags($document, $data->tagIds);

        return $document;
    }

    public function archive(Document $document): Document
    {
        return $this->documentRepository->update($document, [
            'status' => DocumentStatus::Archived,
            'archived_at' => now(),
        ]);
    }

    public function restore(Document $document): Document
    {
        return $this->documentRepository->update($document, [
            'status' => DocumentStatus::Active,
            'archived_at' => null,
        ]);
    }

    public function delete(Document $document): void
    {
        $this->storage->delete($document->stored_path);
        $this->documentRepository->delete($document);
    }
}
