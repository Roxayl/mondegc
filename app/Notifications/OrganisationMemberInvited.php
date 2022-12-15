<?php

namespace Roxayl\MondeGC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Roxayl\MondeGC\Models\OrganisationMember;

class OrganisationMemberInvited extends Notification
{
    use Queueable;

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
            'organisation_member_id' => $this->organisation_member->id
        ];
    }
}
