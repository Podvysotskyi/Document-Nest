<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkDeleteDocumentsRequest;
use App\Services\DocumentBulkActionService;
use Illuminate\Http\RedirectResponse;

class BulkDeleteDocumentsController extends Controller
{
    public function __construct(private DocumentBulkActionService $documentBulkActionService) {}

    public function __invoke(BulkDeleteDocumentsRequest $request): RedirectResponse
    {
        $this->documentBulkActionService->delete($request->user(), $request->documentIds());

        return redirect()->route('documents.index');
    }
}
