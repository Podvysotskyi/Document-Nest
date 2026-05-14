<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDocumentRequest;
use App\Models\Category;
use App\Models\Document;
use App\Models\Tag;
use Inertia\Inertia;
use Inertia\Response;

class CreateDocumentController extends Controller
{
    public function __invoke(CreateDocumentRequest $request): Response
    {
        return Inertia::render('Documents/Create', [
            'categories' => Category::query()->ownedBy($request->user())->orderBy('name')->get(['id', 'name']),
            'tags' => Tag::query()->ownedBy($request->user())->orderBy('name')->get(['id', 'name']),
            'statuses' => [
                Document::STATUS_ACTIVE,
                Document::STATUS_EXPIRED,
                Document::STATUS_ARCHIVED,
            ],
        ]);
    }
}
