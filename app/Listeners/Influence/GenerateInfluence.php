<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Listeners\Influence;

use Roxayl\MondeGC\Events\Contracts\InfluencableEvent;

class GenerateInfluence
{
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
