<?php

namespace App\Notifications;

use App\Models\OrganisationMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganisationMemberJoined extends Notification
{
    use Queueable;

    /**
     * @var OrganisationMember|null
     */
    private ?OrganisationMember $organisation_member;

    /**
     * Create a new notification instance.
     *
     * @param OrganisationMember $organisation_member
     */
    public function __construct(OrganisationMember $organisation_member)
    {
        $this->organisation_member = $organisation_member;
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
        return (new MailMessage)
                    ->line("Un nouveau pays a formulé une demande d'admission
                        à une organisation.")
                    ->action("Voir l'organisation", route('organisation.show',
                        ['organisation' => $this->organisation_member->organisation_id])
                            . '#membres')
                    ->line('À bientôt dans le Monde GC.');
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
            'organisation_id' => $this->organisation_member->organisation_id,
            'pays_id' => $this->organisation_member->pays_id
        ];
    }
}
