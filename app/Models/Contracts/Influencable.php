<?php

namespace App\Models\Contracts;

use Illuminate\Support\Collection;

interface Influencable
{
    /**
     * @return mixed Relation entre l'influencable et les influences.
     */
    public function influences();

    /*
     * Créé les entrées ou met à jour l'influence générée par l'influencable en fonction
     * de règles spécifiques pour chaque influencable.
     */
    public function generateInfluence() : void;

    /**
     * Obtient les ressources générées au moment actuel, par l'influencable.
     * @return Collection Collection contenant les ressources générées par l'influencable.
     */
    public function getGeneratedResources() : Collection;

    /**
     * Obtient les ressources générées par l'influencable, en prenant en compte l'ensemble
     * des influences créées.
     * @return Collection Collection contenant les ressources générées par l'influencable.
     */
    public function getFinalResources() : Collection;

    /**
     * Donne le rendement actuel, en pourcentage, de l'influencable actuel.
     * @return int Taux de rendement sur 100.
     */
    public function efficiencyRate() : int;
}
