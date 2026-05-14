<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexTagRequest;
use App\Http\Resources\TagResource;
use App\Repositories\TagRepository;
use Inertia\Inertia;
use Inertia\Response;

class IndexTagController extends Controller
{
    public function __construct(private TagRepository $tagRepository) {}

    public function __invoke(IndexTagRequest $request): Response
    {
        return Inertia::render('Tags/Index', [
            'tags' => TagResource::collection(
                $this->tagRepository->listForUserWithDocumentCounts($request->user())
            )->resolve(),
        ]);
    }
}
