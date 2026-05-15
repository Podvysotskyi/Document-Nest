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
    ) {}

    public function archive(User $user, array $documentIds): int
    {
        $bulkActionId = (string) Str::uuid();

        return $this->run($user, $documentIds, function (Document $document) use ($bulkActionId): bool {
            if ($document->status === DocumentStatus::Archived) {
                return false;
            }

            $this->documentRepository->update($document, [
                'status' => DocumentStatus::Archived,
                'archived_at' => now(),
            ]);

            event(new DocumentsBulkActionCompleted(
                documentId: $document->id,
                userId: $document->user_id,
                actorId: $document->user_id,
                documentTitle: $document->title,
                type: 'bulk_archived',
                metadata: [
                    'bulk_action_id' => $bulkActionId,
                    'source' => 'documents.index',
                ],
            ));

            return true;
        });
    }

    public function restore(User $user, array $documentIds): int
    {
        $bulkActionId = (string) Str::uuid();

        return $this->run($user, $documentIds, function (Document $document) use ($bulkActionId): bool {
            if ($document->status === DocumentStatus::Active) {
                return false;
            }

            $this->documentRepository->update($document, [
                'status' => DocumentStatus::Active,
                'archived_at' => null,
            ]);

            event(new DocumentsBulkActionCompleted(
                documentId: $document->id,
                userId: $document->user_id,
                actorId: $document->user_id,
                documentTitle: $document->title,
                type: 'bulk_restored',
                metadata: [
                    'bulk_action_id' => $bulkActionId,
                    'source' => 'documents.index',
                ],
            ));

            return true;
        });
    }

    public function delete(User $user, array $documentIds): int
    {
        $bulkActionId = (string) Str::uuid();

        return $this->run($user, $documentIds, function (Document $document) use ($bulkActionId): bool {
            $this->documentStorageService->delete($document->stored_path);
            $this->documentRepository->delete($document);

            event(new DocumentsBulkActionCompleted(
                documentId: $document->id,
                userId: $document->user_id,
                actorId: $document->user_id,
                documentTitle: $document->title,
                type: 'bulk_deleted',
                metadata: [
                    'bulk_action_id' => $bulkActionId,
                    'source' => 'documents.index',
                ],
            ));

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
