<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Events\Infrastructure;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Roxayl\MondeGC\Events\Contracts\InfluencableEvent;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Infrastructure;

class InfrastructureJudged implements InfluencableEvent
{
    use Dispatchable, SerializesModels;

    public Infrastructure $infrastructure;

    /**
     * Create a new event instance.
     *
     * @param  Infrastructure  $infrastructure
     */
    public function __construct(Infrastructure $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function getInfluencable(): Influencable
    {
        return $this->infrastructure;
    }
}
