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
}
