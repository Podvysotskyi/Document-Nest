<?php

namespace App\Http\Controllers\Documents;

use App\Events\Documents\DocumentDownloaded;
use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadDocumentRequest;
use App\Models\Document;
use App\Services\DocumentActivityPayloadFactory;
use App\Services\DocumentStorageService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadDocumentController extends Controller
{
    public function __construct(
        private DocumentStorageService $storage,
        private DocumentActivityPayloadFactory $activityPayloadFactory,
    ) {}

    public function __invoke(DownloadDocumentRequest $request, Document $document): StreamedResponse
    {
        DocumentDownloaded::dispatch(
            $this->activityPayloadFactory->forDownloaded($document, (string) $request->user()->id),
        );

        return $this->storage->streamDownload($document->stored_path, $document->original_filename);
    }
}
