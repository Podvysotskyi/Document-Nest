<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;

class UpdateDocumentController extends Controller
{
    public function __construct(private DocumentService $documentService) {}

    public function __invoke(
        UpdateDocumentRequest $request,
        Document $document
    ): RedirectResponse {
        $this->documentService->update($document, $request->toDto());

        return redirect()->route('documents.show', $document);
    }
}
