<?php

namespace App\Listeners\Notification;

use App\Events\Organisation\TypeMigrated;
use App\Notifications\OrganisationTypeMigrated;
use Illuminate\Support\Facades\Notification;

class SendTypeMigratedNotification
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
     * @param  TypeMigrated  $event
     * @return void
     */
    public function handle(TypeMigrated $event)
    {
        $organisation = $event->organisation;

        $users = $organisation->getUsers();

        Notification::send($users, new OrganisationTypeMigrated($organisation));
    }
}
