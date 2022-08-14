<?php

namespace App\Models\Traits;

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

    /**
     * Donne le nom de la clé pour récupérer ou stocker les ressources générées dans le cache.
     *
     * @return string
     */
    public function resourceCacheKey(): string
    {
        return str_replace('\\', '.', $this::class) . '.' . $this->getKey();
    }
}
