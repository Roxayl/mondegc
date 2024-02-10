<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Listeners\Notification;

use Illuminate\Support\Facades\Notification;
use Roxayl\MondeGC\Events\Infrastructure\InfrastructureJudged;
use Roxayl\MondeGC\Notifications\InfrastructureJudged as NotificationInfrastructureJudged;

class SendInfraJudgementNotification
{
    /**
     * Handle the event.
     *
     * @param  InfrastructureJudged  $event
     */
    public function handle(InfrastructureJudged $event): void
    {
        $infrastructure = $event->infrastructure;
        $infrastructurable = $infrastructure->infrastructurable;

        if ($infrastructure->ch_inf_statut === $infrastructure::JUGEMENT_PENDING
          || is_null($infrastructurable)) {
            return;
        }

        $users = $infrastructurable->getUsers();

        Notification::send($users, new NotificationInfrastructureJudged($infrastructure));
    }
}
