<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\RedirectResponse;

class StoreCategoryController extends Controller
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function __invoke(StoreCategoryRequest $request): RedirectResponse
    {
        $this->categoryRepository->createForUser(
            $request->user(),
            $request->toDto(),
        );

        return redirect()->route('categories.index');
    }
}
