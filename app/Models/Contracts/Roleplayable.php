<?php

namespace App\Models\Contracts;

use Illuminate\Support\Collection;

interface Roleplayable
{
    /**
     * Donne le nom du modèle roleplayable.
     * @return string
     */
    public function getName(): string;

    /**
     * Donne le drapeau du modèle roleplayable.
     * @return string URL vers l'image du drapeau.
     */
    public function getFlag(): string;

    /**
     * Donne le lien d'accès vers le modèle roleplayable.
     * @return string URL vers la page du modèle.
     */
    public function accessorUrl(): string;

    /**
     * Renvoie le type du modèle roleplayable.
     * @return string
     */
    public function getType(): string;

    /**
     * Renvoie l'intitulé de la colonne de la table donnant le nom du modèle.
     * @return string
     */
    public static function getNameColumn(): string;

    /**
     * Renvoie les propriétaires de ce modèle roleplayable.
     * @return Collection
     */
    public function getUsers(): Collection;
}
