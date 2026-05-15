<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowDocumentRequest;
use App\Http\Resources\DocumentActivityResource;
use App\Http\Resources\DocumentDetailResource;
use App\Models\Document;
use App\Repositories\DocumentActivityRepository;
use App\Services\DocumentService;
use Inertia\Inertia;
use Inertia\Response;

class ShowDocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService,
        private DocumentActivityRepository $documentActivityRepository,
    ) {}

    public function __invoke(ShowDocumentRequest $request, Document $document): Response
    {
        $document = $this->documentService->loadForShow($document);

        return Inertia::render('Documents/Show', [
            'document' => DocumentDetailResource::make($document)->resolve(),
            'previewUrl' => route('documents.preview', $document),
            'downloadUrl' => route('documents.download', $document),
            'preview' => $this->documentService->resolvePreviewCapability($document),
            'activities' => Inertia::defer(fn () => DocumentActivityResource::collection(
                $this->documentActivityRepository->paginateForDocument($document)
            )->response()->getData(true)),
        ]);
    }
}
