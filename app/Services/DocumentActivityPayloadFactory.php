<?php

namespace App\Services;

use App\DTOs\DocumentActivityPayload;
use App\Enums\DocumentActivityType;
use App\Models\Document;
use Illuminate\Support\Str;

class DocumentActivityPayloadFactory
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function forCreated(Document $document, string $actorId, array $context = []): DocumentActivityPayload
    {
        $updated = $this->snapshot($document);

        return $this->build(
            document: $document,
            actorId: $actorId,
            type: DocumentActivityType::Created,
            description: 'Document created',
            metadata: array_merge($context, [
                'original' => null,
                'updated' => $updated,
                'changed_fields' => ['created'],
            ]),
        );
    }

    /**
     * @param  array<string, mixed>  $original
     * @param  array<string, mixed>  $context
     */
    public function forUpdated(Document $document, array $original, string $actorId, array $context = []): DocumentActivityPayload
    {
        $updated = $this->snapshot($document);

        return $this->build(
            document: $document,
            actorId: $actorId,
            type: DocumentActivityType::Updated,
            description: 'Document updated',
            metadata: array_merge($context, [
                'original' => $original,
                'updated' => $updated,
                'changed_fields' => $this->diffFields($original, $updated),
            ]),
        );
    }

    /**
     * @param  array<string, mixed>  $original
     * @param  array<string, mixed>  $context
     */
    public function forArchived(Document $document, array $original, string $actorId, array $context = []): DocumentActivityPayload
    {
        $updated = $this->snapshot($document);

        return $this->build(
            document: $document,
            actorId: $actorId,
            type: DocumentActivityType::Archived,
            description: 'Document archived',
            metadata: array_merge($context, [
                'original' => $original,
                'updated' => $updated,
                'changed_fields' => $this->diffFields($original, $updated),
            ]),
        );
    }

    /**
     * @param  array<string, mixed>  $original
     * @param  array<string, mixed>  $context
     */
    public function forRestored(Document $document, array $original, string $actorId, array $context = []): DocumentActivityPayload
    {
        $updated = $this->snapshot($document);

        return $this->build(
            document: $document,
            actorId: $actorId,
            type: DocumentActivityType::Restored,
            description: 'Document restored',
            metadata: array_merge($context, [
                'original' => $original,
                'updated' => $updated,
                'changed_fields' => $this->diffFields($original, $updated),
            ]),
        );
    }

    /**
     * @param  array<string, mixed>  $original
     * @param  array<string, mixed>  $context
     */
    public function forDeleted(Document $document, array $original, string $actorId, array $context = []): DocumentActivityPayload
    {
        return $this->build(
            document: $document,
            actorId: $actorId,
            type: DocumentActivityType::Deleted,
            description: 'Document deleted',
            metadata: array_merge($context, [
                'original' => $original,
                'updated' => null,
                'changed_fields' => ['deleted'],
            ]),
        );
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public function forReminderSent(Document $document, string $remindOn, ?string $reminderId = null, array $context = []): DocumentActivityPayload
    {
        $metadata = array_merge($context, [
            'remind_on' => $remindOn,
            'expiry_date' => $document->expiry_date?->toDateString(),
        ]);

        if ($reminderId !== null) {
            $metadata['reminder_id'] = $reminderId;
        }

        return $this->build(
            document: $document,
            actorId: (string) $document->user_id,
            type: DocumentActivityType::ReminderSent,
            description: 'Expiry reminder email sent',
            metadata: $metadata,
        );
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public function forDownloaded(Document $document, string $actorId, array $context = []): DocumentActivityPayload
    {
        return $this->build(
            document: $document,
            actorId: $actorId,
            type: DocumentActivityType::Downloaded,
            description: 'Document downloaded',
            metadata: $context,
        );
    }

    /**
     * @param  array<string, mixed>  $original
     */
    public function forBulkArchived(Document $document, array $original, string $actorId, string $bulkActionId): DocumentActivityPayload
    {
        $updated = $this->snapshot($document);

        return $this->build(
            document: $document,
            actorId: $actorId,
            type: DocumentActivityType::BulkArchived,
            description: 'Document archived in bulk action',
            metadata: [
                'bulk_action_id' => $bulkActionId,
                'source' => 'documents.index',
                'original' => $original,
                'updated' => $updated,
                'changed_fields' => $this->diffFields($original, $updated),
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $original
     */
    public function forBulkRestored(Document $document, array $original, string $actorId, string $bulkActionId): DocumentActivityPayload
    {
        $updated = $this->snapshot($document);

        return $this->build(
            document: $document,
            actorId: $actorId,
            type: DocumentActivityType::BulkRestored,
            description: 'Document restored in bulk action',
            metadata: [
                'bulk_action_id' => $bulkActionId,
                'source' => 'documents.index',
                'original' => $original,
                'updated' => $updated,
                'changed_fields' => $this->diffFields($original, $updated),
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $original
     */
    public function forBulkDeleted(Document $document, array $original, string $actorId, string $bulkActionId): DocumentActivityPayload
    {
        return $this->build(
            document: $document,
            actorId: $actorId,
            type: DocumentActivityType::BulkDeleted,
            description: 'Document deleted in bulk action',
            metadata: [
                'bulk_action_id' => $bulkActionId,
                'source' => 'documents.index',
                'original' => $original,
                'updated' => null,
                'changed_fields' => ['deleted'],
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function snapshot(Document $document): array
    {
        $document->loadMissing('tags:id');

        return [
            'title' => $document->title,
            'status' => $document->status?->value,
            'category_id' => $document->category_id,
            'tag_ids' => $document->tags->pluck('id')->all(),
            'notes' => $document->notes,
            'issue_date' => $document->issue_date?->toDateString(),
            'expiry_date' => $document->expiry_date?->toDateString(),
        ];
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function build(
        Document $document,
        string $actorId,
        DocumentActivityType $type,
        string $description,
        array $metadata,
    ): DocumentActivityPayload {
        return new DocumentActivityPayload(
            documentId: (string) $document->id,
            userId: (string) $document->user_id,
            actorId: $actorId,
            documentTitle: (string) $document->title,
            type: $type,
            description: $description,
            metadata: $metadata,
            activityId: (string) Str::uuid(),
        );
    }

    /**
     * @param  array<string, mixed>  $original
     * @param  array<string, mixed>  $updated
     * @return array<int, string>
     */
    private function diffFields(array $original, array $updated): array
    {
        $changed = [];

        foreach ($updated as $field => $value) {
            $previous = $original[$field] ?? null;

            if (is_array($previous) && is_array($value)) {
                sort($previous);
                sort($value);
            }

            if ($previous !== $value) {
                $changed[] = $field;
            }
        }

        return $changed;
    }
}
