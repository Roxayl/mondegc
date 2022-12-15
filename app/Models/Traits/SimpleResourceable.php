<?php

namespace Roxayl\MondeGC\Models\Traits;

trait SimpleResourceable
{
    /**
     * Renvoie les ressources d'un modèle ressourçable.
     *
     * @return array<string, float>
     */
    public function getResourcesAttribute(): array
    {
        return $this->resources();
    }
}
