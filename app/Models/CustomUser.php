<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\CustomUser
 *
 * @property int $ch_use_id
 * @property bool|null $ch_use_acces
 * @property \Illuminate\Support\Carbon|null $ch_use_date
 * @property \Illuminate\Support\Carbon|null $ch_use_last_log
 * @property string|null $last_activity
 * @property string|null $ch_use_login
 * @property string|null $ch_use_password
 * @property string|null $ch_use_mail
 * @property int|null $ch_use_paysID
 * @property int $ch_use_statut
 * @property string|null $ch_use_lien_imgpersonnage
 * @property string|null $ch_use_predicat_dirigeant
 * @property string|null $ch_use_titre_dirigeant
 * @property string|null $ch_use_nom_dirigeant
 * @property string|null $ch_use_prenom_dirigeant
 * @property string|null $ch_use_biographie_dirigeant
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Infrastructure[] $infrastructures
 * @property-read int|null $infrastructures_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log[] $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\NotificationLegacy[] $notificationsLegacy
 * @property-read int|null $notifications_legacy_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pays[] $pays
 * @property-read int|null $pays_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Roleplay[] $ownedRoleplays
 * @property-read int|null $owned_roleplays_count
 * @method static \Database\Factories\CustomUserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseAcces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseBiographieDirigeant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseLastLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseLienImgpersonnage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseMail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseNomDirigeant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUsePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUsePaysID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUsePredicatDirigeant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUsePrenomDirigeant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereChUseTitreDirigeant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomUser whereLastActivity($value)
 * @mixin Model
 */
class CustomUser extends Authenticatable
{
    use HasFactory, Notifiable;

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

    public function infrastructures(): HasMany
    {
        return $this->hasMany(Infrastructure::class, 'user_creator');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function ownedRoleplays(): HasMany
    {
        return $this->hasMany(Roleplay::class, 'user_id');
    }

    public function notificationsLegacy(): HasMany
    {
        return $this->hasMany(NotificationLegacy::class, 'recipient_id');
    }

    public function pays(): BelongsToMany
    {
        return $this->belongsToMany(
            Pays::class, 'users_pays',
            'ID_user', 'ID_pays')
            ->withPivot(['permissions']);
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
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

    /**
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->ch_use_password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail(): string
    {
        return $this->ch_use_mail;
    }

    /**
     * Vérifie si un utilisateur possède bien un pays, au niveau de permission co-dirigeant.
     * @param Pays $pays Pays à vérifier.
     * @return bool Renvoie <code>true</code> lorsque l'utilisateur dirige le pays,
     *              <code>false</code> sinon.
     */
    public function ownsPays(Pays $pays): bool
    {
        return in_array($pays->ch_pay_id,
            array_column($this->pays()->get()->toArray(), 'ch_pay_id'));
    }

    /**
     * @param string $level Prend les valeurs suivantes : "member", "juge", "ocgc", "admin".
     * @return bool
     */
    public function hasMinPermission(string $level): bool
    {
        switch($level) {
            case 'member':
                $permission = self::MEMBER;
                break;
            case 'juge':
                $permission = self::JUGE;
                break;
            case 'ocgc':
                $permission = self::OCGC;
                break;
            case 'admin':
                $permission = self::ADMIN;
                break;
            default:
                throw new \InvalidArgumentException("Mauvais type de permission.");
        }

        return $this->ch_use_statut >= $permission;
    }
}
