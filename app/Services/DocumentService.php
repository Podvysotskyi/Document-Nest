<?php

namespace App\Services;

use App\DTOs\DocumentFiltersData;
use App\DTOs\StoreDocumentData;
use App\DTOs\UpdateDocumentData;
use App\Enums\DocumentStatus;
use App\Events\Documents\DocumentArchived;
use App\Events\Documents\DocumentCreated;
use App\Events\Documents\DocumentDeleted;
use App\Events\Documents\DocumentRestored;
use App\Events\Documents\DocumentUpdated;
use App\Models\Document;
use App\Models\User;
use App\Repositories\DocumentRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DocumentService
{
    public function __construct(
        private DocumentRepository $documentRepository,
        private DocumentStorageService $storage,
        private DocumentActivityPayloadFactory $activityPayloadFactory,
    ) {}

    public function paginateForUser(User $user, DocumentFiltersData $filters): LengthAwarePaginator
    {
        return $this->documentRepository->paginateForUser($user, $filters);
    }

    public function loadForShow(Document $document): Document
    {
        return $this->documentRepository->loadForShow($document);
    }

    /**
     * @return array{isPreviewable: bool, type: string}
     */
    public function resolvePreviewCapability(Document $document): array
    {
        $mimeType = strtolower($document->mime_type);

        if ($mimeType === 'application/pdf') {
            return ['isPreviewable' => true, 'type' => 'pdf'];
        }

        if (str_starts_with($mimeType, 'image/')) {
            return ['isPreviewable' => true, 'type' => 'image'];
        }

        return ['isPreviewable' => false, 'type' => 'unsupported'];
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
        $document->setRelation('tags', $document->tags()->get());

        DocumentCreated::dispatch(
            $this->activityPayloadFactory->forCreated($document, (string) $user->id),
        );

        return $document;
    }

    public function update(Document $document, UpdateDocumentData $data): Document
    {
        $original = $this->activityPayloadFactory->snapshot($document);

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
        $document->setRelation('tags', $document->tags()->get());

        DocumentUpdated::dispatch(
            $this->activityPayloadFactory->forUpdated($document, $original, $this->resolveActor($document)),
        );

        return $document;
    }

    public function archive(Document $document): Document
    {
        $original = $this->activityPayloadFactory->snapshot($document);

        $document = $this->documentRepository->update($document, [
            'status' => DocumentStatus::Archived,
            'archived_at' => now(),
        ]);

        DocumentArchived::dispatch(
            $this->activityPayloadFactory->forArchived($document, $original, $this->resolveActor($document)),
        );

        return $document;
    }

    public function restore(Document $document): Document
    {
        $original = $this->activityPayloadFactory->snapshot($document);

        $document = $this->documentRepository->update($document, [
            'status' => DocumentStatus::Active,
            'archived_at' => null,
        ]);

        DocumentRestored::dispatch(
            $this->activityPayloadFactory->forRestored($document, $original, $this->resolveActor($document)),
        );

        return $document;
    }

    public function delete(Document $document): void
    {
        $original = $this->activityPayloadFactory->snapshot($document);
        $actorId = $this->resolveActor($document);

        $this->storage->delete($document->stored_path);
        $this->documentRepository->delete($document);

        DocumentDeleted::dispatch(
            $this->activityPayloadFactory->forDeleted($document, $original, $actorId),
        );
    }

    private function resolveActor(Document $document): string
    {
        return (string) (Auth::id() ?? $document->user_id);
    }
}
