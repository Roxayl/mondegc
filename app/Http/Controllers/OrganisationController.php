<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\OrganisationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrganisationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect('politique.php#organisations');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Organisation::class);

        $pays = auth()->user()->pays;

        $title = 'Créer une organisation';
        $seo_description = 'Créer une nouvelle organisation de pays au sein du Monde GC.';
        return view('organisation.create', compact(['title', 'seo_description', 'pays']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $this->authorize('create', Organisation::class);

        $organisation = Organisation::create($request->except(['_method', '_token']));

        $memberData = array(
            'organisation_id' => $organisation->id,
            'permissions' => Organisation::$permissions['owner'],
            'pays_id' => $request->pays_id
        );
        $member = OrganisationMember::create($memberData);

        return redirect()->route('organisation.show', ['id' => $organisation->id])
            ->with(['message' => "success|L'organisation a été créée avec succès !"]);
    }

    /**
     * Display the specified resource
     */
    public function show($id, $slug = null)
    {
        $organisation = Organisation::with(['members', 'membersPending', 'communiques'])
            ->findOrFail($id);

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
        $this->authorize('update', $organisation);

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
     */
    public function update(Request $request, $id)
    {
        $organisation = Organisation::with('members')->findOrFail($id);
        $this->authorize('update', $organisation);

        $organisation->allow_temperance = $request->has('allow_temperance');

        $organisation->update($request->except(['_method', '_token']));
        return redirect()->route('organisation.edit', ['id' => $id])
            ->with('message', 'success|Organisation mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        throw new BadRequestHttpException();
    }

}
