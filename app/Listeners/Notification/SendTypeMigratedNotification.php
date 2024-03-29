<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Listeners\Notification;

use Illuminate\Support\Facades\Notification;
use Roxayl\MondeGC\Events\Organisation\TypeMigrated;
use Roxayl\MondeGC\Notifications\OrganisationTypeMigrated;

class SendTypeMigratedNotification
{
    /**
     * Handle the event.
     *
     * @param  TypeMigrated  $event
     */
    public function handle(TypeMigrated $event): void
    {
        $organisation = $event->organisation;

        $users = $organisation->getUsers();

        Notification::send($users, new OrganisationTypeMigrated($organisation));
    }
}
