<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Mpociot\Versionable\Version;

class VersionController extends Controller
{
    /**
     * Permet de restaurer un modèle versionable à une version antérieure.
     *
     * @param Version $version Version vers laquelle restaurer le modèle.
     */
    public function revert(Version $version): RedirectResponse
    {
        DB::transaction(function() use ($version) {

            // Modèle versionné.
            $model = $version->getModel();

            $this->authorize('revert', $model);

            $oldReason = $version->reason;

            $version->revert();

            // Enregistre la raison de la modification.
            $latestVersion = Version::query()->latest()->first();
            $latestVersion->reason = "Retour à la version : " . $oldReason;
            $latestVersion->save();

        });

        return redirect()->back()->with('message', 'success|Modèle restauré avec succès.');
    }
}
