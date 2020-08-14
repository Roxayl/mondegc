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
        return view('organisation.create')->with('pays', $pays);
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

        return redirect()->route('organisation.showslug',
              $organisation->showRouteParameter())
            ->with(['message' => "success|L'organisation a été créée avec succès !"]);
    }

    /**
     * Display the specified resource
     */
    public function show($id, $slug = null)
    {
        $organisation = Organisation::with(['members', 'membersPending', 'communiques'])
            ->findOrFail($id);

        if(is_null($slug) || $organisation->slug !== Str::slug($slug)) {
            return redirect()->route('organisation.showslug',
                $organisation->showRouteParameter());
        }

        $members_invited = collect();
        if(auth()->check()) {
            $members_invited = $organisation->membersInvited(auth()->user())->get();
        }

        return view('organisation.show', compact(['organisation', 'members_invited']));
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

        return view('organisation.edit')->with('organisation', $organisation);
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
        return redirect()->route('organisation.edit', ['organisation' => $id])
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
