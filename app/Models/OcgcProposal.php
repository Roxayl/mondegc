<?php

namespace App\Models;

use Carbon\Carbon;
use GenCity\Proposal\Proposal;
use GenCity\Proposal\ProposalDecisionMaker;
use GenCity\Proposal\VoteList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class OcgcProposal
 *
 * @property int $id
 * @property int|null $ID_pays
 * @property string|null $question
 * @property string|null $type
 * @property string|null $type_reponse
 * @property string|null $reponse_1
 * @property string|null $reponse_2
 * @property string|null $reponse_3
 * @property string|null $reponse_4
 * @property string|null $reponse_5
 * @property float|null $threshold
 * @property int|null $is_valid
 * @property string|null $motive
 * @property Carbon|null $debate_start
 * @property Carbon|null $debate_end
 * @property string|null $link_debate
 * @property string|null $link_debate_name
 * @property string|null $link_wiki
 * @property string|null $link_wiki_name
 * @property int|null $res_year
 * @property int|null $res_id
 * @property Carbon|null $created
 * @property Carbon|null $updated
 * @package App\Models
 * @property-read \App\Models\Pays|null $pays
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal query()
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereDebateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereDebateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereIDPays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereIsValid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereLinkDebate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereLinkDebateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereLinkWiki($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereLinkWikiName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereMotive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereReponse1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereReponse2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereReponse3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereReponse4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereReponse5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereResId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereResYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereTypeReponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OcgcProposal whereUpdated($value)
 * @mixin Model
 */
class OcgcProposal extends Model
{
    protected $table = 'ocgc_proposals';
    public $timestamps = false;

    protected $casts = [
        'ID_pays' => 'int',
        'threshold' => 'float',
        'is_valid' => 'int',
        'res_year' => 'int',
        'res_id' => 'int',
    ];

    protected $dates = [
        'debate_start',
        'debate_end',
        'created',
        'updated',
    ];

    protected $fillable = [
        'ID_pays',
        'question',
        'type',
        'type_reponse',
        'reponse_1',
        'reponse_2',
        'reponse_3',
        'reponse_4',
        'reponse_5',
        'threshold',
        'debate_start',
        'debate_end',
        'link_debate',
        'link_debate_name',
        'link_wiki',
        'link_wiki_name',
    ];

    /**
     * @var int|null Nombre total de réponses possibles pour une proposition.
     */
    private static ?int $maxResponses = null;

    /**
     * @var string[] Correspondance entre le détail et l'identifiant d'un type de proposition.
     */
    private static array $typeDetail = [
        'IRL' => "Sondage",
        'RP'  => "Résolution",
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setMaxResponses();
    }

    /**
     * Pays à l'origine de la proposition.
     * @return BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'ID_pays', 'ch_pay_id');
    }

    private function setMaxResponses(): void
    {
        // Obtenir le nombre maximal de réponses à partir des attributs de la base de données (e.g."reponse_5").
        if(is_null(self::$maxResponses)) {
            $attrNames = $this->getFillable();
            $maxResponses = 0;
            foreach($attrNames as $attrName) {
                rescue(function() use($attrName, &$maxResponses) {
                    if(Str::startsWith($attrName, 'reponse_')) {
                        preg_match_all('!\d+!', $attrName, $matches);
                        $maxResponses = implode(' ', $matches[0]);
                    }
                });
            }
            self::$maxResponses = $maxResponses;
        }

        // Vérifier que la valeur obtenue est correcte.
        if(is_null(self::$maxResponses) || ! self::$maxResponses) {
            throw new \UnexpectedValueException("Bad max response value.");
        }
    }

    /**
     * Nombre total de réponses possibles pour une proposition.
     * @return int
     */
    public function getMaxResponses(): int
    {
        $this->setMaxResponses();
        return self::$maxResponses;
    }

    /**
     * Donne la collection des réponses à une proposition, y compris le vote blanc.
     * @return Collection
     */
    public function responses(): Collection
    {
        $responses = [];

        $responses[0] = 'Blanc';
        for($i = 1; $i <= $this->getMaxResponses(); $i++) {
            $attribute = "reponse_$i";
            if($this->$attribute !== null) {
                $responses[$i] = $this->$attribute;
            }
        }

        return collect($responses);
    }

    /**
     * Renvoie le choix à l'issue du vote.
     * @return Collection Collection de réponses.
     */
    public function results(): Collection
    {
        $decisionMaker = new ProposalDecisionMaker(new VoteList(new Proposal($this->id)));

        return collect($decisionMaker->outputFormat());
    }

    /**
     * Donne le détail du type de la proposition.
     * @return string "Sondage" ou "Résolution", en fonction du type de la proposition.
     */
    public function typeDetail(): string
    {
        return self::$typeDetail[$this->type];
    }

    /**
     * Renvoie l'identifiant complet d'une proposition (e.g. "Résolution 21-072").
     * @return string
     */
    public function fullIdentifier(): string
    {
        return $this->typeDetail() . ' '
            . Str::substr($this->res_year, 2, 2) . '-'
            . Str::padLeft($this->res_id, 3, '0');
    }
}
