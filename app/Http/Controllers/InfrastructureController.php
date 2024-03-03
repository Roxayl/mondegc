<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Roxayl\MondeGC\Models\Infrastructure;
use Roxayl\MondeGC\Models\InfrastructureGroupe;
use Roxayl\MondeGC\Models\InfrastructureOfficielle;

class InfrastructureController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $infrastructure = Infrastructure::with('infrastructurable')->findOrFail($id);

        return response()->json($infrastructure->toArray());
    }

    /**
     * @param  string  $infrastructurableType
     * @param  int  $infrastructurableId
     * @return View
     */
    public function selectGroup(string $infrastructurableType, int $infrastructurableId): View
    {
        $infrastructure = new Infrastructure();
        $infrastructure->fill([
            'infrastructurable_type' => $infrastructure::getMorphFromUrlParameter($infrastructurableType),
            'infrastructurable_id' => $infrastructurableId,
        ]);

        $infrastructure_groupes = InfrastructureGroupe::query()->orderBy('order')->get();

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        return view('infrastructure.select_group', compact([
            'infrastructure', 'infrastructure_groupes',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @param  string  $infrastructurableType
     * @param  int  $infrastructurableId
     * @return View
     */
    public function create(Request $request, string $infrastructurableType, int $infrastructurableId): View
    {
        $infrastructure = new Infrastructure();
        $infrastructure->fill([
            'infrastructurable_type' => $infrastructure::getMorphFromUrlParameter($infrastructurableType),
            'infrastructurable_id' => $infrastructurableId,
        ]);

        $infrastructureGroupe = InfrastructureGroupe::query()->findOrFail(
            $request->input('infrastructure_groupe_id'));

        $infrastructureOfficielle = null;
        if ($request->has('infrastructure_officielle_id')) {
            $infrastructureOfficielle = InfrastructureOfficielle::query()->find(
                $request->input('infrastructure_officielle_id')
            );
        }

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        return view('infrastructure.create', compact([
            'infrastructure', 'infrastructureGroupe', 'infrastructureOfficielle',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $infrastructure = new Infrastructure();
        $infrastructure->fill($request->all());

        $infrastructure->ch_inf_label = 'infrastructure';
        $infrastructure->ch_inf_villeid = $infrastructure->infrastructurable_id;
        $infrastructure->ch_inf_statut = Infrastructure::JUGEMENT_PENDING;
        $infrastructure->user_creator = auth()->user()->getAuthIdentifier();
        $infrastructure->ch_inf_juge = null;
        $infrastructure->ch_inf_commentaire_juge = null;
        $infrastructure->infrastructurable_type = Infrastructure::getMorphFromUrlParameter(
            $request->input('infrastructurable_type'));

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        $infrastructure->save();

        return redirect($infrastructure->infrastructurable->backAccessorUrl() . '#infrastructures')
            ->with('message', 'success|Infrastructure créée avec succès !');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit(int $id): View
    {
        $infrastructure = Infrastructure::query()->findOrFail($id);

        $infrastructureOfficielle = InfrastructureOfficielle::query()->findOrFail(
            $infrastructure->infrastructureOfficielle?->ch_inf_off_id
        );

        $infrastructureGroupe = InfrastructureGroupe::query()->findOrFail(
            $infrastructure->infrastructureOfficielle?->infrastructureGroupe->first()?->id
        );

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        return view('infrastructure.edit', compact([
            'infrastructure', 'infrastructureOfficielle', 'infrastructureGroupe',
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $infrastructure = Infrastructure::query()->findOrFail($id);
        $infrastructure->fill($request->all());

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        $infrastructure->update();

        return redirect($infrastructure->infrastructurable->backAccessorUrl() . '#infrastructures')
            ->with('message', 'success|Infrastructure modifiée avec succès !');
    }

    /**
     * Affiche le formulaire de confirmation pour supprimer une infrastructure.
     *
     * @param  int  $id
     * @return View
     */
    public function delete(int $id): View
    {
        $infrastructure = Infrastructure::query()->findOrFail($id);

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        return view('infrastructure.delete', compact(['infrastructure']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $infrastructure = Infrastructure::query()->findOrFail($id);

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        $infrastructure->delete();

        return redirect($infrastructure->infrastructurable->backAccessorUrl() . '#infrastructures')
            ->with('message', "success|L'infrastructure a été supprimée.");
    }

    /**
     * @param  Infrastructure  $infrastructure
     * @return View
     */
    public function history(Infrastructure $infrastructure): View
    {
        $versions = $infrastructure->versions()->latest('version_id')->paginate();
        $canRevert = Gate::allows('revert', $infrastructure);
        $title = "Historique de l'infrastructure $infrastructure->ch_inf_label";
        $breadcrumb = null;

        return view(
            'version.history',
            compact('title', 'breadcrumb', 'versions', 'canRevert')
        );
    }
}
