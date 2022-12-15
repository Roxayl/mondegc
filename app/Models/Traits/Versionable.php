<?php

namespace Roxayl\MondeGC\Models\Traits;

use Mpociot\Versionable\VersionableTrait;
use Roxayl\MondeGC\Models\Version;

trait Versionable
{
    use VersionableTrait;

    /**
     * @var string
     */
    public string $versionClass = Version::class;
}
