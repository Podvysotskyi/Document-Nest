<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArchiveDocumentRequest;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;

class ArchiveDocumentController extends Controller
{
    public function __construct(private DocumentService $documentService) {}

    public function __invoke(ArchiveDocumentRequest $request, Document $document): RedirectResponse
    {
        $this->documentService->archive($document);

        return redirect()->route('documents.show', $document);
    }
}
