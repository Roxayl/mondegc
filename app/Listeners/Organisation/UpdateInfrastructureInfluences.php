<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Listeners\Organisation;

use Roxayl\MondeGC\Events\Organisation\MembershipChanged;
use Roxayl\MondeGC\Events\Organisation\TypeMigrated;

class UpdateInfrastructureInfluences
{
    /**
     * Handle the event.
     *
     * @param object&(MembershipChanged|TypeMigrated) $event
     */
    public function handle(object $event): void
    {
        $organisation = $event->organisation;

        $infrastructures = $organisation->infrastructures;

        foreach($infrastructures as $infrastructure) {
            $infrastructure->generateInfluence();
        }
    }
}
