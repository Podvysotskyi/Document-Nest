<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyTagRequest;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Http\RedirectResponse;

class DestroyTagController extends Controller
{
    public function __construct(private TagRepository $tagRepository) {}

    public function __invoke(DestroyTagRequest $request, Tag $tag): RedirectResponse
    {
        $this->tagRepository->delete($tag);

        return redirect()->route('tags.index');
    }
}
