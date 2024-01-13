<?php

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Factories\InfluencableFactory;
use Roxayl\MondeGC\Models\Influence;
use Roxayl\MondeGC\Services\HelperService;
use YlsIdeas\FeatureFlags\Facades\Features;

class BackOfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function(Request $request, \Closure $next) {
            if(! auth()->user()?->hasMinPermission('ocgc')) {
                abort(403);
            }

            return $next($request);
        });
    }

    /**
     * @return View
     */
    public function advancedParameters(): View
    {
        $cacheSize = HelperService::formatBytes(HelperService::directorySize(
            storage_path('framework/cache/data')
        ));

        $cacheEnabled = Features::accessible('cache');

        $influenceTableSize = Influence::count();

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
     * @param InfluencableFactory $influencableFactory
     * @return RedirectResponse
     */
    public function regenerateInfluences(InfluencableFactory $influencableFactory): RedirectResponse
    {
        /** @var Influencable[] $influencables */
        $influencables = $influencableFactory->listEnabled();

        DB::transaction(function() use ($influencables) {
            DB::table('influence')->delete();

            foreach($influencables as $influencable) {
                $influencable->generateInfluence();
            }
        });

        cache()->flush();

        return redirect()->route('back-office.advanced-parameters')
            ->with('message', 'success|Les influences ont été regénérées avec succès.');
    }
}
