<?php

namespace App\DTOs;

final readonly class SavedDocumentFilterData
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(
        public string $name,
        public array $filters,
        public ?string $sort,
        public ?string $direction,
        public bool $isDefault,
    ) {}
}
