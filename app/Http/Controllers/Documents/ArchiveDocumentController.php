<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArchiveDocumentRequest;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Http\RedirectResponse;

class ArchiveDocumentController extends Controller
{
    public function __construct(private DocumentRepository $documentRepository) {}

    public function __invoke(ArchiveDocumentRequest $request, Document $document): RedirectResponse
    {
        $this->documentRepository->archive($document);

        return redirect()->route('documents.show', $document);
    }
}
