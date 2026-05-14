<?php

namespace App\Http\Controllers\Documents;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditDocumentRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DocumentEditResource;
use App\Http\Resources\TagResource;
use App\Models\Document;
use App\Services\CategoryService;
use App\Services\DocumentService;
use App\Services\TagService;
use Inertia\Inertia;
use Inertia\Response;

class EditDocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService,
        private CategoryService $categoryService,
        private TagService $tagService,
    ) {}

    public function __invoke(EditDocumentRequest $request, Document $document): Response
    {
        $document = $this->documentService->loadForShow($document);

        return Inertia::render('Documents/Edit', [
            'document' => DocumentEditResource::make($document)->resolve(),
            'categories' => CategoryResource::collection($this->categoryService->listForUser($request->user()))->resolve(),
            'tags' => TagResource::collection($this->tagService->listForUser($request->user()))->resolve(),
            'statuses' => array_column(DocumentStatus::cases(), 'value'),
        ]);
    }
}
