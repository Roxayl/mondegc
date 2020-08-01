<?php

namespace App\Http\Controllers;

use App\Services\LegacyPageService;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function explore(Request $request) {

        $mapScript = LegacyPageService::carteGenerale();

        return view('map.explore', compact(['mapScript']));

    }
}
