<?php

namespace Roxayl\MondeGC\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Versionable\VersionableTrait;
use Roxayl\MondeGC\Models\Version;

trait Versionable
{
    use VersionableTrait;

    /**
     * @var class-string<Model>
     */
    public string $versionClass = Version::class;

    /**
     * @return array<string>
     */
    public function getDontVersionFields(): array
    {
        return $this->dontVersionFields ?: [];
    }
}
