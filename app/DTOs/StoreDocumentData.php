<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

final readonly class StoreDocumentData
{
    /**
     * @param  array<int, string>  $tagIds
     */
    public function __construct(
        public UploadedFile $file,
        public string $title,
        public ?string $categoryId,
        public array $tagIds,
        public ?string $notes,
        public ?string $issueDate,
        public ?string $expiryDate,
        public ?string $status,
    ) {}
}
