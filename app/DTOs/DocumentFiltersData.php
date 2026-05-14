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
}
