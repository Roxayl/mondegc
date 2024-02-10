<?php

namespace Roxayl\MondeGC\Models\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Resourceable extends SimpleResourceable
{
    /**
     * Renvoie les ressources apportées par un {@link Chapitre chapitre de roleplay} d'un modèle ressourçable.
     *
     * @return array<string, float>
     */
    public function roleplayResources(): array;

    /**
     * Donne le nom du modèle ressourçable.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Donne le drapeau du modèle ressourçable.
     *
     * @return string URL vers l'image du drapeau.
     */
    public function getFlag(): string;

    /**
     * Donne le lien d'accès vers le modèle ressourçable.
     *
     * @return string URL vers la page du modèle.
     */
    public function accessorUrl(): string;

    /**
     * Affiche uniquement les modèles non-supprimés.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeVisible(Builder $query): Builder;

    /**
     * Donne le nom de la clé pour récupérer ou stocker les ressources générées dans le cache.
     *
     * @param  null  $parameters
     * @return string
     */
    public function resourceCacheKey($parameters): string;
}
