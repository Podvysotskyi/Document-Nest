<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkRestoreDocumentsRequest;
use App\Services\DocumentBulkActionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class BulkRestoreDocumentsController extends Controller
{
    public function __construct(private DocumentBulkActionService $documentBulkActionService) {}

    public function __invoke(BulkRestoreDocumentsRequest $request): RedirectResponse
    {
        $count = $this->documentBulkActionService->restore($request->user(), $request->documentIds());

        return redirect()
            ->route('documents.index')
            ->with('success', $count === 0
                ? 'No documents needed restoring.'
                : "Restored {$count} ".Str::plural('document', $count).'.');
    }
}
