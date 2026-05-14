<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestoreDocumentRequest;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Http\RedirectResponse;

class RestoreDocumentController extends Controller
{
    public function __construct(private DocumentRepository $documentRepository) {}

    public function __invoke(RestoreDocumentRequest $request, Document $document): RedirectResponse
    {
        $this->documentRepository->restore($document);

        return redirect()->route('documents.show', $document);
    }
}
