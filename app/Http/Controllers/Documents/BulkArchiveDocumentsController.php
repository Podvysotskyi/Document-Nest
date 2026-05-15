<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkArchiveDocumentsRequest;
use App\Services\DocumentBulkActionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class BulkArchiveDocumentsController extends Controller
{
    public function __construct(private DocumentBulkActionService $documentBulkActionService) {}

    public function __invoke(BulkArchiveDocumentsRequest $request): RedirectResponse
    {
        $count = $this->documentBulkActionService->archive($request->user(), $request->documentIds());

        return redirect()
            ->route('documents.index')
            ->with('success', $count === 0
                ? 'No documents needed archiving.'
                : "Archived {$count} ".Str::plural('document', $count).'.');
    }
}
