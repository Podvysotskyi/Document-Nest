<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexDocumentRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DocumentListResource;
use App\Http\Resources\SavedDocumentFilterResource;
use App\Http\Resources\TagResource;
use App\Services\CategoryService;
use App\Services\DocumentService;
use App\Services\SavedDocumentFilterService;
use App\Services\TagService;
use Inertia\Inertia;
use Inertia\Response;

class IndexDocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService,
        private CategoryService $categoryService,
        private TagService $tagService,
        private SavedDocumentFilterService $savedDocumentFilterService,
    ) {}

    public function __invoke(IndexDocumentRequest $request): Response
    {
        $filters = $request->toDto();

        return Inertia::render('Documents/Index', [
            'documents' => DocumentListResource::collection(
                $this->documentService->paginateForUser($request->user(), $filters)
            )->response()->getData(true),
            'filters' => [
                'q' => $filters->query ?? '',
                'category_id' => $request->input('category_id', ''),
                'tag_id' => $filters->tagId ?? '',
                'status' => $filters->status ?? '',
                'expiry_from' => $filters->expiryFrom ?? '',
                'expiry_to' => $filters->expiryTo ?? '',
                'sort' => $filters->sort,
                'direction' => $filters->direction ?? '',
            ],
            'savedFilters' => SavedDocumentFilterResource::collection($this->savedDocumentFilterService->listForUser($request->user()))->resolve(),
            'categories' => CategoryResource::collection($this->categoryService->listForUser($request->user(), true))->resolve(),
            'tags' => TagResource::collection($this->tagService->listForUser($request->user()))->resolve(),
        ]);
    }
}
