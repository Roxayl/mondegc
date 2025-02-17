<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use GenCity\Proposal\Proposal;
use GenCity\Proposal\ProposalDecisionMaker;
use GenCity\Proposal\VoteList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class OcgcProposal.
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
 * @property-read Pays|null $pays
 *
 * @method static Builder|OcgcProposal newModelQuery()
 * @method static Builder|OcgcProposal newQuery()
 * @method static Builder|OcgcProposal query()
 * @method static Builder|OcgcProposal whereCreated($value)
 * @method static Builder|OcgcProposal whereDebateEnd($value)
 * @method static Builder|OcgcProposal whereDebateStart($value)
 * @method static Builder|OcgcProposal whereIDPays($value)
 * @method static Builder|OcgcProposal whereId($value)
 * @method static Builder|OcgcProposal whereIsValid($value)
 * @method static Builder|OcgcProposal whereLinkDebate($value)
 * @method static Builder|OcgcProposal whereLinkDebateName($value)
 * @method static Builder|OcgcProposal whereLinkWiki($value)
 * @method static Builder|OcgcProposal whereLinkWikiName($value)
 * @method static Builder|OcgcProposal whereMotive($value)
 * @method static Builder|OcgcProposal whereQuestion($value)
 * @method static Builder|OcgcProposal whereReponse1($value)
 * @method static Builder|OcgcProposal whereReponse2($value)
 * @method static Builder|OcgcProposal whereReponse3($value)
 * @method static Builder|OcgcProposal whereReponse4($value)
 * @method static Builder|OcgcProposal whereReponse5($value)
 * @method static Builder|OcgcProposal whereResId($value)
 * @method static Builder|OcgcProposal whereResYear($value)
 * @method static Builder|OcgcProposal whereThreshold($value)
 * @method static Builder|OcgcProposal whereType($value)
 * @method static Builder|OcgcProposal whereTypeReponse($value)
 * @method static Builder|OcgcProposal whereUpdated($value)
 *
 * @mixin \Eloquent
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
        'debate_start' => 'datetime',
        'debate_end' => 'datetime',
        'created' => 'datetime',
        'updated' => 'datetime',
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
        'IRL' => 'Sondage',
        'RP' => 'Résolution',
    ];

    /**
     * @inheritDoc
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->detectMaxResponses();
    }

    /**
     * Pays à l'origine de la proposition.
     *
     * @return BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'ID_pays', 'ch_pay_id');
    }

    private function detectMaxResponses(): void
    {
        // Obtenir le nombre maximal de réponses à partir des attributs de la base de données (e.g."reponse_5").
        if (is_null(self::$maxResponses)) {
            $attrNames = $this->getFillable();
            $maxResponses = 0;
            foreach ($attrNames as $attrName) {
                rescue(function () use ($attrName, &$maxResponses) {
                    if (Str::startsWith($attrName, 'reponse_')) {
                        preg_match_all('!\d+!', $attrName, $matches);
                        $maxResponses = (int) implode(' ', $matches[0]);
                    }
                });
            }
            self::$maxResponses = $maxResponses;
        }

        // Vérifier que la valeur obtenue est correcte.
        if (is_null(self::$maxResponses) || ! self::$maxResponses) {
            throw new \UnexpectedValueException('Bad max response value.');
        }
    }

    /**
     * Nombre total de réponses possibles pour une proposition.
     *
     * @return int
     */
    public function getMaxResponses(): int
    {
        $this->detectMaxResponses();

        return self::$maxResponses;
    }

    /**
     * Donne la collection des réponses à une proposition, y compris le vote blanc.
     *
     * @return Collection
     */
    public function responses(): Collection
    {
        $responses = [];

        $responses[0] = 'Blanc';
        for ($i = 1; $i <= $this->getMaxResponses(); $i++) {
            $attribute = "reponse_$i";
            if ($this->$attribute !== null) {
                $responses[$i] = $this->$attribute;
            }
        }

        return collect($responses);
    }

    /**
     * Renvoie la liste des pays votants à la proposition.
     *
     * @return Collection<Pays>
     */
    public function votingCountries(): Collection
    {
        $votes = DB::query()
            ->select('*')
            ->from('ocgc_votes')
            ->where('ID_proposal', $this->id);

        return Pays::query()->whereIn('ch_pay_id', $votes->pluck('ID_pays'))->get();
    }

    /**
     * Ajoute de nouveaux pays votants à la proposition.
     *
     * @param  iterable<int>  $paysIds  Liste de pays décrits par leur identifiant.
     */
    public function addVoters(iterable $paysIds): void
    {
        $votingCountries = $this->votingCountries()->pluck('ch_pay_id');
        foreach ($paysIds as $paysId) {
            if ($paysId === null || $votingCountries->contains($paysId)) {
                continue;
            }
            DB::table('ocgc_votes')->insert([
                'ID_proposal' => $this->id,
                'ID_pays' => $paysId,
                'reponse_choisie' => null,
                'created' => Carbon::now(),
            ]);
        }
    }

    /**
     * Renvoie le choix à l'issue du vote.
     *
     * @return Collection Collection de réponses.
     */
    public function results(): Collection
    {
        $decisionMaker = new ProposalDecisionMaker(new VoteList(new Proposal($this->id)));

        return collect($decisionMaker->outputFormat());
    }

    /**
     * Donne le détail du type de la proposition.
     *
     * @return string "Sondage" ou "Résolution", en fonction du type de la proposition.
     */
    public function typeDetail(): string
    {
        return self::$typeDetail[$this->type];
    }

    /**
     * Renvoie l'identifiant complet d'une proposition (e.g. "Résolution 21-072").
     *
     * @return string
     */
    public function fullIdentifier(): string
    {
        return $this->typeDetail() . ' '
            . Str::substr($this->res_year, 2, 2) . '-'
            . Str::padLeft($this->res_id, 3, '0');
    }
}
