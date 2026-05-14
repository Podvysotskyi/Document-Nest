<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;

class StoreCategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function __invoke(StoreCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->createForUser(
            $request->user(),
            $request->toDto(),
        );

        return redirect()->route('categories.index');
    }
}
