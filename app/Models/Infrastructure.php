<?php

namespace App\Models;

use App\Models\Contracts\Influencable;
use App\Models\Presenters\InfrastructurePresenter;
use App\Models\Traits\DeletesInfluences;
use App\Models\Traits\Influencable as GeneratesInfluence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Infrastructure
 *
 * @property int $ch_inf_id
 * @property string $ch_inf_label
 * @property int $ch_inf_off_id
 * @property int $ch_inf_villeid
 * @property Carbon $ch_inf_date
 * @property int $ch_inf_statut
 * @property string $nom_infra
 * @property string|null $ch_inf_lien_image
 * @property string|null $ch_inf_lien_image2
 * @property string|null $ch_inf_lien_image3
 * @property string|null $ch_inf_lien_image4
 * @property string|null $ch_inf_lien_image5
 * @property string|null $ch_inf_lien_forum
 * @property string|null $lien_wiki
 * @property int|null $user_creator
 * @property string|null $ch_inf_commentaire
 * @property int|null $ch_inf_juge
 * @property string|null $ch_inf_commentaire_juge
 * @property Carbon|null $judged_at
 * @property CustomUser $user
 * @property int|null $infrastructurable_id
 * @property string|null $infrastructurable_type
 * @property-read Collection|Influence[] $influences
 * @property-read int|null $influences_count
 * @property-read Model|\Eloquent $infrastructurable
 * @property-read InfrastructureOfficielle $infrastructure_officielle
 * @property-read CustomUser|null $judge
 * @method static Builder|Infrastructure newModelQuery()
 * @method static Builder|Infrastructure newQuery()
 * @method static Builder|Infrastructure query()
 * @method static Builder|Infrastructure whereChInfCommentaire($value)
 * @method static Builder|Infrastructure whereChInfCommentaireJuge($value)
 * @method static Builder|Infrastructure whereChInfDate($value)
 * @method static Builder|Infrastructure whereChInfId($value)
 * @method static Builder|Infrastructure whereChInfJuge($value)
 * @method static Builder|Infrastructure whereChInfLabel($value)
 * @method static Builder|Infrastructure whereChInfLienForum($value)
 * @method static Builder|Infrastructure whereChInfLienImage($value)
 * @method static Builder|Infrastructure whereChInfLienImage2($value)
 * @method static Builder|Infrastructure whereChInfLienImage3($value)
 * @method static Builder|Infrastructure whereChInfLienImage4($value)
 * @method static Builder|Infrastructure whereChInfLienImage5($value)
 * @method static Builder|Infrastructure whereChInfOffId($value)
 * @method static Builder|Infrastructure whereChInfStatut($value)
 * @method static Builder|Infrastructure whereChInfVilleid($value)
 * @method static Builder|Infrastructure whereInfrastructurableId($value)
 * @method static Builder|Infrastructure whereInfrastructurableType($value)
 * @method static Builder|Infrastructure whereJudgedAt($value)
 * @method static Builder|Infrastructure whereLienWiki($value)
 * @method static Builder|Infrastructure whereNomInfra($value)
 * @method static Builder|Infrastructure whereUserCreator($value)
 * @mixin Model
 */
class Infrastructure extends Model implements Influencable
{
    use InfrastructurePresenter, GeneratesInfluence, DeletesInfluences;

    protected $table = 'infrastructures';
    protected $primaryKey = 'ch_inf_id';

    public const CREATED_AT = 'ch_inf_date';
    public const UPDATED_AT = null;

    protected $casts = [
        'ch_inf_off_id' => 'int',
        'ch_inf_villeid' => 'int',
        'ch_inf_statut' => 'int',
        'user_creator' => 'int',
        'ch_inf_juge' => 'int'
    ];

    protected $dates = [
        'ch_inf_date',
        'judged_at',
    ];

    protected $fillable = [
        'ch_inf_off_id',
        'ch_inf_villeid',
        'nom_infra',
        'ch_inf_lien_image',
        'ch_inf_lien_image2',
        'ch_inf_lien_image3',
        'ch_inf_lien_image4',
        'ch_inf_lien_image5',
        'ch_inf_lien_forum',
        'ch_inf_commentaire',
        'lien_wiki',
        'infrastructurable_type',
        'infrastructurable_id',
    ];

