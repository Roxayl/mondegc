<?php

namespace Roxayl\MondeGC\Listeners\Influence;

use Roxayl\MondeGC\Events\Contracts\InfluencableEvent;

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
