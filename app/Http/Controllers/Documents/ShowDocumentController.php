<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowDocumentRequest;
use App\Models\Document;
use Inertia\Inertia;
use Inertia\Response;

class ShowDocumentController extends Controller
{
    public function __invoke(ShowDocumentRequest $request, Document $document): Response
    {
        $document->load(['category:id,name', 'tags:id,name']);

        return Inertia::render('Documents/Show', [
            'document' => $document,
            'previewUrl' => route('documents.preview', $document),
            'downloadUrl' => route('documents.download', $document),
        ]);
    }
}
