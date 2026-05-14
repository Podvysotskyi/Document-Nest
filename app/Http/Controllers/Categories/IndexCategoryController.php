<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Inertia\Inertia;
use Inertia\Response;

class IndexCategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function __invoke(IndexCategoryRequest $request): Response
    {
        return Inertia::render('Categories/Index', [
            'categories' => CategoryResource::collection(
                $this->categoryService->listForUserWithDocumentCounts($request->user())
            )->resolve(),
        ]);
    }
}
