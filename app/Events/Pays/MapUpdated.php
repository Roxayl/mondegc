<?php

namespace App\Events\Pays;

use App\Events\Contracts\InfluencableEvent;
use App\Models\Contracts\Influencable;
use App\Models\Pays;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
