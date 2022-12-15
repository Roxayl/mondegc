<?php

namespace Roxayl\MondeGC\Http\Controllers;

use Roxayl\MondeGC\Services\LegacyPageService;
use Illuminate\Contracts\View\View;

class MapController extends Controller
{
    /**
     * @return View
     */
    public function explore(): View
    {
        $mapScript = LegacyPageService::carteGenerale();

        return view('map.explore', compact(['mapScript']));
    }
}
