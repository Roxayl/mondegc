<?php

namespace App\Http\Controllers;

use App\Models\Contracts\Roleplayable;
use App\Models\Factories\RoleplayableFactory;
use App\Models\Roleplay;
use App\Services\StringBladeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoleplayController extends Controller
{
    /**
     * Créé une instance d'un roleplayable à partir des paramètre passés dans la requête {@see Request}.
     * Il est nécessaire que les champs "type" et "id" soient présents dans les paramètres de la requête.
     *
     * @param Request $request
     * @return Roleplayable
     * @throws ValidationException
     */
    private static function createRoleplayableFromForm(Request $request): Roleplayable
    {
        $form = $request->all();

        /** @var Roleplayable|null $roleplayable */
        $roleplayable = RoleplayableFactory::find($form['type'], $form['id']);

        if($roleplayable === null) {
            throw ValidationException::withMessages(["Ce roleplayable n'existe pas."]);
        }

        return $roleplayable;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        // TODO: Not yet implemented.
        $this->authorize('display', Roleplay::class);

        return response()->noContent();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $this->authorize('create', Roleplay::class);

        $roleplay = new Roleplay();

        return view('roleplay.create')->with('roleplay', $roleplay);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Roleplay::class);

        $request->validate(Roleplay::validationRules);

        DB::beginTransaction();

        $roleplay = new Roleplay();
        $roleplay->user_id = auth()->user()->getAuthIdentifier();
        $roleplay->fill($request->only($roleplay->getFillable()));
        $roleplay->save();

        /** @var Roleplayable|null $organizer */
        $organizer = RoleplayableFactory::find($request->input('type'), $request->input('id'));
        if(! $organizer) {
            throw ValidationException::withMessages(["Cette entité n'existe pas."]);
        }
        $roleplay->addOrganizer($organizer);

        DB::commit();

        return redirect()->route('roleplay.show', $roleplay)
            ->with('message', 'success|Roleplay créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Roleplay $roleplay
     * @return View
     */
    public function edit(Roleplay $roleplay): View
    {
        $this->authorize('display', Roleplay::class);

        return view('roleplay.edit')->with('roleplay', $roleplay);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Roleplay $roleplay
     * @return RedirectResponse
     */
    public function update(Request $request, Roleplay $roleplay): RedirectResponse
    {
        $this->authorize('manage', $roleplay);

        $roleplay->fill($request->only($roleplay->getFillable()));

        $roleplay->save();

        return redirect()->route('roleplay.show', $roleplay)->with(
            'message', 'success|Roleplay modifié avec succès.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Roleplay $roleplay
     * @return View
     */
    public function show(Roleplay $roleplay): View
    {
        $this->authorize('display', Roleplay::class);

        $chapters = $roleplay->chapters()->orderByDesc('order')->get();

        return view('roleplay.show', compact('roleplay', 'chapters'));
    }

    /**
     * Affiche la page de confirmation de la suppression de roleplay.
     *
     * @param Roleplay $roleplay
     * @return View
     */
    public function delete(Roleplay $roleplay): View
    {
        $this->authorize('display', Roleplay::class);

        return view('roleplay.delete')->with('roleplay', $roleplay);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Roleplay $roleplay
     * @return RedirectResponse
     */
    public function destroy(Roleplay $roleplay): RedirectResponse
    {
        $this->authorize('display', Roleplay::class);

        $roleplay->delete();

        return redirect()->to(url('index.php'))
            ->with('message', 'success|Roleplay supprimé avec succès.');
    }

    /**
     * @param Roleplay $roleplay
     * @return View
     */
    public function confirmClose(Roleplay $roleplay): View
    {
        $this->authorize('manage', $roleplay);

        return view('roleplay.confirm-close')->with('roleplay', $roleplay);
    }

    /**
     * @param Roleplay $roleplay
     * @return RedirectResponse
     */
    public function close(Roleplay $roleplay): RedirectResponse
    {
        $this->authorize('manage', $roleplay);

        $roleplay->close();

        $roleplay->save();

        return redirect()->route('roleplay.show', $roleplay)
            ->with('message', 'success|Ce roleplay a été fermé.');
    }

    /**
     * Renvoie les résultats d'une recherche de roleplayables, pour l'interface d'ajout d'organisateurs de roleplay.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function roleplayables(Request $request): JsonResponse
    {
        $this->authorize('display', Roleplay::class);

        $type   = $request->input('type');
        $term   = $request->input('term');

        $roleplayables = RoleplayableFactory::list($type, $term);

        $result = [];
        foreach($roleplayables as $roleplayable) {
            $result[] = [
                'id'    => $roleplayable->getKey(),
                'value' => $roleplayable->getKey(),
                'label' => $roleplayable->getName(),
            ];
        }

        return response()->json($result);
    }

    /**
     * Donne le contenu HTML du bloc listant les organisateurs du roleplay.
     *
     * @param Roleplay $roleplay
     * @param StringBladeService $stringBlade
     * @return Response
     */
    public function organizers(Roleplay $roleplay, StringBladeService $stringBlade): Response
    {
        $this->authorize('display', Roleplay::class);

        $blade = '<x-roleplay.organizers :roleplay="$roleplay" />';

        $html = $stringBlade->render(
            $blade, compact('roleplay')
        );

        return response($html);
    }

    /**
     * Affiche l'interface de gestion des organisateurs de roleplay.
     *
     * @param Roleplay $roleplay
     * @param StringBladeService $stringBlade
     * @return Response
     */
    public function manageOrganizers(Roleplay $roleplay, StringBladeService $stringBlade): Response
    {
        $this->authorize('manage', $roleplay);

        $blade = '<x-roleplay.manage-organizers :roleplay="$roleplay" />';

        $html = $stringBlade->render(
            $blade, compact('roleplay')
        );

        return response($html);
    }

    /**
     * Affiche l'interface d'ajout d'organisateurs de roleplay.
     *
     * @param Roleplay $roleplay
     * @param StringBladeService $stringBlade
     * @return Response
     */
    public function addOrganizer(Roleplay $roleplay, StringBladeService $stringBlade): Response
    {
        $this->authorize('manage', $roleplay);

        $blade = '<x-roleplay.add-organizer :roleplay="$roleplay" />';

        $html = $stringBlade->render(
            $blade, compact('roleplay')
        );

        return response($html);
    }

    /**
     * Ajoute un organisateur à un roleplay, et redirige vers la page du roleplay.
     *
     * @param Roleplay $roleplay
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrganizer(Roleplay $roleplay, Request $request): RedirectResponse
    {
        $this->authorize('manage', $roleplay);

        $roleplayable = self::createRoleplayableFromForm($request);

        if($roleplay->hasOrganizer($roleplayable)) {
            throw ValidationException::withMessages(["C'est déjà un organisateur de ce roleplay."]);
        }

        $roleplay->addOrganizer($roleplayable);

        return redirect()->route('roleplay.show', $roleplay)
            ->with('message', "success|Cet organisateur a été ajouté avec succès.");
    }

    /**
     * Supprime un organisateur du roleplay, et redirige vers la page du roleplay.
     *
     * @param Roleplay $roleplay
     * @param Request $request
     * @return RedirectResponse
     */
    public function removeOrganizer(Roleplay $roleplay, Request $request): RedirectResponse
    {
        $this->authorize('manage', $roleplay);

        $roleplayable = self::createRoleplayableFromForm($request);

        if(! $roleplay->hasOrganizer($roleplayable)) {
            throw ValidationException::withMessages(["Ce n'est pas un organisateur de ce roleplay."]);
        }

        if($roleplay->organizers()->count() <= 1) {
            throw ValidationException::withMessages(["Il doit y avoir au moins un organisateur."]);
        }

        $roleplay->removeOrganizer($roleplayable);

        return redirect()->route('roleplay.show', $roleplay)
            ->with('message', "success|Cet organisateur a été retiré avec succès.");
    }
}
