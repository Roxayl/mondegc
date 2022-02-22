<?php

namespace App\Events\Patrimoine;

use App\Events\Contracts\InfluencableEvent;
use App\Models\Contracts\Influencable;
use App\Models\Patrimoine;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PatrimoineCategorized implements InfluencableEvent
{
    use Dispatchable, SerializesModels;

    public Patrimoine $patrimoine;

    /**
     * Create a new event instance.
     *
     * @param Patrimoine $patrimoine
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
