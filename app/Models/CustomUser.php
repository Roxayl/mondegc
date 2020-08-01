<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
	protected $primaryKey = 'ch_use_id';
	public $timestamps = false;

	protected $casts = [
		'ch_use_acces' => 'bool',
		'ch_use_paysID' => 'int',
		'ch_use_statut' => 'bool'
	];

	protected $dates = [
		'ch_use_date',
		'ch_use_last_log'
	];

	protected $hidden = [
		'ch_use_password'
	];

	protected $fillable = [
		'ch_use_acces',
		'ch_use_date',
		'ch_use_last_log',
		'ch_use_login',
		'ch_use_password',
		'ch_use_mail',
		'ch_use_paysID',
		'ch_use_statut',
		'ch_use_lien_imgpersonnage',
		'ch_use_predicat_dirigeant',
		'ch_use_titre_dirigeant',
		'ch_use_nom_dirigeant',
		'ch_use_prenom_dirigeant',
		'ch_use_biographie_dirigeant'
	];

	public function infrastructures()
	{
		return $this->hasMany(Infrastructure::class, 'user_creator');
	}

	public function logs()
	{
		return $this->hasMany(Log::class);
	}

	public function notifications_legacy()
	{
		return $this->hasMany(NotificationLegacy::class, 'recipient_id');
	}

    public function pays()
    {
        return $this->belongsToMany(Pays::class, 'users_pays', 'ID_user', 'ID_pays');
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return "ch_use_id";
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }


    public function getAuthPassword()
    {
      return $this->ch_use_password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->ch_use_mail;
    }

    public function hasMinPermission($level) {

        switch($level) {
            case 'member':
                $permission = 10; break;
            case 'juge':
                $permission = 15; break;
            case 'ocgc':
                $permission = 20; break;
            case 'admin':
                $permission = 30; break;
            default:
                throw new \InvalidArgumentException("Mauvais type de permission.");
        }

        return $this->ch_use_statut >= $permission;

    }

}