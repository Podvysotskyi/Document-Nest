<?php

namespace App\Events\Documents;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentsBulkActionCompleted implements ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;

    /**
     * @param  array<string, mixed>  $metadata
     */
    public function __construct(
        public string $documentId,
        public string $userId,
        public string $actorId,
        public string $documentTitle,
        public string $type,
        public array $metadata = [],
    ) {}
}
