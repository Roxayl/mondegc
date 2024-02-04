<?php

namespace Roxayl\MondeGC\Policies\Traits;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\Contracts\Infrastructurable;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Infrastructure;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Ville;

trait ManagesInfrastructures
{
    /**
     * Vérifie si l'utilisateur a les permissions pour gérer les infrastructures
     * d'un infrastructurable spécifié.
     * @param CustomUser $user
     * @param Infrastructurable $infrastructurable
     * @return bool
     */
    public function manageInfrastructure(CustomUser $user, Infrastructurable $infrastructurable): bool
    {
        if($user->hasMinPermission('ocgc')) {
            return true;
        }

        $method = 'check' . Str::ucfirst(
            Infrastructure::getUrlParameterFromMorph(get_class($infrastructurable)));
        return $this->$method($user, $infrastructurable);
    }

    /**
     * @param CustomUser $user
     * @param Organisation $infrastructurable
     * @return bool
     */
    protected function checkOrganisation(CustomUser $user, Organisation $infrastructurable): bool
    {
        if(!$infrastructurable->hasEconomy()) {
            return false;
        }
        return Gate::check('administrate', $infrastructurable);
    }

    /**
     * @param CustomUser $user
     * @param Ville $infrastructurable
     * @return bool
     */
    protected function checkVille(CustomUser $user, Ville $infrastructurable): bool
    {
        $pays = $infrastructurable->pays;
        return $this->checkPays($user, $pays);
    }

    /**
     * @param CustomUser $user
     * @param Pays $infrastructurable
     * @return bool
     */
    protected function checkPays(CustomUser $user, Pays $infrastructurable): bool
    {
        if($user->ownsPays($infrastructurable)) {
            return true;
        }
        return false;
    }
}
