<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganisationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index($id, $slug)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource
     */
    public function show($id, $slug = null)
    {

        $organisation = Organisation::with('members')->findOrFail($id);

        if(is_null($slug) || Str::slug($organisation->name) !== Str::slug($slug)) {
            return redirect("organisation/{$organisation->id}-" .
                    Str::slug($organisation->name));
        }

        $page_title = $organisation->name;
        $seo_description = substr($organisation->text, 0, 255);
        $content = $organisation->text;
        $title = $organisation->name;

        return view('organisation.show', compact(
            ['content', 'title', 'page_title', 'seo_description',
             'organisation']));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        $organisation = Organisation::with('members')->findOrFail($id);
        $seo_description = "Modifier la page de l'organisation {$organisation->name}.";
        $title = 'Modifier ' . $organisation->name;

        return view('organisation.edit',
            compact(['organisation', 'title', 'seo_description']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
