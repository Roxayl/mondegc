<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Roxayl\MondeGC\Models\Ville;

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
        $breadcrumb = view('ville.components.history-breadcrumb', compact('ville'));

        return view(
            'version.history',
            compact('title', 'breadcrumb', 'versions', 'canRevert')
        );
    }
}
