<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Contracts\View\View;
use Roxayl\MondeGC\Services\LegacyPageService;

class MapController extends Controller
{
    /**
     * @param  LegacyPageService  $legacyPageService
     * @return View
     */
    public function explore(LegacyPageService $legacyPageService): View
    {
        $mapScript = $legacyPageService->carteGenerale();

        return view('map.explore', compact(['mapScript']));
    }
}
