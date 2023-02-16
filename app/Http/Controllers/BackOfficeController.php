<?php

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Roxayl\MondeGC\Services\HelperService;
use YlsIdeas\FeatureFlags\Facades\Features;

class BackOfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function() {
            if(! auth()->user()?->hasMinPermission('ocgc')) {
                abort(403);
            }
        });
    }

    /**
     * @return View
     */
    public function advancedParameters(): View
    {
        $this->checkAuthorization();

        $cacheSize = HelperService::formatBytes(HelperService::directorySize(
            storage_path('framework/cache/data')
        ));

        $cacheEnabled = Features::accessible('cache');

        return view('back-office.advanced-parameters', compact('cacheSize', 'cacheEnabled'));
    }

    /**
     * @return RedirectResponse
     */
    public function purgeCache(): RedirectResponse
    {
        $this->checkAuthorization();

        cache()->flush();

        return redirect()->route('back-office.advanced-parameters')
            ->with('message', 'success|Le cache a été purgé avec succès.');
    }
}
