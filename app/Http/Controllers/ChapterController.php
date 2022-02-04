<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Roleplay;
use App\Services\StringBladeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware('feature:roleplay');
    }

    /**
     * @param Roleplay $roleplay
     * @param StringBladeService $stringBlade
     * @return Response
     */
    public function createButton(Roleplay $roleplay, StringBladeService $stringBlade): Response
    {
        $blade = '<x-chapter.create-button :roleplay="$roleplay" />';

        $html = $stringBlade->render(
            $blade, compact('roleplay')
        );

        return response($html);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Roleplay $roleplay
     * @param StringBladeService $stringBlade
     * @return Response
     */
    public function create(Roleplay $roleplay, StringBladeService $stringBlade): Response
    {
        $blade = '<x-chapter.create-chapter :roleplay="$roleplay" />';

        $html = $stringBlade->render(
            $blade, compact('roleplay')
        );

        return response($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Roleplay $roleplay
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Roleplay $roleplay, Request $request): RedirectResponse
    {
        $chapter = new Chapter();

        // Gérer les relations.
        $chapter->setRelation('roleplay', $roleplay);
        $chapter->roleplay_id = $roleplay->getKey();
        $chapter->user_id = auth()->user()->getAuthIdentifier();

        // Gérer les accès à la création de chapitre.
        $this->authorize('manage', $chapter);

        // Valider et remplir les champs saisis dans le formulaire.
        $request->validate(Chapter::validationRules);
        $chapter->fill($request->only(['name', 'summary', 'content']));

        // Enregistrer le modèle. Les champs "starting_date", "ending_date", et "order" sont définis à partir
        // de l'événement "creating" dispatché par le modèle (voir \App\Models\Chapter::boot()).
        $chapter->save();

        return redirect()->route('roleplay.show', $roleplay)
            ->with('message', 'success|Chapitre ajouté avec succès !');
    }

    /**
     * Display the specified resource.
     *
     * @param  Chapter  $chapter
     * @return Response
     */
    public function show(Chapter $chapter): Response
    {
        return response()->noContent();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Chapter $chapter
     * @return Response
     */
    public function edit(Chapter $chapter): Response
    {
        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Chapter $chapter
     * @return Response
     */
    public function update(Request $request, Chapter $chapter): Response
    {
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Chapter $chapter
     * @return Response
     */
    public function destroy(Chapter $chapter): Response
    {
        return response()->noContent();
    }
}
