<?php

namespace Roxayl\MondeGC\Models\Contracts;

interface SimpleResourceable
{
    /**
     * Renvoie les ressources d'un modèle ressourçable.
     *
     * @return array<string, float>
     */
    public function resources(): array;

    /**
     * Renvoie les ressources d'un modèle ressourçable.
     *
     * @return array<string, float>
     */
    public function getResourcesAttribute(): array;
}
