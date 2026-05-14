<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadDocumentRequest;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadDocumentController extends Controller
{
    public function __invoke(DownloadDocumentRequest $request, Document $document): StreamedResponse
    {
        return Storage::disk('local')->download($document->stored_path, $document->original_filename);
    }
}
