<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowDocumentRequest;
use App\Http\Resources\DocumentDetailResource;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Inertia\Inertia;
use Inertia\Response;

class ShowDocumentController extends Controller
{
    public function __construct(private DocumentRepository $documentRepository) {}

    public function __invoke(ShowDocumentRequest $request, Document $document): Response
    {
        $document = $this->documentRepository->loadForShow($document);

        return Inertia::render('Documents/Show', [
            'document' => DocumentDetailResource::make($document)->resolve(),
            'previewUrl' => route('documents.preview', $document),
            'downloadUrl' => route('documents.download', $document),
        ]);
    }
}
