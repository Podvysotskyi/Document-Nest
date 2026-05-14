<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArchiveDocumentRequest;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;

class ArchiveDocumentController extends Controller
{
    public function __invoke(ArchiveDocumentRequest $request, Document $document): RedirectResponse
    {
        $document->update([
            'status' => Document::STATUS_ARCHIVED,
            'archived_at' => now(),
        ]);

        return redirect()->route('documents.show', $document);
    }
}
