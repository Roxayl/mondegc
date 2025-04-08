<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Events\Organisation\TypeMigrated;
use Roxayl\MondeGC\Http\Requests\Organisation\MigrateType;
use Roxayl\MondeGC\Models\Communique;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\OrganisationMember;

class OrganisationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return redirect('politique.php#organisations');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return View
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Organisation::class);
        $organisation = new Organisation();

        $pays = auth()->user()->pays;

        $type = $request->get('type');

        return view('organisation.create', compact(['organisation', 'pays', 'type']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Organisation::class);

        $organisation = new Organisation();
        $organisation->fill($request->all($organisation->getFillable()));

        $type = $request->input('type');
        if (! in_array($type, Organisation::$typesCreatable, true)) {
            throw new \InvalidArgumentException("Mauvais type d'organisation.");
        }
        $organisation->type = $type;

        $organisation->save();

        $memberData = [
            'organisation_id' => $organisation->id,
            'permissions' => Organisation::$permissions['owner'],
            'pays_id' => $request->input('pays_id'),
        ];
        OrganisationMember::create($memberData);

        return redirect()->route('organisation.showslug',
            $organisation->showRouteParameter())
            ->with(['message' => "success|L'organisation a été créée avec succès !"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  string|null  $slug
     * @return View|RedirectResponse
     */
    public function show(int $id, ?string $slug = null): View|RedirectResponse
    {
        /** @var Organisation $organisation */
        $organisation = Organisation::with(['members', 'membersPending'])
            ->findOrFail($id);

        if (is_null($slug) || $organisation->slug !== Str::slug($slug)) {
            return redirect()->route('organisation.showslug',
                $organisation->showRouteParameter());
        }

        $communiques = $organisation->communiques();
        if (Gate::denies('administrate', $organisation)) {
            // Affiche seulement les communiqués publiés, si l'utilisateur n'a pas les
            // permissions pour administrer l'organisation.
            $communiques = $communiques->where('ch_com_statut', Communique::STATUS_PUBLISHED);
        }
        $communiques = $communiques->paginate(10);

        $membersInvited = collect();
        if (auth()->check()) {
            $membersInvited = $organisation->membersInvited(auth()->user())->get();
        }

        return view('organisation.show', compact(
            ['organisation', 'communiques', 'membersInvited'])
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit(int $id): View
    {
        /** @var Organisation $organisation */
        $organisation = Organisation::with('members')->findOrFail($id);
        $this->authorize('update', $organisation);

        $canDelete = Gate::allows('delete', $organisation);

        return view('organisation.edit', compact('organisation', 'canDelete'));
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
        /** @var Organisation $organisation */
        $organisation = Organisation::with('members')->findOrFail($id);
        $this->authorize('update', $organisation);

        $organisation->allow_temperance = false;

        $organisation->update($request->except(['_method', '_token']));

        return redirect()->route('organisation.edit', ['organisation' => $id])
            ->with('message', 'success|Organisation mise à jour avec succès !');
    }

    /**
     * Affiche le formulaire de confirmation de la suppression de l'organisation.
     *
     * @param  Organisation  $organisation
     * @return View
     */
    public function delete(Organisation $organisation): View
    {
        $this->authorize('delete', $organisation);

        return view('organisation.delete')->with('organisation', $organisation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Organisation  $organisation
     * @return RedirectResponse
     */
    public function destroy(Organisation $organisation): RedirectResponse
    {
        $this->authorize('delete', $organisation);

        $organisation->delete();

        return redirect('politique.php')
            ->with('message', "success|L'organisation a été supprimée avec succès.");
    }

    /**
     * Affiche l'interface permettant de migrer le type d'une organisation.
     *
     * @param  int  $id
     * @return View
     */
    public function migrate(int $id): View
    {
        $organisation = Organisation::query()->findOrFail($id);
        $this->authorize('update', $organisation);

        return view('organisation.migrate', compact(['organisation']));
    }

    /**
     * Migre une organisation.
     *
     * @param  MigrateType  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function runMigration(MigrateType $request, int $id): RedirectResponse
    {
        $organisation = Organisation::query()->findOrFail($id);
        $this->authorize('update', $organisation);

        $organisation->type = $request->input('type');
        $organisation->type_migrated_at = Carbon::now();

        $organisation->save();

        event(new TypeMigrated($organisation));

        return redirect()->back()
            ->with('message', 'success|Votre organisation est devenue une '
                . __("organisation.types.$organisation->type")
            );
    }

    /**
     * @param  Organisation  $organisation
     * @return View
     */
    public function history(Organisation $organisation): View
    {
        $versions = $organisation->versions()->latest('version_id')->paginate();
        $canRevert = Gate::allows('revert', $organisation);
        $title = "Historique de l'organisation $organisation->name";
        $breadcrumb = view('organisation.components.history-breadcrumb', compact('organisation'));

        return view(
            'version.history',
            compact('title', 'breadcrumb', 'versions', 'canRevert')
        );
    }
}
