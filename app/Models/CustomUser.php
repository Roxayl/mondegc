<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models;

use Database\Factories\CustomUserFactory;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Roxayl\MondeGC\Models\Contracts\Roleplayable;

/**
 * Roxayl\MondeGC\Models\CustomUser.
 *
 * @property int $ch_use_id
 * @property bool|null $ch_use_acces
 * @property Carbon|null $ch_use_date
 * @property Carbon|null $ch_use_last_log
 * @property string|null $last_activity
 * @property string|null $ch_use_login
 * @property string|null $ch_use_password
 * @property string|null $api_token
 * @property string|null $ch_use_mail
 * @property int|null $ch_use_paysID
 * @property int $ch_use_statut
 * @property string|null $ch_use_lien_imgpersonnage
 * @property string|null $ch_use_predicat_dirigeant
 * @property string|null $ch_use_titre_dirigeant
 * @property string|null $ch_use_nom_dirigeant
 * @property string|null $ch_use_prenom_dirigeant
 * @property string|null $ch_use_biographie_dirigeant
 * @property-read Eloquent\Collection|Infrastructure[] $infrastructures
 * @property-read int|null $infrastructures_count
 * @property-read Eloquent\Collection|Log[] $logs
 * @property-read int|null $logs_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Eloquent\Collection|NotificationLegacy[] $notificationsLegacy
 * @property-read int|null $notifications_legacy_count
 * @property-read Eloquent\Collection|Pays[] $pays
 * @property-read int|null $pays_count
 * @property-read Eloquent\Collection|Roleplay[] $ownedRoleplays
 * @property-read int|null $owned_roleplays_count
 *
 * @method static CustomUserFactory factory(...$parameters)
 * @method static Builder|CustomUser newModelQuery()
 * @method static Builder|CustomUser newQuery()
 * @method static Builder|CustomUser query()
 * @method static Builder|CustomUser whereChUseAcces($value)
 * @method static Builder|CustomUser whereChUseBiographieDirigeant($value)
 * @method static Builder|CustomUser whereChUseDate($value)
 * @method static Builder|CustomUser whereChUseId($value)
 * @method static Builder|CustomUser whereChUseLastLog($value)
 * @method static Builder|CustomUser whereChUseLienImgpersonnage($value)
 * @method static Builder|CustomUser whereChUseLogin($value)
 * @method static Builder|CustomUser whereChUseMail($value)
 * @method static Builder|CustomUser whereChUseNomDirigeant($value)
 * @method static Builder|CustomUser whereChUsePassword($value)
 * @method static Builder|CustomUser whereApiToken($value)
 * @method static Builder|CustomUser whereChUsePaysID($value)
 * @method static Builder|CustomUser whereChUsePredicatDirigeant($value)
 * @method static Builder|CustomUser whereChUsePrenomDirigeant($value)
 * @method static Builder|CustomUser whereChUseStatut($value)
 * @method static Builder|CustomUser whereChUseTitreDirigeant($value)
 * @method static Builder|CustomUser whereLastActivity($value)
 *
 * @mixin \Eloquent
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
        'ch_use_statut' => 'int',
        'ch_use_date' => 'datetime',
        'ch_use_last_log' => 'datetime',
    ];

    protected $hidden = [
        'ch_use_password',
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
        'ch_use_biographie_dirigeant',
    ];

    /* Niveau de permissions défini dans ch_use_statut */
    public const ADMIN = 30;
    public const OCGC = 20;
    public const JUGE = 15;
    public const MEMBER = 10;

    /**
     * @var Collection|null Liste des roleplayables mis en cache.
     */
    private ?Collection $cachedRoleplayables = null;

    /**
     * @return HasMany
     */
    public function infrastructures(): HasMany
    {
        return $this->hasMany(Infrastructure::class, 'user_creator');
    }

    /**
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    /**
     * @return HasMany
     */
    public function ownedRoleplays(): HasMany
    {
        return $this->hasMany(Roleplay::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function notificationsLegacy(): HasMany
    {
        return $this->hasMany(NotificationLegacy::class, 'recipient_id');
    }

    /**
     * Donne les pays gérés par l'utilisateur, avec les niveaux de permission qu'il possède sur le pays.
     *
     * @return BelongsToMany
     */
    public function pays(): BelongsToMany
    {
        return $this->belongsToMany(
            Pays::class, 'users_pays',
            'ID_user', 'ID_pays')
            ->withPivot(['permissions']);
    }

    /**
     * @return Eloquent\Collection<Pays>
     */
    public function getPays(): Eloquent\Collection
    {
        return $this->pays;
    }

    /**
     * Donne les villes gérées par l'utilisateur, au travers de ses pays.
     *
     * @return Collection<int, Ville>
     */
    public function villes(): Collection
    {
        $villes = collect();

        // Obtenir les villes des pays gérés par l'utilisateur.
        foreach ($this->pays as $pays) {
            $villes = $villes->merge($pays->villes);
        }

        return $villes;
    }

    /**
     * Donne les organisations gérées par l'utilisateur, au travers de ses pays.
     *
     * @return Collection<int, Organisation>
     */
    public function organisations(): Collection
    {
        $organisations = collect();

        foreach ($this->pays as $pays) {
            $organisations = $organisations->merge($pays->managedOrganisations());
        }

        return $organisations;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'ch_use_id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return int
     */
    public function getAuthIdentifier(): int
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
     *
     * @param  Pays  $pays  Pays à vérifier.
     * @return bool Renvoie <code>true</code> lorsque l'utilisateur dirige le pays,
     *              <code>false</code> sinon.
     */
    public function ownsPays(Pays $pays): bool
    {
        return in_array($pays->ch_pay_id,
            array_column($this->pays->toArray(), 'ch_pay_id'));
    }

    /**
     * Donne les modèles roleplayables gérés par l'utilisateur.
     *
     * @return Collection
     *
     * @see Roleplayable
     */
    public function roleplayables(): Collection
    {
        $methods = ['getPays', 'organisations', 'villes'];

        if ($this->cachedRoleplayables === null) {
            $roleplayables = collect();
            foreach ($methods as $method) {
                $roleplayables = $roleplayables->merge($this->$method());
            }
            $this->cachedRoleplayables = $roleplayables;
        }

        return $this->cachedRoleplayables;
    }

    /**
     * Détermine si l'utilisateur possède un roleplayable donné.
     *
     * @param  Roleplayable|null  $roleplayable
     * @return bool
     */
    public function hasRoleplayable(?Roleplayable $roleplayable): bool
    {
        if ($roleplayable === null) {
            return false;
        }

        foreach ($this->roleplayables() as $userRoleplayable) {
            if ($userRoleplayable::class === $roleplayable::class
               && $userRoleplayable->getKey() === $roleplayable->getKey()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  string  $level  Prend les valeurs suivantes : "member", "juge", "ocgc", "admin".
     * @return bool
     */
    public function hasMinPermission(string $level): bool
    {
        $permission = match ($level) {
            'member' => self::MEMBER,
            'juge' => self::JUGE,
            'ocgc' => self::OCGC,
            'admin' => self::ADMIN,
            default => throw new \InvalidArgumentException('Mauvais type de permission.'),
        };

        return $this->ch_use_statut >= $permission;
    }
}
