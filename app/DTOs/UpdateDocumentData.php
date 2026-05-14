<?php

namespace App\DTOs;

final readonly class UpdateDocumentData
{
    /**
     * @param  array<int, string>  $tagIds
     */
    public function __construct(
        public string $title,
        public ?string $categoryId,
        public array $tagIds,
        public ?string $notes,
        public ?string $issueDate,
        public ?string $expiryDate,
        public string $status,
    ) {}

    /**
     * @param  array<string, mixed>  $validated
     */
    public static function fromArray(array $validated): self
    {
        return new self(
            title: $validated['title'],
            categoryId: $validated['category_id'] ?? null,
            tagIds: $validated['tag_ids'] ?? [],
            notes: $validated['notes'] ?? null,
            issueDate: $validated['issue_date'] ?? null,
            expiryDate: $validated['expiry_date'] ?? null,
            status: $validated['status'],
        );
    }
}
