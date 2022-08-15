<?php

namespace App\Http\Controllers;

use App\Models\Contracts\Influencable;
use App\Models\Factories\InfluencableFactory;
use App\Services\HelperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use YlsIdeas\FeatureFlags\Facades\Features;

class BackOfficeController extends Controller
{
    private function checkAuthorization(): void
    {
        if(! auth()->user()?->hasMinPermission('ocgc')) {
            abort(403);
        }
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

    /**
     * @return RedirectResponse
     */
    public function regenerateInfluences(): RedirectResponse
    {
        $this->checkAuthorization();

        /** @var Influencable[] $influencables */
        $influencables = InfluencableFactory::list();

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
