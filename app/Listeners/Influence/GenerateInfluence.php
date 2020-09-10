<?php

namespace App\Listeners\Influence;

use App\Events\Contracts\InfluencableEvent;

class GenerateInfluence
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param InfluencableEvent $event
     * @return void
     */
    public function handle(InfluencableEvent $event)
    {
        $influencable = $event->getInfluencable();
        $influencable->generateInfluence();
    }
}
