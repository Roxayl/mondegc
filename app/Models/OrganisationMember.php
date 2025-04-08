<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;
use Roxayl\MondeGC\Notifications\OrganisationMemberInvited;
use Roxayl\MondeGC\Notifications\OrganisationMemberJoined;
use Roxayl\MondeGC\Notifications\OrganisationMemberPermissionChanged;
use Roxayl\MondeGC\Notifications\OrganisationMemberQuit;

/**
 * Class OrganisationMember.
 *
 * @property int $id
 * @property int|null $organisation_id
 * @property int $permissions
 * @property int|null $pays_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Organisation $organisation
 * @property Pays $pays
 *
 * @method static Builder|OrganisationMember newModelQuery()
 * @method static Builder|OrganisationMember newQuery()
 * @method static Builder|OrganisationMember query()
 * @method static Builder|OrganisationMember whereCreatedAt($value)
 * @method static Builder|OrganisationMember whereId($value)
 * @method static Builder|OrganisationMember whereOrganisationId($value)
 * @method static Builder|OrganisationMember wherePaysId($value)
 * @method static Builder|OrganisationMember wherePermissions($value)
 * @method static Builder|OrganisationMember whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class OrganisationMember extends Model
{
    protected $table = 'organisation_members';

    protected $casts = [
        'organisation_id' => 'int',
        'permissions' => 'int',
        'pays_id' => 'int',
    ];

    protected $fillable = [
        'organisation_id',
        'permissions',
        'pays_id',
    ];

    /**
     * @return BelongsTo
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    /**
     * @return BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pays_id', 'ch_pay_id');
    }

    /**
     * @return string
     */
    public function getPermissionLabel(): string
    {
        return match ($this->permissions) {
            Organisation::$permissions['owner'] => 'Propriétaire',
            Organisation::$permissions['administrator'] => 'Administrateur',
            Organisation::$permissions['member'] => 'Membre',
            Organisation::$permissions['pending'] => 'En attente de validation',
            Organisation::$permissions['invited'] => 'Invitation envoyée',
            default => throw new \InvalidArgumentException(
                'Mauvais type de permission.'),
        };
    }

    /**
     * @param  Model|null  $oldMember
     */
    public function sendNotifications(?Model $oldMember = null): void
    {
        $oldPermission = ! is_null($oldMember) ?
            (int) $oldMember->permissions : $this->permissions;

        // Pays en attente de validation rejetée.
        // Notifiés : Dirigeants du pays.
        if ($oldPermission === Organisation::$permissions['pending'] && ! $this->exists) {
            $pays = Pays::find($this->pays_id);
            $organisation = Organisation::find($this->organisation_id);
            $users = $pays->users;
            Notification::send($users,
                new OrganisationMemberQuit($pays, $organisation));
        }

        // Pays en attente de validation acceptée, ou invitation acceptée.
        // Notifiés : Utilisateurs membres de l'organisation.
        elseif (($oldPermission === Organisation::$permissions['pending'] ||
                 $oldPermission === Organisation::$permissions['invited'])
                &&
                 $this->permissions === Organisation::$permissions['member']) {
            $users = Organisation::find(
                $this->organisation_id)->getUsers(Organisation::$permissions['member']);
            Notification::send($users,
                new OrganisationMemberPermissionChanged($this, 'accepted'));
        }

        // Pays en attente de validation, à modérer par un administateur.
        // Notifiés : Administrateurs de l'organisation.
        elseif ($this->permissions === Organisation::$permissions['pending']) {
            $users = Organisation::find($this->organisation_id)->getUsers();
            Notification::send($users, new OrganisationMemberJoined($this));
        }

        // Pays invité à rejoindre l'organisation.
        // Notifiés : Utilisateurs gérant le pays invité.
        elseif ($this->permissions === Organisation::$permissions['invited']) {
            $pays = Pays::find($this->pays_id);
            $users = $pays->users;
            Notification::send($users, new OrganisationMemberInvited($this));
        }

        // Pays devenu administrateur.
        // Notifiés : Utilisateurs membres de l'organisation.
        elseif ($oldPermission >= Organisation::$permissions['member']
            && $this->permissions === Organisation::$permissions['administrator']) {
            $users = Organisation::find(
                $this->organisation_id)->getUsers(Organisation::$permissions['member']);
            Notification::send($users,
                new OrganisationMemberPermissionChanged($this,
                    'promotedAdministrator'));
        }

        // Pays ayant quitté l'organisation.
        // Notifiés : Utilisateurs membres de l'organisation.
        elseif (! $this->exists) {
            $users = Organisation::find(
                $this->organisation_id)->getUsers(Organisation::$permissions['member']);
            $pays = Pays::find($this->pays_id);
            $organisation = Organisation::find($this->organisation_id);
            Notification::send($users,
                new OrganisationMemberQuit($pays, $organisation));
        }
    }
}
