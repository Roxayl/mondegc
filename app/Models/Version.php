<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Mpociot\Versionable\Version as BaseVersion;

class Version extends BaseVersion
{
    /**
     * @param Builder $query
     * @param string $type
     * @param int|string $id
     * @return Builder
     */
    public function scopeForVersionable(Builder $query, string $type, int|string $id): Builder
    {
        return $query
            ->where('versionable_id', $id)
            ->where('versionable_type', $type);
    }

    /**
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
     * @return Version|null
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
