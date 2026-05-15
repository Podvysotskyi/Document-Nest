<?php

namespace App\Http\Controllers\SavedDocumentFilters;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroySavedDocumentFilterRequest;
use App\Models\SavedDocumentFilter;
use App\Services\SavedDocumentFilterService;
use Illuminate\Http\RedirectResponse;

class DestroySavedDocumentFilterController extends Controller
{
    public function __construct(private SavedDocumentFilterService $savedDocumentFilterService) {}

    public function __invoke(DestroySavedDocumentFilterRequest $request, SavedDocumentFilter $savedDocumentFilter): RedirectResponse
    {
        $this->savedDocumentFilterService->delete($savedDocumentFilter);

        return redirect()->route('documents.index')->with('success', 'Saved view deleted.');
    }
}
