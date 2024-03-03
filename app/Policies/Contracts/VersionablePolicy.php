<?php

namespace Roxayl\MondeGC\Policies\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

interface VersionablePolicy
{
    /**
     * Détermine si un utilisateur peut afficher la diff d'un modèle versionable donné.
     *
     * @param  Authenticatable|null  $user
     * @param  Model  $model
     * @return bool
     */
    public function viewDiff(?Authenticatable $user, Model $model): bool;

    /**
     * Détermine, pour un modèle versionable, si l'utilisateur peut rétablir une version ancienne du modèle.
     *
     * @param  Authenticatable  $user
     * @param  Model  $model
     * @return bool
     */
    public function revert(Authenticatable $user, Model $model): bool;
}
