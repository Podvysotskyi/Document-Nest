<?php

namespace App\DTOs;

use App\Enums\DocumentActivityType;

final readonly class DocumentActivityPayload
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function __construct(
        public string $documentId,
        public string $userId,
        public string $actorId,
        public string $documentTitle,
        public DocumentActivityType $type,
        public string $description,
        public array $metadata,
        public string $activityId,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'activity_id' => $this->activityId,
            'document_id' => $this->documentId,
            'user_id' => $this->userId,
            'actor_id' => $this->actorId,
            'document_title' => $this->documentTitle,
            'type' => $this->type->value,
            'description' => $this->description,
            'metadata' => $this->metadata,
        ];
    }
}
