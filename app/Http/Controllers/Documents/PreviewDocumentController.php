<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreviewDocumentRequest;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreviewDocumentController extends Controller
{
    public function __construct(private DocumentRepository $documentRepository) {}

    public function __invoke(PreviewDocumentRequest $request, Document $document): StreamedResponse
    {
        return $this->documentRepository->streamPreview($document);
    }
}
