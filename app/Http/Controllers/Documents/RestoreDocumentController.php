<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestoreDocumentRequest;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;

class RestoreDocumentController extends Controller
{
    public function __construct(private DocumentService $documentService) {}

    public function __invoke(RestoreDocumentRequest $request, Document $document): RedirectResponse
    {
        $this->documentService->restore($document);

        return redirect()->route('documents.show', $document);
    }
}
