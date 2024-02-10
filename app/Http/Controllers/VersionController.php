<?php

declare(strict_types=1);

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
     * @param  Version  $version  Version vers laquelle restaurer le modèle.
     */
    public function revert(Version $version): RedirectResponse
    {
        DB::transaction(function () use ($version): void {
            // Modèle versionné.
            /** @var Model|Versionable $model */
            $model = $version->getModel();

            $this->authorize('revert', $model);

            if ($version->isLast()) {
                throw ValidationException::withMessages(['Cette version est déjà la dernière.']);
            }

            if (! empty($version->reason)) {
                $reason = 'Retour à la version : ' . $version->reason;
            } else {
                $reason = 'Retour à la version du ' . $version->created_at->format('d/m/Y à H:i');
            }

            $version->revert();

            // Enregistre la raison de la modification.
            $latestVersion = Version::query()->latest()->first();
            $latestVersion->reason = $reason;
            $latestVersion->save();
        });

        return redirect()->back()->with('message', 'success|Modèle restauré avec succès.');
    }
}
