<?php

namespace Roxayl\MondeGC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Roxayl\MondeGC\Models\Infrastructure;

class InfrastructureJudged extends Notification
{
    use Queueable;

    private ?Infrastructure $infrastructure;
    private bool $accepted;

    /**
     * Create a new notification instance.
     *
     * @param Infrastructure $infrastructure
     */
    public function __construct(Infrastructure $infrastructure)
    {
        $this->infrastructure = $infrastructure;
        $this->accepted = $this->infrastructure->ch_inf_statut == 2;
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
            'infrastructure_id' => $this->infrastructure->ch_inf_id,
            'accepted' => $this->accepted
        ];
    }
}
