<?php

namespace App\Http\Controllers;

use App\Models\Roleplay;
use App\Services\StringBladeService;
use App\View\Components\Roleplay\ManageOrganizers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\Component;

class RoleplayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Roleplay $roleplay
     * @return View
     */
    public function show(Roleplay $roleplay): View
    {
        return view('roleplay.show')->with('roleplay', $roleplay);
    }

    /**
     * @param Roleplay $roleplay
     * @param StringBladeService $stringBlade
     * @return Response
     */
    public function organizers(Roleplay $roleplay, StringBladeService $stringBlade): Response
    {
        $blade = '<x-roleplay.organizers :roleplay="$roleplay" />';

        $html = $stringBlade->render(
            $blade, compact('roleplay')
        );

        return response($html);
    }

    /**
     * Affiche l'interface de gestion des organisateurs de roleplay.
     *
     * @param Roleplay $roleplay
     * @param StringBladeService $stringBlade
     * @return Response
     */
    public function manageOrganizers(Roleplay $roleplay, StringBladeService $stringBlade): Response
    {
        $blade = '<x-roleplay.manage-organizers :roleplay="$roleplay" />';

        $html = $stringBlade->render(
            $blade, compact('roleplay')
        );

        return response($html);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Roleplay $roleplay
     * @return Response
     */
    public function edit(Roleplay $roleplay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Roleplay $roleplay
     * @return Response
     */
    public function update(Request $request, Roleplay $roleplay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Roleplay $roleplay
     * @return Response
     */
    public function destroy(Roleplay $roleplay)
    {
        //
    }
}
