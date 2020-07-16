<?php

namespace App\Http\Controllers\Monde;

use App\Models\Organisation;
use App\Models\OrganisationMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class OrganisationMemberController extends Controller
{

    public function __construct() {

        $this->middleware('require-ajax', ['only' => ['joinOrganisation', 'edit']]);

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

        $organisation = Organisation::with('members')->findOrFail($organisation_id);

        // Vérifier si le membre existe déjà
        $memberAlreadyExists = (bool)$organisation->membersAll()
            ->where('pays_id', '=', $request->get('pays_id'))->get()->count();
        if($memberAlreadyExists) {
            return redirect()->route('organisation.showslug', ['id' => $organisation_id,
                'slug' => Str::slug($organisation->name)])
                ->with('message', "error|Ce pays est déjà membre de cette organisation ou
                        vous avez déjà formulé une demande d'adhésion.");
        }

        $data = [
            'organisation_id' => $organisation_id,
            'pays_id' => $request->get('pays_id'),
            'permissions' => Organisation::$permissions['pending'],
        ];
        OrganisationMember::create($data);
        return redirect()->back()
            ->with('message', "success|Le pays a rejoint l'organisation avec succès !");

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

        // $this->authorize('update', $orgMember);

        $data = [
            'permissions' => $request->permissions,
        ];
        $orgMember->update($data);

        return redirect()->route('organisation.showslug',
            ['id' => $orgMember->organisation_id,
             'slug' => Str::slug($orgMember->organisation->name)])
            ->with('message', 'success|Le pays a été modifié avec succès !');

    }

    /*
     * Accepter une demande d'adhésion à une organisation.
     */
    public function accept(Request $request, $id) {

        throw new \InvalidArgumentException("Not yet implemented.");

    }

    /*
     * Supprimer un membre d'une organisation ou sa demande d'adhésion.
     */
    public function destroy(Request $request, $id) {

        throw new \InvalidArgumentException("Not yet implemented.");

    }

}
