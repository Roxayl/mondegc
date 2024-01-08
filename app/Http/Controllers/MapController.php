<?php

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Roxayl\MondeGC\Services\LegacyPageService;

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
