<?php

namespace App\Http\Controllers;

use App\Services\HelperService;
use Illuminate\Http\RedirectResponse;
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
}
