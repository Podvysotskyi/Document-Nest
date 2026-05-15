<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkArchiveDocumentsRequest;
use App\Services\DocumentBulkActionService;
use Illuminate\Http\RedirectResponse;

class BulkArchiveDocumentsController extends Controller
{
    public function __construct(private DocumentBulkActionService $documentBulkActionService) {}

    public function __invoke(BulkArchiveDocumentsRequest $request): RedirectResponse
    {
        $this->documentBulkActionService->archive($request->user(), $request->documentIds());

        return redirect()->route('documents.index');
    }
}
