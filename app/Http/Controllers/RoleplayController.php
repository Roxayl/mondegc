<?php

namespace App\Http\Controllers;

use App\Models\Roleplay;
use App\Services\StringBladeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleplayController extends Controller
{
    public function __construct()
    {
        $this->middleware('feature:roleplay');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // TODO: Not yet implemented.
        return response()->noContent();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // TODO: Not yet implemented.
        return response()->noContent();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // TODO: Not yet implemented.
        return response()->noContent();
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
        // TODO: Not yet implemented.
        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Roleplay $roleplay
     * @return Response
     */
    public function update(Request $request, Roleplay $roleplay)
    {
        // TODO: Not yet implemented.
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Roleplay $roleplay
     * @return Response
     */
    public function destroy(Roleplay $roleplay)
    {
        // TODO: Not yet implemented.
        return response()->noContent();
    }
}
