<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\RedirectResponse;

class DestroyCategoryController extends Controller
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function __invoke(DestroyCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryRepository->delete($category);

        return redirect()->route('categories.index');
    }
}
