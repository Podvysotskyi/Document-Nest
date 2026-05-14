<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;

class StoreDocumentController extends Controller
{
    public function __construct(private DocumentService $documentService) {}

    public function __invoke(StoreDocumentRequest $request): RedirectResponse
    {
        $document = $this->documentService->createForUser(
            $request->user(),
            $request->toDto(),
        );

        return redirect()->route('documents.show', $document);
    }
}
