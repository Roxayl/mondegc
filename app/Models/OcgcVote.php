<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OcgcVote
 * 
 * @property int $id
 * @property int|null $ID_proposal
 * @property int|null $ID_pays
 * @property int|null $reponse_choisie
 * @property Carbon|null $created
 */
class OcgcVote extends Model
{
    protected $table = 'ocgc_votes';
    public $timestamps = false;

    protected $casts = [
        'ID_proposal' => 'int',
        'ID_pays' => 'int',
        'reponse_choisie' => 'int'
    ];

    protected $dates = [
        'created'
    ];

    protected $fillable = [
        'ID_proposal',
        'ID_pays',
        'reponse_choisie',
        'created'
    ];

    /**
     * Proposition à laquelle le vote est rattaché.
     * @return BelongsTo
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(OcgcProposal::class, 'ID_proposal');
    }

    /**
     * Donne l'intitulé de la réponse choisie.
     * @return string
     */
    public function getResponseLabel(): string
    {
        $reponseChoisie = $this->reponse_choisie;
        $column = "reponse_$reponseChoisie";

        return $this->proposal->$column;
    }

    /**
     * Donne l'identifiant du vote de l'électeur, caractérisé par son type.
     * @return string Renvoie 'no-vote' si l'électeur n'a pas voté ; 'blanc' s'il s'agit d'un vote blanc ; 'normal'
     *                si l'électeur a choisi une modalité du vote.
     */
    public function getResponseIdentifier(): string
    {
        if($this->reponse_choisie === null) {
            return 'no-vote';
        } elseif($this->reponse_choisie === 0) {
            return 'blank';
        }
        return 'response-' . $this->reponse_choisie;
    }
}
