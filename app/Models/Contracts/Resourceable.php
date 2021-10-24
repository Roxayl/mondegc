<?php

namespace App\Models\Contracts;

interface Resourceable
{
    /**
     * Renvoie les ressources d'un modèle ressourçable.
     * @return array<string, float>
     */
    public function resources(): array;

    /**
     * Renvoie les ressources apportées par un {@link Chapitre chapitre de roleplay} d'un modèle ressourçable.
     * @return array<string, float>
     */
    public function roleplayResources(): array;
}
