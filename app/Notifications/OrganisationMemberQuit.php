<?php

namespace Roxayl\MondeGC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Pays;

class OrganisationMemberQuit extends Notification
{
    use Queueable;

    private ?Pays $pays;
    private ?Organisation $organisation;

    /**
     * Create a new notification instance.
     *
     * @param Pays $pays
     * @param Organisation $organisation
     */
    public function __construct(Pays $pays, Organisation $organisation)
    {
        $this->pays = $pays;
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
            'pays_id' => $this->pays->ch_pay_id,
        ];
    }
}
