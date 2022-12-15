<?php

namespace Roxayl\MondeGC\Models\Traits;

use Roxayl\MondeGC\Models\Version;
use Mpociot\Versionable\VersionableTrait;

trait Versionable
{
    use VersionableTrait;

    /**
     * @var string
     */
    public string $versionClass = Version::class;
}
