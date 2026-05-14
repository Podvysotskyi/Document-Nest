<?php

namespace App\DTOs;

final readonly class DocumentFiltersData
{
    public function __construct(
        public ?string $query,
        public ?string $categoryId,
        public ?string $tagId,
        public ?string $status,
        public ?string $expiryFrom,
        public ?string $expiryTo,
        public string $sort,
    ) {}

    /**
     * @param  array<string, mixed>  $validated
     */
    public static function fromArray(array $validated): self
    {
        return new self(
            query: $validated['q'] ?? null,
            categoryId: $validated['category_id'] ?? null,
            tagId: $validated['tag_id'] ?? null,
            status: $validated['status'] ?? null,
            expiryFrom: $validated['expiry_from'] ?? null,
            expiryTo: $validated['expiry_to'] ?? null,
            sort: $validated['sort'] ?? 'newest',
        );
    }
}
