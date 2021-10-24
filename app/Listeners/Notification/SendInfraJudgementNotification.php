<?php

namespace App\Listeners\Notification;

use App\Events\Infrastructure\InfrastructureJudged;
use App\Notifications\InfrastructureJudged as NotificationInfrastructureJudged;
use Illuminate\Support\Facades\Notification;

class SendInfraJudgementNotification
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
     * @param InfrastructureJudged $event
     */
    public function handle(InfrastructureJudged $event): void
    {
        $infrastructure = $event->infrastructure;
        $infrastructurable = $infrastructure->infrastructurable;

        if( $infrastructure->ch_inf_statut === $infrastructure::JUGEMENT_PENDING
          || is_null($infrastructurable) ) {
            return;
        }

        $users = $infrastructurable->getUsers();

        Notification::send($users, new NotificationInfrastructureJudged($infrastructure));
    }
}
