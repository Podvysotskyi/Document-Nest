<?php

namespace App\DTOs;

use App\Enums\DocumentStatus;

final readonly class DocumentFiltersData
{
    public function __construct(
        public ?string $query,
        public ?string $categoryId,
        public ?string $tagId,
        public ?DocumentStatus $status,
        public ?string $expiryFrom,
        public ?string $expiryTo,
        public string $sort,
    ) {}
}
