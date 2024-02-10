<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Events\Organisation;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Roxayl\MondeGC\Models\Organisation;

class TypeMigrated
{
    use Dispatchable, SerializesModels;

    public Organisation $organisation;

    /**
     * Create a new event instance.
     *
     * @param  Organisation  $organisation
     */
    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }
}
