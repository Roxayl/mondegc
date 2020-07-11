<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\OrganisationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganisationController extends Controller
{

    public function index($id, $slug) {

        $thisOrganisation = Organisation::findOrFail($id);

        if(Str::slug($thisOrganisation->name) !== Str::slug($slug)) {
            return redirect("organisation/{$thisOrganisation->id}-" .
                    Str::slug($thisOrganisation->name));
        }

        $members = OrganisationMember::where('organisation_id', $thisOrganisation->id)->get();

        $page_title = $thisOrganisation->name;
        $seo_description = substr($thisOrganisation->text, 0, 255);
        $content = 'test contenu';
        $title = $thisOrganisation->name;

        return view('organisation.organisationview', compact(
            ['content', 'title', 'page_title', 'seo_description', 'members']));

    }

}
