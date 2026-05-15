<?php

namespace App\Services;

use App\Enums\DocumentStatus;
use App\Events\Documents\DocumentsBulkActionCompleted;
use App\Models\Document;
use App\Models\User;
use App\Repositories\DocumentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentBulkActionService
{
    public function __construct(
        private DocumentRepository $documentRepository,
        private DocumentStorageService $documentStorageService,
        private DocumentActivityPayloadFactory $activityPayloadFactory,
    ) {}

    /**
     * @param  array<int, string>  $documentIds
     */
    public function archive(User $user, array $documentIds): int
    {
        $bulkActionId = (string) Str::uuid();

        return $this->run($user, $documentIds, function (Document $document) use ($user, $bulkActionId): bool {
            if ($document->status === DocumentStatus::Archived) {
                return false;
            }

            $original = $this->activityPayloadFactory->snapshot($document);

            $document = $this->documentRepository->update($document, [
                'status' => DocumentStatus::Archived,
                'archived_at' => now(),
            ]);

            DocumentsBulkActionCompleted::dispatch(
                $this->activityPayloadFactory->forBulkArchived($document, $original, (string) $user->id, $bulkActionId),
            );

            return true;
        });
    }

    /**
     * @param  array<int, string>  $documentIds
     */
    public function restore(User $user, array $documentIds): int
    {
        $bulkActionId = (string) Str::uuid();

        return $this->run($user, $documentIds, function (Document $document) use ($user, $bulkActionId): bool {
            if ($document->status === DocumentStatus::Active) {
                return false;
            }

            $original = $this->activityPayloadFactory->snapshot($document);

            $document = $this->documentRepository->update($document, [
                'status' => DocumentStatus::Active,
                'archived_at' => null,
            ]);

            DocumentsBulkActionCompleted::dispatch(
                $this->activityPayloadFactory->forBulkRestored($document, $original, (string) $user->id, $bulkActionId),
            );

            return true;
        });
    }

    /**
     * @param  array<int, string>  $documentIds
     */
    public function delete(User $user, array $documentIds): int
    {
        $bulkActionId = (string) Str::uuid();

        return $this->run($user, $documentIds, function (Document $document) use ($user, $bulkActionId): bool {
            $original = $this->activityPayloadFactory->snapshot($document);

            $this->documentStorageService->delete($document->stored_path);
            $this->documentRepository->delete($document);

            DocumentsBulkActionCompleted::dispatch(
                $this->activityPayloadFactory->forBulkDeleted($document, $original, (string) $user->id, $bulkActionId),
            );

            return true;
        });
    }

    /**
     * @param  array<int, string>  $documentIds
     * @param  callable(Document): bool  $callback
     */
    private function run(User $user, array $documentIds, callable $callback): int
    {
        $changesCount = 0;
        $ownedDocuments = $this->documentRepository->findOwnedByIds($user, $documentIds);

        DB::transaction(function () use ($ownedDocuments, $callback, &$changesCount): void {
            foreach ($ownedDocuments as $document) {
                if ($callback($document)) {
                    $changesCount++;
                }
            }
        });

        return $changesCount;
    }
}
