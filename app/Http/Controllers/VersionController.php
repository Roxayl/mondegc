<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Reliese\Database\Eloquent\Model;
use Roxayl\MondeGC\Models\Traits\Versionable;
use Roxayl\MondeGC\Models\Version;
use Roxayl\MondeGC\Services\VersionDiffService;

class VersionController extends Controller
{
    /**
     * Compare deux versions d'un modèle versionable.
     *
     * @param  VersionDiffService  $diffService
     * @param  Version  $version1
     * @param  Version|null  $version2
     * @return View
     */
    public function diff(VersionDiffService $diffService, Version $version1, ?Version $version2 = null): View
    {
        /** @var Model&Versionable $model1 */
        /** @var Model&Versionable $model2 */
        $model1 = $version1->getModel();

        $this->authorize('viewDiff', $model1);

        if ($version2 === null) {
            $model2 = new ($model1::class);
        } else {
            $model2 = $version2->getModel();
            if (get_class($model1) !== get_class($model2)) {
                throw new \InvalidArgumentException('Provided versions not compatible.');
            }
        }

        $diffs = $diffService->generate($model1, $model2);

        return view('version.diff', compact('diffs'));
    }

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
