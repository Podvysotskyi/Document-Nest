<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;

class UpdateTagController extends Controller
{
    public function __construct(private TagService $tagService) {}

    public function __invoke(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $this->tagService->update(
            $tag,
            $request->toDto(),
        );

        return redirect()->route('tags.index');
    }
}
