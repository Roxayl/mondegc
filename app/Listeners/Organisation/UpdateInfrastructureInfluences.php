<?php

namespace App\Listeners\Organisation;

use App\Events\Organisation\MembershipChanged;
use App\Events\Organisation\TypeMigrated;

class UpdateInfrastructureInfluences
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
     * @param MembershipChanged|TypeMigrated $event
     * @return void
     */
    public function handle($event)
    {
        $organisation = $event->organisation;

        $infrastructures = $organisation->infrastructures;

        foreach($infrastructures as $infrastructure) {
            $infrastructure->generateInfluence();
        }
    }
}
