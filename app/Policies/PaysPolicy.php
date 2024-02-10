<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Policies\Contracts\VersionablePolicy;
use Roxayl\MondeGC\Policies\Traits\ManagesInfrastructures;

class PaysPolicy implements VersionablePolicy
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
     * @param Pays $pays
     * @return bool
     */
    public function update(CustomUser $user, Pays $pays): bool
    {
        if($user->hasMinPermission('ocgc')) return true;

        return $pays->users->contains($user);
    }

    /**
     * @inheritDoc
     */
    public function revert(CustomUser|Authenticatable $user, Pays|Model $model): bool
    {
        return $this->update($user, $model);
    }
}
