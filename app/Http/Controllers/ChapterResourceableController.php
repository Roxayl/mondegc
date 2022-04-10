<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterResourceable;
use App\Services\StringBladeService;
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
     * @return Response
     */
    public function store(Chapter $chapter, Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ChapterResourceable $chapterResourceable
     * @return Response
     */
    public function edit(ChapterResourceable $chapterResourceable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ChapterResourceable $chapterResourceable
     * @return Response
     */
    public function update(Request $request, ChapterResourceable $chapterResourceable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ChapterResourceable $chapterResourceable
     * @return Response
     */
    public function destroy(ChapterResourceable $chapterResourceable)
    {
        //
    }
}
