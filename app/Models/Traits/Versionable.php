<?php

namespace App\Models\Traits;

use App\Models\Version;
use Mpociot\Versionable\VersionableTrait;

trait Versionable
{
    use VersionableTrait;

    /**
     * @var string
     */
    public string $versionClass = Version::class;
}
