<?php

namespace Roxayl\MondeGC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Roxayl\MondeGC\Models\OrganisationMember;

class OrganisationMemberPermissionChanged extends Notification
{
    use Queueable;

    /**
     * @var OrganisationMember
     */
    private OrganisationMember $organisationMember;

    /**
     * @var string
     */
    private string $action;

    /**
     * @var array|string[]
     */
    private array $availableActions = ['accepted', 'promotedAdministrator'];

    /**
     * Create a new notification instance.
     *
     * @param  OrganisationMember  $organisationMember
     * @param  string  $action
     */
    public function __construct(OrganisationMember $organisationMember, string $action)
    {
        if(! in_array($action, $this->availableActions, true)) {
            throw new \InvalidArgumentException("Mauvais type d'action.");
        }

        $this->organisationMember = $organisationMember;
        $this->action = $action;
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
            'action' => $this->action,
        ];
    }
}
