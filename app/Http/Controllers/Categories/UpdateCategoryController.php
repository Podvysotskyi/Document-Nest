<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\RedirectResponse;

class UpdateCategoryController extends Controller
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function __invoke(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryRepository->update(
            $category,
            $request->toDto(),
        );

        return redirect()->route('categories.index');
    }
}
