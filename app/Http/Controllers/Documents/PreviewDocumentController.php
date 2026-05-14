<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreviewDocumentRequest;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreviewDocumentController extends Controller
{
    public function __invoke(PreviewDocumentRequest $request, Document $document): StreamedResponse
    {
        return Storage::disk('local')->response(
            $document->stored_path,
            headers: ['Content-Type' => $document->mime_type],
        );
    }
}
