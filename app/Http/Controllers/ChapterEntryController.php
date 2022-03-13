<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterEntry\ManageResource;
use App\Models\Chapter;
use App\Models\ChapterEntry;
use App\Models\CustomUser;
use App\Services\StringBladeService;
use App\View\Components\Blocks\RoleplayableSelector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
        $entry = new ChapterEntry();
        $entry->setRelation('chapter', $chapter);
        $this->authorize('create', $entry);

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
        $entry = new ChapterEntry();
        $entry->setRelation('chapter', $chapter);
        $entry->chapter_id = $chapter->getKey();
        $this->authorize('create', $entry);

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

        // Définir la relation.
        $entry->setRelation('chapter', $chapter);
        $entry->chapter_id = $chapter->getKey();

        // Vérifier que le chapitre est actif.
        if(! $entry->chapter->isCurrent()) {
            throw ValidationException::withMessages(["Ce chapitre n'est plus actif."]);
        }

        $this->authorize('create', $entry);

        // Définir le roleplayable associé.
        $roleplayable = RoleplayableSelector::createRoleplayableFromForm($request);
        /** @var CustomUser $user */
        $user = auth()->user();
        if(! $user->hasRoleplayable($roleplayable)) {
            throw ValidationException::withMessages(["Vous ne pouvez pas créer un post avec cette entité."]);
        }
        $entry->roleplayable_id = $roleplayable->getKey();
        $entry->roleplayable_type = get_class($roleplayable);

        // Récupérer le titre et le contenu.
        $entry->fill($request->only($entry->getFillable()));

        // Gérer l'intégration d'un média.
        $request->setMediaFromRequest($entry);

        $entry->save();

        return redirect(route('roleplay.show', $entry->chapter->roleplay)
                . '#chapter-' . $entry->chapter->identifier)
            ->with('message', 'success|Evénement ajouté avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ChapterEntry  $entry
     * @return Response
     */
    public function edit(ChapterEntry $entry): Response
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
    public function update(Request $request, ChapterEntry $entry): Response
    {
        return response()->noContent();
    }

    /**
     * Affiche le formulaire de suppression d'une entrée de chapitre.
     *
     * @param  ChapterEntry  $entry
     * @return View
     */
    public function delete(ChapterEntry $entry): View
    {
        $this->authorize('manage', $entry);

        return view('chapter-entry.delete')->with('entry', $entry);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ChapterEntry  $entry
     * @return RedirectResponse
     */
    public function destroy(ChapterEntry $entry): RedirectResponse
    {
        $this->authorize('manage', $entry);

        $chapter = $entry->chapter;
        $roleplay = $chapter->roleplay;

        $entry->delete();

        return redirect(route('roleplay.show', $roleplay) . '#chapter-' . $chapter->identifier)
            ->with('message', 'success|Entrée supprimée avec succès.');
    }
}
