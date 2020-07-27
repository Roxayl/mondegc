<?php

namespace App\Notifications;

use App\Models\Infrastructure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

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
