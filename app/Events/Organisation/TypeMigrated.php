<?php

namespace App\Events\Organisation;

use App\Models\Organisation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TypeMigrated
{
    use Dispatchable, SerializesModels;

    public ?Organisation $organisation;

    /**
     * Create a new event instance.
     *
     * @param Organisation $organisation
     */
    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }
}
