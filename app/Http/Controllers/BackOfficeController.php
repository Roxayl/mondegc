<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Roxayl\MondeGC\Services\HelperService;
use Roxayl\MondeGC\Services\RegenerateInfluenceService;
use YlsIdeas\FeatureFlags\Manager;

class BackOfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function(Request $request, \Closure $next): mixed {
            if(! auth()->user()?->hasMinPermission('ocgc')) {
                abort(403);
            }

            return $next($request);
        });
    }

    /**
     * @param Manager $featureManager
     * @param RegenerateInfluenceService $regenerateInfluenceService
     * @return View
     */
    public function advancedParameters(
        Manager $featureManager,
        RegenerateInfluenceService $regenerateInfluenceService
    ): View {
        $cacheSize = HelperService::formatBytes(HelperService::directorySize(
            storage_path('framework/cache/data')
        ));

        $cacheEnabled = $featureManager->accessible('cache');

        $influenceTableSize = $regenerateInfluenceService->influenceCount();

        return view('back-office.advanced-parameters', compact(
            'cacheSize', 'cacheEnabled', 'influenceTableSize'
        ));
    }

    /**
     * @return RedirectResponse
     */
    public function purgeCache(): RedirectResponse
    {
        cache()->flush();

        return redirect()->route('back-office.advanced-parameters')
            ->with('message', 'success|Le cache a été purgé avec succès.');
    }

    /**
     * @param RegenerateInfluenceService $regenerateInfluenceService
     * @return RedirectResponse
     */
    public function regenerateInfluences(RegenerateInfluenceService $regenerateInfluenceService): RedirectResponse
    {
        $regenerateInfluenceService->regenerate();

        return redirect()->route('back-office.advanced-parameters')
            ->with('message', 'success|Les influences ont été regénérées avec succès.');
    }
}
