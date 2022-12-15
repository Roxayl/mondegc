<?php

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Roxayl\MondeGC\Events\Organisation\MembershipChanged;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\OrganisationMember;
use Roxayl\MondeGC\Models\Pays;

class OrganisationMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('require-ajax', ['only' => ['joinOrganisation', 'edit']]);
    }

    /**
     * @param Request $request
     * @param Organisation $organisation
     */
    private function checkMemberAlreadyExists(Request $request, Organisation $organisation): void
    {
        $pays_id = $request->get('pays_id');

        // Vérifier si le membre existe déjà
        $memberAlreadyExists = (bool)$organisation->membersAll()
            ->where('pays_id', '=', $pays_id)
            ->get()
            ->count();
        if($memberAlreadyExists) {
            throw new \InvalidArgumentException("Ce pays est déjà membre de cette "
               . "organisation ou vous avez déjà formulé une demande d'adhésion.");
        }

        // Empêche qu'un pays déjà membre d'une alliance en rejoigne une autre.
        if($organisation->type === $organisation::TYPE_ALLIANCE) {
            $alliance = Pays::find($pays_id)->alliance();
            if(!empty($alliance)) {
                throw new \InvalidArgumentException("Ce pays ne peut pas rejoindre "
                    . "plusieurs alliances. Il est déjà membre de {$alliance->name}.");
            }
        }
    }

    /**
     * Formulaire pour rejoindre une organisation.
     *
     * @param int $organisation_id
     * @return View
     */
    public function joinOrganisation(int $organisation_id): View
    {
        $organisation = Organisation::with('members')->findOrFail($organisation_id);
        $pays = auth()->user()->pays;
        return view('organisation.member.join', compact(['organisation', 'pays']));
    }

    /**
     * Ajouter un nouveau membre.
     *
     * @param Request $request
     * @param int $organisation_id
     * @return RedirectResponse
     */
    public function store(Request $request, int $organisation_id): RedirectResponse
    {
        $organisation = Organisation::findOrFail($organisation_id);

        $this->checkMemberAlreadyExists($request, $organisation);

        $data = [
            'organisation_id' => $organisation_id,
            'pays_id' => $request->get('pays_id'),
            'permissions' => Organisation::$permissions['pending'],
        ];
        $thisMember = OrganisationMember::create($data);

        $thisMember->sendNotifications();

        return redirect()->back()
            ->with('message', "success|Votre demande d'adhésion a été formulée.");
    }

    /**
     * @param int $organisation_id
     * @return View
     */
    public function invite(int $organisation_id): View
    {
        $organisation = Organisation::with('members')->findOrFail($organisation_id);
        $pays = Pays::where('ch_pay_publication', '=', Pays::$statut['active'])->get();
        return view('organisation.member.invite', compact(['organisation', 'pays']));
    }

    /**
     * @param Request $request
     * @param int $organisation_id
     * @return RedirectResponse
     */
    public function sendInvitation(Request $request, int $organisation_id): RedirectResponse
    {
        $organisation = Organisation::findOrFail($organisation_id);

        $this->authorize('administrate', $organisation);
        $this->checkMemberAlreadyExists($request, $organisation);

        $data = [
            'organisation_id' => $organisation_id,
            'pays_id' => $request->get('pays_id'),
            'permissions' => Organisation::$permissions['invited'],
        ];
        /** @var OrganisationMember $thisMember */
        $thisMember = OrganisationMember::create($data);

        $thisMember->sendNotifications();

        return redirect()->back()
            ->with('message', "success|Ce pays a été invité.");
    }

    /**
     * Formulaire de modif des permissions d'un membre.
     *
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function edit(Request $request, int $id): View
    {
        $orgMember = OrganisationMember::with(['pays', 'organisation'])->findOrFail($id);

        $this->authorize('update', $orgMember);

        return view('organisation.member.edit', compact(['orgMember']));
    }

    /**
     * Mettre à jour les permissions d'un membre d'une organisation. Ne s'applique pas aux propriétaires.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $orgMember = OrganisationMember::with(['pays', 'organisation'])->findOrFail($id);

        $this->authorize('update', $orgMember);

        $oldOrgMember = $orgMember->replicate();

        $orgMember->update([
            'permissions' => $request->permissions,
        ]);

        $orgMember->sendNotifications($oldOrgMember);
        event(new MembershipChanged($orgMember->organisation));

        return redirect()->route('organisation.showslug',
            $orgMember->organisation->showRouteParameter())
            ->with('message', 'success|Le pays a été modifié avec succès !');
    }

    /**
     * Afficher la popup de confirmation pour quitter une organisation.
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function delete(Request $request, int $id): View
    {
        $orgMember = OrganisationMember::with(['pays', 'organisation'])->findOrFail($id);

        $this->authorize('quit', $orgMember);

        return view('organisation.member.delete')->with('orgMember', $orgMember);
    }

    /**
     * Supprimer un membre d'une organisation ou sa demande d'adhésion.
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, int $id): RedirectResponse
    {
        $orgMember = OrganisationMember::with(['pays', 'organisation'])->findOrFail($id);

        $this->authorize('quit', $orgMember);

        $oldOrgMember = $orgMember->replicate();

        $orgMember->delete();

        $orgMember->sendNotifications($oldOrgMember);
        event(new MembershipChanged($orgMember->organisation));

        return redirect()->route('organisation.showslug',
            $orgMember->organisation->showRouteParameter())
            ->with('message', "success|Le pays a quitté l'organisation avec succès. Byebye, blackbird!");

    }

}
