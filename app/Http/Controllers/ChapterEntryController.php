<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterEntry\ManageResource;
use App\Models\Chapter;
use App\Models\ChapterEntry;
use App\Models\Pays;
use App\Services\StringBladeService;
use Illuminate\Http\RedirectResponse;
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
     * @param  ManageResource  $request
     * @param  Chapter  $chapter
     * @return RedirectResponse
     */
    public function store(ManageResource $request, Chapter $chapter): RedirectResponse
    {
        $entry = new ChapterEntry();

        // Set relations.
        $entry->chapter_id = $chapter->getKey();
        // TODO. Récupérer le roleplayable avec la requête.
        $entry->roleplayable_id = Pays::inRandomOrder()->first()->getKey();
        $entry->roleplayable_type = ChapterEntry::getActualClassNameForMorph(Pays::class);

        // Set content.
        $entry->content = $request->input('content');

        // Rules to define media.
        $request->setMediaFromRequest($entry);

        $entry->save();

        return redirect()->route('roleplay.show', $chapter->roleplay)
            ->with('message', 'success|Evénement ajouté avec succès.');
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
