<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mpociot\Versionable\Version as BaseVersion;

/**
 * Roxayl\MondeGC\Models\Version.
 *
 * @property int $version_id
 * @property string $versionable_id
 * @property string $versionable_type
 * @property string|null $user_id
 * @property string $model_data
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed|CustomUser $responsible_user
 * @property-read Model|\Eloquent $versionable
 *
 * @method static Builder|Version forVersionable(string $type, string|int $id)
 * @method static Builder|Version newModelQuery()
 * @method static Builder|Version newQuery()
 * @method static Builder|Version query()
 * @method static Builder|Version whereCreatedAt($value)
 * @method static Builder|Version whereModelData($value)
 * @method static Builder|Version whereReason($value)
 * @method static Builder|Version whereUpdatedAt($value)
 * @method static Builder|Version whereUserId($value)
 * @method static Builder|Version whereVersionId($value)
 * @method static Builder|Version whereVersionableId($value)
 * @method static Builder|Version whereVersionableType($value)
 *
 * @mixin \Eloquent
 */
class Version extends BaseVersion
{
    /**
     * Filtre une requête par versionnable, en fonction de son type et de son identifiant.
     * ({@see \Illuminate\Database\Eloquent\Relations\MorphTo}).
     *
     * @param  Builder  $query
     * @param  string  $type
     * @param  int|string  $id
     * @return Builder
     */
    public function scopeForVersionable(Builder $query, string $type, int|string $id): Builder
    {
        return $query
            ->where('versionable_id', $id)
            ->where('versionable_type', $type);
    }

    /**
     * Détermine si cette version est la première pour le versionnable donné.
     *
     * @return bool
     */
    public function isFirst(): bool
    {
        $minId = $this->query()
            ->forVersionable($this->versionable_type, $this->versionable_id)
            ->min('version_id');

        return $this->getKey() === $minId;
    }

    /**
     * Détermine s'il s'agit de la dernière version (donc la version actuelle) d'un versionnable.
     *
     * @return bool
     */
    public function isLast(): bool
    {
        $minId = $this->query()
            ->forVersionable($this->versionable_type, $this->versionable_id)
            ->max('version_id');

        return $this->getKey() === $minId;
    }

    /**
     * Donne la version, d'un versionnable, qui précède celle-ci.
     *
     * @return Version|null Renvoie la version précédant celle-ci. Si cette version est la première ({@see isLast()}),
     *                      cette méthode renvoie <code>null</code>.
     */
    public function previous(): ?Version
    {
        return $this->query()
            ->forVersionable($this->versionable_type, $this->versionable_id)
            ->where('version_id', '<', $this->getKey())
            ->orderByDesc('version_id')
            ->first();
    }
}
