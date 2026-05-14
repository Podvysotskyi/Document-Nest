<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Http\RedirectResponse;

class UpdateTagController extends Controller
{
    public function __construct(private TagRepository $tagRepository) {}

    public function __invoke(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $this->tagRepository->update(
            $tag,
            $request->toDto(),
        );

        return redirect()->route('tags.index');
    }
}
