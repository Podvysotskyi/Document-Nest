<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyDocumentRequest;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;

class DestroyDocumentController extends Controller
{
    public function __construct(private DocumentService $documentService) {}

    public function __invoke(DestroyDocumentRequest $request, Document $document): RedirectResponse
    {
        $this->documentService->delete($document);

        return redirect()->route('documents.index');
    }
}
