<?php

namespace Roxayl\MondeGC\Listeners\Organisation;

use Roxayl\MondeGC\Events\Organisation\MembershipChanged;
use Roxayl\MondeGC\Events\Organisation\TypeMigrated;

class UpdateInfrastructureInfluences
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
     * @param MembershipChanged|TypeMigrated $event
     */
    public function handle($event): void
    {
        $organisation = $event->organisation;

        $infrastructures = $organisation->infrastructures;

        foreach($infrastructures as $infrastructure) {
            $infrastructure->generateInfluence();
        }
    }
}
