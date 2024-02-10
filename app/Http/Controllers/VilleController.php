<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Mpociot\Versionable\Version;
use Roxayl\MondeGC\Models\Ville;
use Roxayl\MondeGC\Services\VersionDiffService;

class VilleController extends Controller
{
    /**
     * @param  Ville  $ville
     * @return View
     */
    public function history(Ville $ville): View
    {
        $versions = $ville->versions()->latest('version_id')->paginate();
        $canRevert = Gate::allows('revert', $ville);
        $title = 'Historique de la ville ' . $ville->ch_vil_nom;
        $diffRoute = 'ville.diff';
        $breadcrumb = view('ville.components.history-breadcrumb', compact('ville'));

        return view(
            'version.history',
            compact('title', 'breadcrumb', 'versions', 'canRevert', 'diffRoute')
        );
    }

    /**
     * Compare deux versions d'une ville.
     *
     * @param  VersionDiffService  $diffService
     * @param  Version  $version1
     * @param  Version|null  $version2
     * @return View
     */
    public function diff(VersionDiffService $diffService, Version $version1, ?Version $version2 = null): View
    {
        $this->authorize('viewAny', Ville::class);

        /** @var Ville $model1 */
        /** @var Ville $model2 */
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
