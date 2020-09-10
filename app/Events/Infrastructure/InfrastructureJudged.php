<?php

namespace App\Events\Infrastructure;

use App\Events\Contracts\InfluencableEvent;
use App\Models\Contracts\Influencable;
use App\Models\Infrastructure;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InfrastructureJudged implements InfluencableEvent
{
    use Dispatchable, SerializesModels;

    public ?Infrastructure $infrastructure;

    /**
     * Create a new event instance.
     *
     * @param Infrastructure $infrastructure
     */
    public function __construct(Infrastructure $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function getInfluencable() : Influencable
    {
        return $this->infrastructure;
    }
}
