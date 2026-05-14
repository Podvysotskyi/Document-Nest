<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Repositories\TagRepository;
use Illuminate\Http\RedirectResponse;

class StoreTagController extends Controller
{
    public function __construct(private TagRepository $tagRepository) {}

    public function __invoke(StoreTagRequest $request): RedirectResponse
    {
        $this->tagRepository->createForUser(
            $request->user(),
            $request->toDto(),
        );

        return redirect()->route('tags.index');
    }
}
