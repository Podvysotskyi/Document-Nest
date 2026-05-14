<?php

namespace App\Http\Controllers\Documents;

use App\DTOs\StoreDocumentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Repositories\DocumentRepository;
use Illuminate\Http\RedirectResponse;

class StoreDocumentController extends Controller
{
    public function __construct(private DocumentRepository $documentRepository) {}

    public function __invoke(StoreDocumentRequest $request): RedirectResponse
    {
        $document = $this->documentRepository->createForUser(
            $request->user(),
            StoreDocumentData::fromArray($request->validated()),
        );

        return redirect()->route('documents.show', $document);
    }
}
