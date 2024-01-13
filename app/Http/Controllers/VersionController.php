<?php

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Reliese\Database\Eloquent\Model;
use Roxayl\MondeGC\Models\Traits\Versionable;
use Roxayl\MondeGC\Models\Version;

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
            /** @var Model|Versionable $model */
            $model = $version->getModel();

            $this->authorize('revert', $model);

            if($version->isLast()) {
                throw ValidationException::withMessages(["Cette version est déjà la dernière."]);
            }

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
