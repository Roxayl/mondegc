<?php

namespace Roxayl\MondeGC\Events\Pays;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Roxayl\MondeGC\Events\Contracts\InfluencableEvent;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Pays;

class MapUpdated implements InfluencableEvent
{
    use Dispatchable, SerializesModels;

    public Pays $pays;

    /**
     * Create a new event instance.
     *
     * @param Pays $pays
     */
    public function __construct(Pays $pays)
    {
        $this->pays = $pays;
    }

    public function getInfluencable(): Influencable
    {
        return $this->pays->getMapManager();
    }
}
