<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditDocumentRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DocumentEditResource;
use App\Http\Resources\TagResource;
use App\Models\Document;
use App\Repositories\CategoryRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\TagRepository;
use Inertia\Inertia;
use Inertia\Response;

class EditDocumentController extends Controller
{
    public function __construct(
        private DocumentRepository $documentRepository,
        private CategoryRepository $categoryRepository,
        private TagRepository $tagRepository
    ) {}

    public function __invoke(EditDocumentRequest $request, Document $document): Response
    {
        $document = $this->documentRepository->loadForShow($document);

        return Inertia::render('Documents/Edit', [
            'document' => DocumentEditResource::make($document)->resolve(),
            'categories' => CategoryResource::collection($this->categoryRepository->listForUser($request->user()))->resolve(),
            'tags' => TagResource::collection($this->tagRepository->listForUser($request->user()))->resolve(),
            'statuses' => [
                Document::STATUS_ACTIVE,
                Document::STATUS_EXPIRED,
                Document::STATUS_ARCHIVED,
            ],
        ]);
    }
}
