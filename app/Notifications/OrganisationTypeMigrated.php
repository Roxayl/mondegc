<?php

namespace Roxayl\MondeGC\Notifications;

use Roxayl\MondeGC\Models\Organisation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrganisationTypeMigrated extends Notification
{
    use Queueable;

    private ?Organisation $organisation;

    /**
     * Create a new notification instance.
     *
     * @param Organisation $organisation
     */
    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'organisation_id' => $this->organisation->id,
            'type' => $this->organisation->type,
        ];
    }
}
