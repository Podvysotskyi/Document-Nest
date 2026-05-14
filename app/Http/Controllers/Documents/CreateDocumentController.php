<?php

namespace App\Http\Controllers\Documents;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use App\Services\CategoryService;
use App\Services\TagService;
use Inertia\Inertia;
use Inertia\Response;

class CreateDocumentController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
        private TagService $tagService,
    ) {}

    public function __invoke(CreateDocumentRequest $request): Response
    {
        return Inertia::render('Documents/Create', [
            'categories' => CategoryResource::collection($this->categoryService->listForUser($request->user()))->resolve(),
            'tags' => TagResource::collection($this->tagService->listForUser($request->user()))->resolve(),
            'statuses' => array_column(DocumentStatus::cases(), 'value'),
        ]);
    }
}
