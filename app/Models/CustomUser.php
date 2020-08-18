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
		'ch_use_statut' => 'int'
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

	/* Niveau de permissions défini dans ch_use_statut */
	public const ADMIN = 30;
	public const OCGC = 20;
	public const JUGE = 15;
	public const MEMBER = 10;

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

    /**
     * Vérifie si un utilisateur possède bien un pays, au niveau de permission co-dirigeant.
     * @param Pays $pays Pays à vérifier.
     * @return bool Renvoie <code>true</code> lorsque l'utilisateur dirige le pays,
     *              <code>false</code> sinon.
     */
    public function ownsPays(Pays $pays) : bool
    {
        return in_array($pays->ch_pay_id,
                 array_column($this->pays()->get()->toArray(), 'ch_pay_id'));
    }

    public function hasMinPermission($level) {

        switch($level) {
            case 'member':
                $permission = self::MEMBER; break;
            case 'juge':
                $permission = self::JUGE; break;
            case 'ocgc':
                $permission = self::OCGC; break;
            case 'admin':
                $permission = self::ADMIN; break;
            default:
                throw new \InvalidArgumentException("Mauvais type de permission.");
        }

        return $this->ch_use_statut >= $permission;

    }

}
