<?php

namespace App\Http\Controllers;

use App\Events\Organisation\MembershipChanged;
use App\Models\Organisation;
use App\Models\OrganisationMember;
use App\Models\Pays;
use Illuminate\Http\Request;

class OrganisationMemberController extends Controller
{

    public function __construct()
    {
        $this->middleware('require-ajax', ['only' => ['joinOrganisation', 'edit']]);
    }

    private function checkMemberAlreadyExists(Request $request, Organisation $organisation)
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

    /*
     * Formulaire pour rejoindre une organisation.
     */
    public function joinOrganisation(Request $request, $organisation_id) {

        $organisation = Organisation::with('members')->findOrFail($organisation_id);
        $pays = auth()->user()->pays;
        return view('organisation.member.join', compact(['organisation', 'pays']));

    }

    /*
     * Ajouter un nouveau membre.
     */
    public function store(Request $request, $organisation_id) {

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

    public function invite(Request $request, $organisation_id) {

        $organisation = Organisation::with('members')->findOrFail($organisation_id);
        $pays = Pays::where('ch_pay_publication', '=', Pays::$statut['active'])->get();
        return view('organisation.member.invite', compact(['organisation', 'pays']));

    }

    public function sendInvitation(Request $request, $organisation_id) {

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

    /*
     * Formulaire de modif des permissions d'un membre.
     */
    public function edit(Request $request, $id) {

        $orgMember = OrganisationMember::with(['pays', 'organisation'])->findOrFail($id);

        $this->authorize('update', $orgMember);

        return view('organisation.member.edit', compact(['orgMember']));

    }

    /*
     * Mettre à jour les permissions d'un membre d'une organisation.
     * Ne s'applique pas aux propriétaires.
     */
    public function update(Request $request, $id) {

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
     */
    public function delete(Request $request, $id) {

        $orgMember = OrganisationMember::with(['pays', 'organisation'])->findOrFail($id);

        $this->authorize('quit', $orgMember);

        return view('organisation.member.delete')->with('orgMember', $orgMember);

    }

    /*
     * Supprimer un membre d'une organisation ou sa demande d'adhésion.
     */
    public function destroy(Request $request, $id) {

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
