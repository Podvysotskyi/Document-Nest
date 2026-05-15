<?php

namespace App\Listeners;

use App\Events\Documents\DocumentActivityEvent;
use App\Services\DocumentActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateDocumentActivity implements ShouldQueue
{
    public int $tries = 3;

    public int $backoff = 5;

    public function __construct(private DocumentActivityService $documentActivityService) {}

    public function handle(DocumentActivityEvent $event): void
    {
        try {
            $this->documentActivityService->record($event->payload);
        } catch (Throwable $exception) {
            report($exception);

            Log::error('Failed to record document activity', [
                'document_id' => $event->payload->documentId,
                'user_id' => $event->payload->userId,
                'type' => $event->payload->type->value,
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    public function failed(DocumentActivityEvent $event, Throwable $exception): void
    {
        report($exception);

        Log::error('Document activity listener failed', [
            'document_id' => $event->payload->documentId,
            'user_id' => $event->payload->userId,
            'type' => $event->payload->type->value,
            'exception' => $exception->getMessage(),
        ]);
    }
}
