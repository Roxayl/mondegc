<?php

namespace App\Http\Controllers;

use App\View\Components\Blocks\TextDiff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
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

    /**
     * Affiche la diff entre deux versions.
     *
     * @param Version $version1
     * @param Version|null $version2
     * @param string $key
     * @return Response
     */
    public function diff(Version $version1, ?Version $version2, string $key): Response
    {
        $model1 = $version1->getModel();
        if($version2 === null) {
            $model2 = new ($model1::class);
        } else {
            $model2 = $version2->getModel();
        }

        if(! in_array($key, $model1->getFillable())) {
            throw new \InvalidArgumentException("Mauvais type de clé.");
        }

        $diffComponent = new TextDiff((string)$model2->$key, $model1->$key);

        return response($diffComponent->render());
    }
}