    public const JUGEMENT_PENDING = 1;
    public const JUGEMENT_ACCEPTED = 2;
    public const JUGEMENT_REJECTED = 3;

    /**
     * @return MorphTo
     */
    public function infrastructurable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return Builder
     */
    public static function pending(): Builder
    {
        return self::where('ch_inf_statut', self::JUGEMENT_PENDING);
    }

    /**
     * @return Builder
     */
    public static function accepted(): Builder
    {
        return self::where('ch_inf_statut', self::JUGEMENT_ACCEPTED);
    }

    /**
     * @return Builder
     */
    public static function rejected(): Builder
    {
        return self::where('ch_inf_statut', self::JUGEMENT_REJECTED);
    }

    /**
     * @return BelongsTo
     */
    public function infrastructureOfficielle(): BelongsTo
    {
        return $this->belongsTo(InfrastructureOfficielle::class, 'ch_inf_off_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'user_creator');
    }

    /**
     * @return BelongsTo
     */
    public function judge(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'ch_inf_juge');
    }

    /**
     * @param string $parameter
     * @return string
     */
    public static function getMorphFromUrlParameter(string $parameter): string
    {
        $class = match ($parameter) {
            'ville' => Ville::class,
            'pays' => Pays::class,
            'organisation' => Organisation::class,
            default => throw new \InvalidArgumentException("Mauvais type de modèle."),
        };

        return self::getActualClassNameForMorph($class);
    }

    /**
     * @param string $morphType
     * @return string
     */
    public static function getUrlParameterFromMorph(string $morphType): string
    {
        $morph = explode("\\", $morphType);
        return strtolower(end($morph));
    }

    /**
     * @inheritDoc
     */
    public function generateInfluence(): void
    {
        $notAccepted = fn() => $this->ch_inf_statut !== self::JUGEMENT_ACCEPTED;

        $this->removeOldInfluenceRows($notAccepted);
        if($notAccepted()) {
            return;
        }

        $totalResources = $this->infrastructureOfficielle->mapResources();

        if(!empty($this->infrastructurable) && $this->infrastructurable->getType() === 'organisation') {

            // Dans le cas où l'organisation est une "alliance" ou "agence GC", on augmente les
            // ressources 'positives' générées de 50% (multiplie par 1.5) et les ressources
            // 'négatives' de 15% (multiplie de 1.15).
            /* TODO On peut envisager une classe qui contient les modifications types aux ressources
             * économiques ? Cela impliquerait de transformer les données de ressources du type
             * 'array' à un classe spécifique... */
            if( in_array($this->infrastructurable->type,
                  [Organisation::TYPE_AGENCY, Organisation::TYPE_ALLIANCE], true) )
            {
                $tmp = array_map(fn($val) => $val > 0 ? (int)($val * 1.5) : (int)($val * 1.15),
                    $totalResources);
                $totalResources = $tmp;
            }

            // On augmente les ressources générées selon la formule suivante :
            // valeurResources * nbrMembresOrga ^ 1.2
            $nbrMembresOrganisation = $this->infrastructurable->members->count();
            $tmp = array_map(fn($val) => (int)($val * pow($nbrMembresOrganisation, 1.2)),
                $totalResources);
            $totalResources = $tmp;
            unset($tmp);
        }

        $divider = 2;

        $resourcesPerMonth = array_map(
            function($val) use($divider) {
                return (int)($val / $divider);
            },
            $totalResources);

        for($i = 0; $i < $divider; $i++) {
            $influence = new Influence;
            $influence->influencable_type = Influence::getActualClassNameForMorph(get_class($this));
            $influence->influencable_id = $this->ch_inf_id;

            if($i >= $divider - 1) {
                array_walk($totalResources,
                    function($val, $key) use($divider, $totalResources, &$resourcesPerMonth) {
                        $diff = $totalResources[$key] - ($resourcesPerMonth[$key] * $divider);
                        if($diff !== 0) {
                            $resourcesPerMonth[$key] += $diff;
                        }
                    });
            }
            $influence->fill($resourcesPerMonth);

            $influence->generates_influence_at = $this->ch_inf_date->addMonths($i);
            $influence->save();
        }
    }

    public static function boot()
    {
        parent::boot();

        // Appelle la méthode ci-dessous avant d'appeler la méthode delete() sur ce modèle.
        static::deleting(function($infrastructure) {
            $infrastructure->deleteInfluences();
        });
    }
}
