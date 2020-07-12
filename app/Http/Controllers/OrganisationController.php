<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\OrganisationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganisationController extends Controller
{

    public function index($id, $slug) {

        $organisation = Organisation::with('members')->findOrFail($id);

        if(Str::slug($organisation->name) !== Str::slug($slug)) {
            return redirect("organisation/{$organisation->id}-" .
                    Str::slug($organisation->name));
        }

        $page_title = $organisation->name;
        $seo_description = substr($organisation->text, 0, 255);
        $content = $organisation->text;
        $title = $organisation->name;

        return view('organisation.organisationview', compact(
            ['content', 'title', 'page_title', 'seo_description',
             'organisation']));

    }

}
