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
}
