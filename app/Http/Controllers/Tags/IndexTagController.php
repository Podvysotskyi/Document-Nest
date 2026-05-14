<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexTagRequest;
use App\Http\Resources\TagResource;
use App\Services\TagService;
use Inertia\Inertia;
use Inertia\Response;

class IndexTagController extends Controller
{
    public function __construct(private TagService $tagService) {}

    public function __invoke(IndexTagRequest $request): Response
    {
        return Inertia::render('Tags/Index', [
            'tags' => TagResource::collection(
                $this->tagService->listForUserWithDocumentCounts($request->user())
            )->resolve(),
        ]);
    }
}
