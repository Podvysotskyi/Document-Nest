<?php

namespace App\Repositories;

use App\Models\Document;
use App\Models\Mongodb\DocumentActivity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocumentActivityRepository
{
    public function paginateForDocument(Document $document, int $perPage = 20): LengthAwarePaginator
    {
        return DocumentActivity::query()
            ->where('document_id', (string) $document->id)
            ->where('user_id', (string) $document->user_id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
