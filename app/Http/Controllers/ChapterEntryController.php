<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterEntry;
use App\Services\StringBladeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChapterEntryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  Chapter  $chapter
     * @param  StringBladeService  $stringBlade
     * @return Response
     */
    public function create(Chapter $chapter, StringBladeService $stringBlade): Response
    {
        $this->authorize('create', ChapterEntry::class);

        $blade = '<x-chapter-entry.create-chapter-entry :chapter="$chapter" />';

        $html = $stringBlade->render(
            $blade, compact('chapter')
        );

        return response($html);
    }

    /**
     * @param Chapter $chapter
     * @param StringBladeService $stringBlade
     * @return Response
     */
    public function createButton(Chapter $chapter, StringBladeService $stringBlade): Response
    {
        $this->authorize('create', ChapterEntry::class);

        $blade = '<x-chapter-entry.create-button :chapter="$chapter" />';

        $html = $stringBlade->render(
            $blade, compact('chapter')
        );

        return response($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @param  Chapter  $chapter
     * @return Response
     */
    public function store(Request $request, Chapter $chapter)
    {
        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @param  ChapterEntry  $entry
     * @return Response
     */
    public function show(ChapterEntry $entry)
    {
        return response()->noContent();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ChapterEntry  $entry
     * @return Response
     */
    public function edit(ChapterEntry $entry)
    {
        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  ChapterEntry  $entry
     * @return Response
     */
    public function update(Request $request, ChapterEntry $entry)
    {
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ChapterEntry  $entry
     * @return Response
     */
    public function destroy(ChapterEntry $entry)
    {
        return response()->noContent();
    }
}
