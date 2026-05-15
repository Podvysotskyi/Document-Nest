<?php

namespace App\Http\Controllers\SavedDocumentFilters;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSavedDocumentFilterRequest;
use App\Models\SavedDocumentFilter;
use App\Services\SavedDocumentFilterService;
use Illuminate\Http\RedirectResponse;

class UpdateSavedDocumentFilterController extends Controller
{
    public function __construct(private SavedDocumentFilterService $savedDocumentFilterService) {}

    public function __invoke(UpdateSavedDocumentFilterRequest $request, SavedDocumentFilter $savedDocumentFilter): RedirectResponse
    {
        $this->savedDocumentFilterService->update($savedDocumentFilter, $request->toDto());

        return redirect()->back()->with('success', 'Saved view updated.');
    }
}
