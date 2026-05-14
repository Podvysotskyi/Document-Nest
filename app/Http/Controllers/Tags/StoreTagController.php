<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;

class StoreTagController extends Controller
{
    public function __construct(private TagService $tagService) {}

    public function __invoke(StoreTagRequest $request): RedirectResponse
    {
        $this->tagService->createForUser(
            $request->user(),
            $request->toDto(),
        );

        return redirect()->route('tags.index');
    }
}
