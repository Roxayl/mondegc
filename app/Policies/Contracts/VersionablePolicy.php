<?php

namespace Roxayl\MondeGC\Policies\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

interface VersionablePolicy
{
    /**
     * Détermine, pour un modèle versionable, si l'utilisateur peut rétablir une version ancienne du modèle.
     *
     * @param Authenticatable $user
     * @param Model $model
     * @return bool
     */
    public function revert(Authenticatable $user, Model $model): bool;
}
