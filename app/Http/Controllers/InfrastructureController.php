<?php

namespace App\Http\Controllers;

use App\Models\Infrastructure;
use App\Models\InfrastructureGroupe;
use App\Models\InfrastructureOfficielle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
     * @param string $infrastructurable_type
     * @param int $infrastructurable_id
     * @return View
     */
    public function selectGroup(string $infrastructurable_type, int $infrastructurable_id): View
    {
        $infrastructure = new Infrastructure();
        $infrastructure->fill([
            'infrastructurable_type' =>
                $infrastructure::getMorphFromUrlParameter($infrastructurable_type),
            'infrastructurable_id' => $infrastructurable_id,
        ]);

        $infrastructure_groupes = InfrastructureGroupe::orderBy('order')->get();

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        return view('infrastructure.select_group',
            compact(['infrastructure', 'infrastructure_groupes']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param string $infrastructurable_type
     * @param int $infrastructurable_id
     * @return View
     */
    public function create(Request $request, string $infrastructurable_type, int $infrastructurable_id): View
    {
        $infrastructure = new Infrastructure();
        $infrastructure->fill([
            'infrastructurable_type' =>
                $infrastructure::getMorphFromUrlParameter($infrastructurable_type),
            'infrastructurable_id' => $infrastructurable_id,
        ]);

        $infrastructureGroupe = InfrastructureGroupe::findOrFail(
            $request->input('infrastructure_groupe_id'));

        $infrastructureOfficielle = null;
        if($request->has('infrastructure_officielle_id')) {
            $infrastructureOfficielle = InfrastructureOfficielle::findOrFail(
                $request->input('infrastructure_officielle_id'));
        }

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        return view('infrastructure.create',
            compact(['infrastructure', 'infrastructureGroupe',
                     'infrastructureOfficielle']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $infrastructure = new Infrastructure();
        $infrastructure->fill($request->except(['_method', '_token']));

        $infrastructure->ch_inf_label = 'infrastructure';
        $infrastructure->ch_inf_villeid = $infrastructure->infrastructurable_id;
        $infrastructure->ch_inf_statut = Infrastructure::JUGEMENT_PENDING;
        $infrastructure->user_creator = auth()->user()->ch_use_id;
        $infrastructure->ch_inf_juge = null;
        $infrastructure->ch_inf_commentaire_juge = null;
        $infrastructure->infrastructurable_type = Infrastructure
            ::getMorphFromUrlParameter($request->input('infrastructurable_type'));

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        $infrastructure->save();

        return redirect($infrastructure->infrastructurable->backAccessorUrl()
            . '#infrastructures')
            ->with(['message' => 'success|Infrastructure créée avec succès !']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $infrastructure = Infrastructure::findOrFail($id);

        $infrastructureOfficielle = InfrastructureOfficielle::findOrFail(
            $infrastructure->infrastructure_officielle->first()->ch_inf_off_id);

        $infrastructureGroupe = InfrastructureGroupe::findOrFail(
            $infrastructure->infrastructure_officielle
                ->infrastructure_groupe->first()->id);

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        return view('infrastructure.edit',compact([
            'infrastructure', 'infrastructureOfficielle', 'infrastructureGroupe']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $infrastructure = Infrastructure::findOrFail($id);
        $infrastructure->fill($request->except(['_method', '_token']));

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        $infrastructure->update();

        return redirect($infrastructure->infrastructurable->backAccessorUrl()
            . '#infrastructures')
            ->with(['message' => 'success|Infrastructure modifiée avec succès !']);
    }

    /**
     * Affiche le formulaire de confirmation pour supprimer une infra.
     *
     * @param int $id
     * @return View
     */
    public function delete(int $id): View
    {
        $infrastructure = Infrastructure::findOrFail($id);

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        return view('infrastructure.delete', compact(['infrastructure']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $infrastructure = Infrastructure::findOrFail($id);

        $this->authorize('manageInfrastructure', $infrastructure->infrastructurable);

        $infrastructure->delete();

        return redirect($infrastructure->infrastructurable->backAccessorUrl()
            . '#infrastructures')
            ->with(['message' => "success|L'infrastructure a été supprimée."]);
    }
}
