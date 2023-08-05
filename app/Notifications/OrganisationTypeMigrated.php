<?php

namespace Roxayl\MondeGC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Roxayl\MondeGC\Models\Organisation;

class OrganisationTypeMigrated extends Notification
{
    use Queueable;

    /**
     * @var Organisation
     */
    private Organisation $organisation;

    /**
     * Create a new notification instance.
     *
     * @param  Organisation  $organisation
     */
    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  object  $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  object  $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return [
            'organisation_id' => $this->organisation->id,
            'type' => $this->organisation->type,
        ];
    }
}
