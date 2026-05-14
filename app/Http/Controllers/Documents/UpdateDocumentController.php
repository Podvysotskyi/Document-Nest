<?php

namespace App\Http\Controllers\Documents;

use App\DTOs\UpdateDocumentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Http\RedirectResponse;

class UpdateDocumentController extends Controller
{
    public function __invoke(
        UpdateDocumentRequest $request,
        Document $document,
        DocumentRepository $documentRepository
    ): RedirectResponse {
        $documentRepository->update($document, UpdateDocumentData::fromArray($request->validated()));

        return redirect()->route('documents.show', $document);
    }
}
