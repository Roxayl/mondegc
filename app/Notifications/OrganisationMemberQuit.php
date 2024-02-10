<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Pays;

class OrganisationMemberQuit extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param  Pays  $pays
     * @param  Organisation  $organisation
     */
    public function __construct(private readonly Pays $pays, private readonly Organisation $organisation)
    {
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
            'pays_id' => $this->pays->ch_pay_id,
        ];
    }
}
