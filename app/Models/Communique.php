<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Communique
 *
 * @property int $ch_com_ID
 * @property string $ch_com_label
 * @property int $ch_com_statut
 * @property string|null $ch_com_categorie
 * @property int $ch_com_element_id
 * @property int $ch_com_user_id
 * @property Carbon|null $ch_com_date
 * @property Carbon|null $ch_com_date_mis_jour
 * @property string|null $ch_com_titre
 * @property string $ch_com_contenu
 * @property int|null $ch_com_pays_id
 * @property CustomUser $user
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Communique newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Communique newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Communique query()
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComDateMisJour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComElementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComPaysId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Communique whereChComUserId($value)
 * @mixin Model
 */
class Communique extends Model
{
	protected $table = 'communiques';
	protected $primaryKey = 'ch_com_ID';

	protected $casts = [
		'ch_com_statut' => 'int',
		'ch_com_element_id' => 'int',
		'ch_com_user_id' => 'int',
		'ch_com_pays_id' => 'int'
	];

	const CREATED_AT =  'ch_com_date';
	const UPDATED_AT =  'ch_com_date_mis_jour';

	protected $dates = [
		'ch_com_date',
		'ch_com_date_mis_jour'
	];

	protected $fillable = [
		'ch_com_statut',
		'ch_com_categorie',
		'ch_com_element_id',
		'ch_com_titre',
		'ch_com_contenu',
		'ch_com_pays_id'
	];

	public const STATUS_PUBLISHED = 1;
	public const STATUS_DRAFT = 2;

	public function user(): BelongsTo
	{
		return $this->belongsTo(CustomUser::class, 'ch_com_user_id');
	}

	public function publisher()
    {
        // TODO: https://laravel.com/docs/5.8/eloquent-relationships#one-to-many-polymorphic-relations
        // return $this->morphTo();
        throw new \Exception("Pas encore implémenté.");
    }
}
