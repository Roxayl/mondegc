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
use Illuminate\Validation\ValidationException;

class RoleplayController extends Controller
{
    public function __construct()
    {
        $this->middleware('feature:roleplay');
    }

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
        return response()->noContent();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        // TODO: Not yet implemented.
        return response()->noContent();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        // TODO: Not yet implemented.
        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @param Roleplay $roleplay
     * @return View
     */
    public function show(Roleplay $roleplay): View
    {
        return view('roleplay.show')->with('roleplay', $roleplay);
    }

    /**
     * Renvoie les résultats d'une recherche de roleplayables, pour l'interface d'ajout d'organisateurs de roleplay.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function roleplayables(Request $request): JsonResponse
    {
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param Roleplay $roleplay
     * @return Response
     */
    public function edit(Roleplay $roleplay): Response
    {
        // TODO: Not yet implemented.
        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Roleplay $roleplay
     * @return Response
     */
    public function update(Request $request, Roleplay $roleplay): Response
    {
        // TODO: Not yet implemented.
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Roleplay $roleplay
     * @return Response
     */
    public function destroy(Roleplay $roleplay): Response
    {
        // TODO: Not yet implemented.
        return response()->noContent();
    }
}
