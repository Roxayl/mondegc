<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Roxayl\MondeGC\Models\OrganisationMember;

class OrganisationMemberInvited extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param  OrganisationMember  $organisationMember
     */
    public function __construct(private readonly OrganisationMember $organisationMember)
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
            'organisation_member_id' => $this->organisationMember->id,
        ];
    }
}
