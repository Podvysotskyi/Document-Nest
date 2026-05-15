<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkDeleteDocumentsRequest;
use App\Services\DocumentBulkActionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class BulkDeleteDocumentsController extends Controller
{
    public function __construct(private DocumentBulkActionService $documentBulkActionService) {}

    public function __invoke(BulkDeleteDocumentsRequest $request): RedirectResponse
    {
        $count = $this->documentBulkActionService->delete($request->user(), $request->documentIds());

        return redirect()
            ->route('documents.index')
            ->with('success', $count === 0
                ? 'No documents were deleted.'
                : "Deleted {$count} ".Str::plural('document', $count).'.');
    }
}
