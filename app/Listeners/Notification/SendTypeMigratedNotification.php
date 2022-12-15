<?php

namespace Roxayl\MondeGC\Listeners\Notification;

use Illuminate\Support\Facades\Notification;
use Roxayl\MondeGC\Events\Organisation\TypeMigrated;
use Roxayl\MondeGC\Notifications\OrganisationTypeMigrated;

class SendTypeMigratedNotification
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
     * @param  TypeMigrated  $event
     */
    public function handle(TypeMigrated $event): void
    {
        $organisation = $event->organisation;

        $users = $organisation->getUsers();

        Notification::send($users, new OrganisationTypeMigrated($organisation));
    }
}
