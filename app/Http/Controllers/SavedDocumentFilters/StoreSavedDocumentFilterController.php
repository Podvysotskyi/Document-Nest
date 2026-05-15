<?php

namespace App\Http\Controllers\SavedDocumentFilters;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSavedDocumentFilterRequest;
use App\Services\SavedDocumentFilterService;
use Illuminate\Http\RedirectResponse;

class StoreSavedDocumentFilterController extends Controller
{
    public function __construct(private SavedDocumentFilterService $savedDocumentFilterService) {}

    public function __invoke(StoreSavedDocumentFilterRequest $request): RedirectResponse
    {
        $this->savedDocumentFilterService->createForUser($request->user(), $request->toDto());

        return redirect()->back()->with('success', 'Saved view created.');
    }
}
