<?php

namespace App\Models\Traits;

trait Resourceable
{
    /**
     * Renvoie les ressources d'un modèle ressourçable.
     * @return array<string, float>
     */
    public function getResourcesAttribute(): array
    {
        return $this->resources();
    }
}
