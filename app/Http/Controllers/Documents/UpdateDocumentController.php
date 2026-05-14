<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Http\RedirectResponse;

class UpdateDocumentController extends Controller
{
    public function __construct(private DocumentRepository $documentRepository) {}

    public function __invoke(
        UpdateDocumentRequest $request,
        Document $document
    ): RedirectResponse {
        $this->documentRepository->update($document, $request->toDto());

        return redirect()->route('documents.show', $document);
    }
}
