<?php

namespace Roxayl\MondeGC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Roxayl\MondeGC\Models\Pays;

class PaysRegistered extends Notification
{
    use Queueable;

    private ?Pays $pays;

    /**
     * Create a new notification instance.
     *
     * @param Pays $pays
     */
    public function __construct(Pays $pays)
    {
        $this->pays = $pays;
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
            'pays_id' => $this->pays->ch_pay_id
        ];
    }
}
