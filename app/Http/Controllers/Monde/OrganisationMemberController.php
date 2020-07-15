<?php

namespace App\Http\Controllers\Monde;

use App\Models\Organisation;
use App\Models\OrganisationMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrganisationMemberController extends Controller
{

    public function __construct() {

        $this->middleware('require-ajax', ['only' => ['joinOrganisation']]);

    }

    public function joinOrganisation(Request $request, $organisation_id) {

        $organisation = Organisation::with('members')->findOrFail($organisation_id);
        $pays = auth()->user()->pays;
        return view('organisation.member.create_modal', compact(['organisation', 'pays']));

    }

    public function store(Request $request, $organisation_id) {

        $organisation = Organisation::with('members')->findOrFail($organisation_id);
        $data = [
            'pays_id' => $request->get('pays_id'),
            'permissions' => $request->get('permissions'),
        ];
        OrganisationMember::create($data);
        return redirect()->back()
            ->with('message', "success|Le pays a rejoint l'organisation avec succès !");

    }

    public function destroy(Request $request, $organisation_id) {



    }

}
