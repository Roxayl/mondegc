<?php

namespace App\Notifications;

use App\Models\Infrastructure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if($this->accepted)
            $message = 'Une de vos infrastructures a été acceptée ' .
                       'par les juges tempérants.';
        else
            $message = 'Une de vos infrastructures a été refusée ' .
                       'par les juges tempérants.';
        return (new MailMessage)
                ->line($message)
                ->action("Voir l'infrastructure", url('/'))
                ->line('À bientôt sur le Monde GC.');
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
