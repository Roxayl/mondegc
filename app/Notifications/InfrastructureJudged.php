<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Roxayl\MondeGC\Models\Infrastructure;

class InfrastructureJudged extends Notification
{
    use Queueable;

    /**
     * @var bool
     */
    private bool $accepted;

    /**
     * Create a new notification instance.
     *
     * @param  Infrastructure  $infrastructure
     */
    public function __construct(private readonly Infrastructure $infrastructure)
    {
        $this->accepted = $this->infrastructure->ch_inf_statut === Infrastructure::JUGEMENT_ACCEPTED;
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return [
            'infrastructure_id' => $this->infrastructure->ch_inf_id,
            'accepted' => $this->accepted,
        ];
    }
}
