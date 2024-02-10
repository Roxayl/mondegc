<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Mpociot\Versionable\Version;
use Roxayl\MondeGC\Models\Managers\PaysSubdivisionManager;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Services\VersionDiffService;

class PaysController extends Controller
{
    /**
     * @param  Pays  $pays
     * @param  string  $paysSlug
     * @return RedirectResponse
     */
    public function show(Pays $pays, string $paysSlug): RedirectResponse
    {
        $routeParameter = $pays->showRouteParameter();

        if ($paysSlug !== $routeParameter['paysSlug']) {
            return to_route('pays.show', $routeParameter);
        }

        return redirect('page-pays.php?ch_pay_id=' . $pays->ch_pay_id);
    }

    /**
     * @param  Request  $request
     * @param  PaysSubdivisionManager  $subdivisionManager
     * @param  Pays  $pays
     * @return RedirectResponse
     */
    public function manageSubdivisions(
        Request $request,
        PaysSubdivisionManager $subdivisionManager,
        Pays $pays,
    ): RedirectResponse {
        $enable = $request->get('enable');

        if ($enable) {
            $subdivisionManager->enable($pays);
        } else {
            $subdivisionManager->disable($pays);
        }

        return redirect('back/page_pays_back.php?ch_pay_id=' . $pays->ch_pay_id)
            ->with('message', 'success|Vos subdivisions administratives ont été modifiées avec succès.');
    }

    /**
     * @param  Pays  $pays
     * @return View
     */
    public function history(Pays $pays): View
    {
        $versions = $pays->versions()->latest('version_id')->paginate();
        $canRevert = Gate::allows('revert', $pays);
        $title = 'Historique du pays ' . $pays->ch_pay_nom;
        $diffRoute = 'pays.diff';
        $breadcrumb = view('pays.components.history-breadcrumb', compact('pays'));

        return view(
            'version.history',
            compact('title', 'breadcrumb', 'versions', 'canRevert', 'diffRoute')
        );
    }

    /**
     * Compare deux versions d'un pays.
     *
     * @param  VersionDiffService  $diffService
     * @param  Version  $version1
     * @param  Version|null  $version2
     * @return View
     */
    public function diff(VersionDiffService $diffService, Version $version1, ?Version $version2 = null): View
    {
        $this->authorize('viewAny', Pays::class);

        /** @var Pays $model1 */
        /** @var Pays $model2 */
        $model1 = $version1->getModel();
        if ($version2 === null) {
            $model2 = new ($model1::class);
        } else {
            $model2 = $version2->getModel();
        }

        $diffs = $diffService->generate($model1, $model2);

        return view('version.diff', compact('diffs'));
    }
}
