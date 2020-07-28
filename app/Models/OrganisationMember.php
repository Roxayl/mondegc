<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Notifications\OrganisationMemberInvited;
use App\Notifications\OrganisationMemberJoined;
use App\Notifications\OrganisationMemberPermissionChanged;
use App\Notifications\OrganisationMemberQuit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

/**
 * Class OrganisationMember
 * 
 * @property int $id
 * @property int|null $organisation_id
 * @property int $permissions
 * @property int|null $pays_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Organisation $organisation
 * @property Pays $pays
 *
 * @package App\Models
 */
class OrganisationMember extends Model
{
	protected $table = 'organisation_members';

	protected $casts = [
		'organisation_id' => 'int',
		'permissions' => 'int',
		'pays_id' => 'int'
	];

	protected $fillable = [
		'organisation_id',
		'permissions',
		'pays_id'
	];

	public function organisation()
	{
		return $this->belongsTo(Organisation::class, 'organisation_id');
	}

	public function pays()
	{
		return $this->belongsTo(Pays::class, 'pays_id', 'ch_pay_id');
	}

	public function temperance()
    {
	    return $this->hasManyThrough(TemperancePays::class, Pays::class,
            'ch_pay_id',
            'id',
            'pays_id',
            'ch_pay_id');

    }

	public function getPermissionLabel()
    {
        switch($this->permissions) {
            case Organisation::$permissions['owner']:
                $label = 'Propriétaire'; break;
            case Organisation::$permissions['administrator']:
                $label = 'Administrateur'; break;
            case Organisation::$permissions['member']:
                $label = 'Membre'; break;
            case Organisation::$permissions['pending']:
                $label = 'En attente de validation'; break;
            case Organisation::$permissions['invited']:
                $label = 'Invitation envoyée'; break;
            default:
                throw new \InvalidArgumentException(
                    "Mauvais type de permission.");
        }

        return $label;
    }

    public function sendNotifications(Model $oldMember = null) {

        $oldPermission = !is_null($oldMember) ?
            (int)$oldMember->permissions : $this->permissions;

        // Pays en attente de validation rejetée.
        // Notifiés : Dirigeants du pays.
        if($oldPermission === Organisation::$permissions['pending'] && !$this->exists)
        {
            $pays = Pays::find($this->pays_id);
            $organisation = Organisation::find($this->organisation_id);
            $users = $pays->users;
            Notification::send($users,
                new OrganisationMemberQuit($pays, $organisation));
        }

        // Pays en attente de validation acceptée, ou invitation acceptée.
        // Notifiés : Utilisateurs membres de l'organisation.
        elseif( ($oldPermission === Organisation::$permissions['pending'] ||
                 $oldPermission === Organisation::$permissions['invited'])
                &&
                 $this->permissions === Organisation::$permissions['member'])
        {
            $users = Organisation::find(
                $this->organisation_id)->adminUsers(Organisation::$permissions['member']);
            Notification::send($users,
                new OrganisationMemberPermissionChanged($this, 'accepted'));
        }

        // Pays en attente de validation, à modérer par un administateur.
        // Notifiés : Administrateurs de l'organisation.
        elseif($this->permissions === Organisation::$permissions['pending'])
        {
            $users = Organisation::find($this->organisation_id)->adminUsers();
            Notification::send($users, new OrganisationMemberJoined($this));
        }

        // Pays invité à rejoindre l'organisation.
        // Notifiés : Utilisateurs gérant le pays invité.
        elseif($this->permissions === Organisation::$permissions['invited'])
        {
            $pays = Pays::find($this->pays_id);
            $users = $pays->users;
            Notification::send($users, new OrganisationMemberInvited($this));
        }

        // Pays devenu administrateur.
        // Notifiés : Utilisateurs membres de l'organisation.
        elseif($oldPermission >= Organisation::$permissions['member']
            && $this->permissions === Organisation::$permissions['administrator'])
        {
            $users = Organisation::find(
                $this->organisation_id)->adminUsers(Organisation::$permissions['member']);
            Notification::send($users,
                new OrganisationMemberPermissionChanged($this,
                    'promotedAdministrator'));
        }

        // Pays ayant quitté l'organisation.
        // Notifiés : Utilisateurs membres de l'organisation.
        elseif(!$this->exists) {
            $users = Organisation::find(
                $this->organisation_id)->adminUsers(Organisation::$permissions['member']);
            $pays = Pays::find($this->pays_id);
            $organisation = Organisation::find($this->organisation_id);
            Notification::send($users,
                new OrganisationMemberQuit($pays, $organisation));
        }

    }
}
