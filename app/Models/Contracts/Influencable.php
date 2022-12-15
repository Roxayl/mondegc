<?php

namespace Roxayl\MondeGC\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

interface Influencable
{
    /**
     * @return MorphMany Relation entre l'influencable et les influences.
     */
    public function influences(): MorphMany;

    /**
     * Créé les entrées ou met à jour l'influence générée par l'influencable en fonction
     * de règles spécifiques pour chaque influencable.
     */
    public function generateInfluence(): void;

    /**
     * Obtient les ressources générées au moment actuel, par l'influencable.
     * @return Collection Collection contenant les ressources générées par l'influencable.
     */
    public function getGeneratedResources(): Collection;

    /**
     * Obtient les ressources générées par l'influencable, en prenant en compte l'ensemble
     * des influences créées.
     * @return Collection Collection contenant les ressources générées par l'influencable.
     */
    public function getFinalResources(): Collection;

    /**
     * Supprime les anciennes entrées dans la table 'influences', lorsqu'on veut générer
     * à nouveau l'influence d'un influencable, par exemple.
     * @param \Closure $f Fonction de vérification, qui doit renvoyer 'true' lorsque les
     *                    influences peuvent être supprimées.
     * @return bool Renvoie <code>true</code> lorsque les influences ont été supprimées ;
     *              <code>false</code> sinon.
     */
    public function removeOldInfluenceRows(\Closure $f): bool;

    /**
     * Donne le rendement actuel, en pourcentage, de l'influencable actuel.
     * @return int Taux de rendement sur 100.
     */
    public function efficiencyRate(): int;
}
