<?php

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Roxayl\MondeGC\Models\Chapter;
use Roxayl\MondeGC\Models\ChapterResourceable;
use Roxayl\MondeGC\Services\StringBladeService;
use Roxayl\MondeGC\View\Components\Blocks\RoleplayableSelector;

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
     * @param Chapter $chapter
     * @return View
     */
    public function manage(Chapter $chapter): View
    {
        return view('chapter-resourceable.manage', compact('chapter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Chapter $chapter
     * @return View
     */
    public function create(Chapter $chapter): View
    {
        return view('chapter-resourceable.create', compact('chapter'));
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
     * @param ChapterResourceable $chapterResourceable
     * @return View
     */
    public function edit(ChapterResourceable $chapterResourceable): View
    {
        $this->authorize('manage', $chapterResourceable);

        $chapter = $chapterResourceable->chapter;
        $roleplayable = $chapterResourceable->resourceable;
        $oldValues = $chapterResourceable->resources();

        return view('chapter-resourceable.edit',
            compact('chapterResourceable', 'chapter', 'oldValues', 'roleplayable'));
    }

    /**
     * @param Request $request
     * @param ChapterResourceable $chapterResourceable
     * @return RedirectResponse
     */
    public function update(Request $request, ChapterResourceable $chapterResourceable): RedirectResponse
    {
        $this->authorize('manage', $chapterResourceable);

        $resourceable = RoleplayableSelector::createRoleplayableFromForm($request);
        $chapterResourceable->setResourceable($resourceable);

        $chapterResourceable->fill($request->only($chapterResourceable->getFillable()));

        $chapterResourceable->save();

        return redirect(route('roleplay.show', $chapterResourceable->roleplay())
                . '#chapter-' . $chapterResourceable->chapter->identifier)
            ->with('message', 'success|Les ressources ont été modifiées et assignées à '
                . e($chapterResourceable->resourceable->getName()) . '.');
    }

    /**
     * @param ChapterResourceable $chapterResourceable
     * @return View
     */
    public function delete(ChapterResourceable $chapterResourceable): View
    {
        $this->authorize('manage', $chapterResourceable);

        return view('chapter-resourceable.delete', compact('chapterResourceable'));
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

        return redirect(route('roleplay.show', $chapterResourceable->roleplay())
                . '#chapter-' . $chapterResourceable->chapter->identifier)
            ->with('message', 'success|Les ressources assignées à '
                . e($chapterResourceable->resourceable->getName()) . ' ont été supprimées.');
    }
}
