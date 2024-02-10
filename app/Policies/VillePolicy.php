<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Ville;
use Roxayl\MondeGC\Policies\Contracts\VersionablePolicy;
use Roxayl\MondeGC\Policies\Traits\ManagesInfrastructures;

class VillePolicy implements VersionablePolicy
{
    use HandlesAuthorization, ManagesInfrastructures;

    /**
     * @param CustomUser|null $user
     * @return bool
     */
    public function viewAny(?CustomUser $user): bool
    {
        return true;
    }

    /**
     * @param CustomUser $user
     * @param Ville $ville
     * @return bool
     */
    public function update(CustomUser $user, Ville $ville): bool
    {
        if($user->hasMinPermission('admin')) return true;

        return $ville->getUsers()->contains($user);
    }

    /**
     * @param CustomUser $user
     * @param Ville $ville
     * @return bool
     */
    public function delete(CustomUser $user, Ville $ville): bool
    {
        if($user->hasMinPermission('admin')) return true;

        return $ville->getUsers()->contains($user);
    }

    /**
     * @inheritDoc
     */
    public function revert(CustomUser|Authenticatable $user, Ville|Model $model): bool
    {
        if($user->hasMinPermission('ocgc')) return true;

        return $model->getUsers()->contains($user);
    }
}
