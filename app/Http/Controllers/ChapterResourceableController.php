<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterResourceable;
use App\Services\StringBladeService;
use App\View\Components\Blocks\RoleplayableSelector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ChapterResourceableController extends Controller
{
    /**
     * @param Chapter $chapter
     * @param StringBladeService $stringBlade
     * @return Response
     */
    public function show(Chapter $chapter, StringBladeService $stringBlade): Response
    {
        $blade = '<x-chapter.chapter-resources :chapter="$chapter" />';

        $html = $stringBlade->render(
            $blade, compact('chapter')
        );

        return response($html);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Chapter $chapter
     * @return View
     */
    public function create(Chapter $chapter): View
    {
        $resourceList = config('enums.resources');

        return view('chapter-resourceable.create', compact('resourceList', 'chapter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Chapter $chapter
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Chapter $chapter, Request $request): RedirectResponse
    {
        $chapterResourceable = new ChapterResourceable();
        $chapterResourceable->setChapter($chapter);

        $this->authorize('create', $chapterResourceable);

        $resourceable = RoleplayableSelector::createRoleplayableFromForm($request);
        $chapterResourceable->setResourceable($resourceable);

        $chapterResourceable->fill($request->only($chapterResourceable->getFillable()));

        $chapterResourceable->save();

        return redirect(route('roleplay.show', $chapter->roleplay)
                . '#chapter-' . $chapter->identifier)
            ->with('message', 'success|Les ressources ont été assignées à '
                . e($chapterResourceable->resourceable->getName()) . '.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ChapterResourceable $chapterResourceable
     * @return RedirectResponse
     */
    public function destroy(ChapterResourceable $chapterResourceable): RedirectResponse
    {
        $this->authorize('manage', $chapterResourceable);

        $chapterResourceable->delete();

        return redirect(route('roleplay.show', $chapterResourceable->chapter->roleplay)
                . '#chapter-' . $chapterResourceable->chapter->identifier)
            ->with('message', 'success|Les ressources assignées à '
                . e($chapterResourceable->resourceable->getName()) . ' ont été supprimées.');
    }
}
