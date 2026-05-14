<?php

namespace App\Http\Controllers\Documents;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use Inertia\Inertia;
use Inertia\Response;

class CreateDocumentController extends Controller
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private TagRepository $tagRepository
    ) {}

    public function __invoke(CreateDocumentRequest $request): Response
    {
        return Inertia::render('Documents/Create', [
            'categories' => CategoryResource::collection($this->categoryRepository->listForUser($request->user()))->resolve(),
            'tags' => TagResource::collection($this->tagRepository->listForUser($request->user()))->resolve(),
            'statuses' => array_column(DocumentStatus::cases(), 'value'),
        ]);
    }
}
