<?php

namespace App\Listeners\Influence;

use App\Events\Contracts\InfluencableEvent;

class GenerateInfluence
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param InfluencableEvent $event
     */
    public function handle(InfluencableEvent $event): void
    {
        $influencable = $event->getInfluencable();
        $influencable->generateInfluence();
    }
}
