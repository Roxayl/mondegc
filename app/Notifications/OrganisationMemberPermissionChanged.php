<?php

namespace Roxayl\MondeGC\Notifications;

use Roxayl\MondeGC\Models\OrganisationMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrganisationMemberPermissionChanged extends Notification
{
    use Queueable;

    private ?OrganisationMember $organisation_member;
    private array $availableActions = ["accepted", "promotedAdministrator"];
    private ?string $action;

    /**
     * Create a new notification instance.
     *
     * @param OrganisationMember $organisation_member
     * @param $action
     */
    public function __construct(OrganisationMember $organisation_member, $action)
    {
        $this->organisation_member = $organisation_member;
        if(!in_array($action, $this->availableActions))
            throw new \InvalidArgumentException("Mauvais type d'action.");
        $this->action = $action;
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
            'organisation_member_id' => $this->organisation_member->id,
            'action' => $this->action,
        ];
    }
}
