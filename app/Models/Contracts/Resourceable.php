<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Builder;

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

    /**
     * Renvoie les ressources d'un modèle ressourçable.
     * @return array<string, float>
     */
    public function getResourcesAttribute(): array;

    /**
     * Donne le nom du modèle ressourçable.
     * @return string
     */
    public function getName(): string;

    /**
     * Affiche uniquement les modèles non-supprimés.
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeVisible(Builder $query): Builder;
}
