<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexDocumentRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DocumentListResource;
use App\Http\Resources\TagResource;
use App\Repositories\CategoryRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\TagRepository;
use Inertia\Inertia;
use Inertia\Response;

class IndexDocumentController extends Controller
{
    public function __construct(
        private DocumentRepository $documentRepository,
        private CategoryRepository $categoryRepository,
        private TagRepository $tagRepository
    ) {}

    public function __invoke(IndexDocumentRequest $request): Response
    {
        $filters = $request->toDto();

        return Inertia::render('Documents/Index', [
            'documents' => DocumentListResource::collection(
                $this->documentRepository->paginateForUser($request->user(), $filters)
            )->response()->getData(true),
            'filters' => [
                'q' => $filters->query ?? '',
                'category_id' => $filters->categoryId ?? '',
                'tag_id' => $filters->tagId ?? '',
                'status' => $filters->status ?? '',
                'expiry_from' => $filters->expiryFrom ?? '',
                'expiry_to' => $filters->expiryTo ?? '',
                'sort' => $filters->sort,
            ],
            'categories' => CategoryResource::collection($this->categoryRepository->listForUser($request->user(), true))->resolve(),
            'tags' => TagResource::collection($this->tagRepository->listForUser($request->user()))->resolve(),
        ]);
    }
}
