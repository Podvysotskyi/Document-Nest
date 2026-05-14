<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditDocumentRequest;
use App\Models\Category;
use App\Models\Document;
use App\Models\Tag;
use Inertia\Inertia;
use Inertia\Response;

class EditDocumentController extends Controller
{
    public function __invoke(EditDocumentRequest $request, Document $document): Response
    {
        $document->load('tags:id');

        return Inertia::render('Documents/Edit', [
            'document' => [
                ...$document->only([
                    'id',
                    'title',
                    'category_id',
                    'notes',
                    'status',
                    'issue_date',
                    'expiry_date',
                ]),
                'tag_ids' => $document->tags->pluck('id')->all(),
            ],
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
