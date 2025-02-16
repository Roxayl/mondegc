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
     * @param  MembershipChanged|TypeMigrated  $event
     */
    public function handle(MembershipChanged|TypeMigrated $event): void
    {
        $organisation = $event->organisation;

        $infrastructures = $organisation->infrastructures;

        foreach ($infrastructures as $infrastructure) {
            $infrastructure->generateInfluence();
        }
    }
}
