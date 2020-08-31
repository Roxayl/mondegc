<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use App\Models\Organisation;
use App\Models\OrganisationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
    public function create(Request $request)
    {
        $this->authorize('create', Organisation::class);
        $organisation = new Organisation();

        $pays = auth()->user()->pays;

        $type = $request->has('type') ? $request->get('type') : null;

        return view('organisation.create', compact(['organisation', 'pays', 'type']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $this->authorize('create', Organisation::class);

        $organisation = new Organisation();
        $organisation->fill($request->except(['_method', '_token']));

        $type = $request->input('type');
        if(!in_array($type, Organisation::$types))
            throw new \InvalidArgumentException("Mauvais type d'organisation.");
        $organisation->type = $type;

        $organisation->save();

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
        $organisation = Organisation::with(['members', 'membersPending'])
            ->findOrFail($id);

        if(is_null($slug) || $organisation->slug !== Str::slug($slug)) {
            return redirect()->route('organisation.showslug',
                $organisation->showRouteParameter());
        }

        $communiques = $organisation->communiques();
        if(Gate::denies('administrate', $organisation)) {
            // Affiche seulement les communiqués publiés, si l'utilisateur n'a pas les
            // permissions pour administrer l'organisation.
            $communiques = $communiques->where('ch_com_statut', Communique::STATUS_PUBLISHED);
        }
        $communiques = $communiques->paginate(10);

        $members_invited = collect();
        if(auth()->check()) {
            $members_invited = $organisation->membersInvited(auth()->user())->get();
        }

        return view('organisation.show', compact(
            ['organisation', 'communiques', 'members_invited']));
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
