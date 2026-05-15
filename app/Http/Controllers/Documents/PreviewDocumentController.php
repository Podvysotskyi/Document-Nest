<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreviewDocumentRequest;
use App\Models\Document;
use App\Services\DocumentStorageService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreviewDocumentController extends Controller
{
    public function __construct(private DocumentStorageService $storage) {}

    public function __invoke(PreviewDocumentRequest $request, Document $document): StreamedResponse
    {
        return $this->storage->streamPreview($document->stored_path, $document->mime_type, $document->original_filename);
    }
}
