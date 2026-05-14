<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyTagRequest;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;

class DestroyTagController extends Controller
{
    public function __construct(private TagService $tagService) {}

    public function __invoke(DestroyTagRequest $request, Tag $tag): RedirectResponse
    {
        $this->tagService->delete($tag);

        return redirect()->route('tags.index');
    }
}
