<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadDocumentRequest;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadDocumentController extends Controller
{
    public function __construct(private DocumentRepository $documentRepository) {}

    public function __invoke(DownloadDocumentRequest $request, Document $document): StreamedResponse
    {
        return $this->documentRepository->streamDownload($document);
    }
}
