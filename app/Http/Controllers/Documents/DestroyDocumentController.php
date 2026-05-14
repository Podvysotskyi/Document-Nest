<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyDocumentRequest;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DestroyDocumentController extends Controller
{
    public function __invoke(DestroyDocumentRequest $request, Document $document): RedirectResponse
    {
        Storage::disk('local')->delete($document->stored_path);
        $document->delete();

        return redirect()->route('documents.index');
    }
}
