<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Events\Patrimoine;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Roxayl\MondeGC\Events\Contracts\InfluencableEvent;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Patrimoine;

class PatrimoineCategorized implements InfluencableEvent
{
    use Dispatchable, SerializesModels;

    public Patrimoine $patrimoine;

    /**
     * Create a new event instance.
     *
     * @param  Patrimoine  $patrimoine
     */
    public function __construct(Patrimoine $patrimoine)
    {
        $this->patrimoine = $patrimoine;
    }

    public function getInfluencable(): Influencable
    {
        return $this->patrimoine;
    }
}
