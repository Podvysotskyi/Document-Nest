<?php

namespace App\Http\Controllers\Documents;

use App\DTOs\DocumentFiltersData;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use App\Models\Tag;
use App\Repositories\DocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class IndexDocumentController extends Controller
{
    public function __construct(private DocumentRepository $documentRepository) {}

    public function __invoke(Request $request): Response
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'uuid'],
            'tag_id' => ['nullable', 'uuid'],
            'status' => ['nullable', Rule::in([
                Document::STATUS_ACTIVE,
                Document::STATUS_EXPIRED,
                Document::STATUS_ARCHIVED,
            ])],
            'expiry_from' => ['nullable', 'date'],
            'expiry_to' => ['nullable', 'date'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'title', 'expiry_date'])],
        ]);

        $filters = DocumentFiltersData::fromArray($validated);

        return Inertia::render('Documents/Index', [
            'documents' => $this->documentRepository->paginateForUser($request->user(), $filters),
            'filters' => [
                'q' => $filters->query ?? '',
                'category_id' => $filters->categoryId ?? '',
                'tag_id' => $filters->tagId ?? '',
                'status' => $filters->status ?? '',
                'expiry_from' => $filters->expiryFrom ?? '',
                'expiry_to' => $filters->expiryTo ?? '',
                'sort' => $filters->sort,
            ],
            'categories' => Category::query()
                ->ownedBy($request->user())
                ->orderBy('name')
                ->get(['id', 'name']),
            'tags' => Tag::query()
                ->ownedBy($request->user())
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }
}
