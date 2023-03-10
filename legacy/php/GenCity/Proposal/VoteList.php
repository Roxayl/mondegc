<?php

namespace GenCity\Proposal;
use GenCity\Monde\Pays;
use GenCity\Monde\PaysList;
use GenCity\Monde\User;

/**
 * Class VoteList
 * Classe liée aux votes d'une proposition.
 */
class VoteList {

    private $proposal = null;
    private $allVotes = array();

    public function __construct(Proposal $proposal) {

        $this->proposal = $proposal;

        // Obtenir tous les votes
        $this->getAllVotes();

    }

    private function getAllVotes() {

        // Vider l'array avant d'ajouter les votes
        $this->allVotes = array();

        $query = sprintf('SELECT * FROM ocgc_votes WHERE ID_proposal = %s',
            GetSQLValueString($this->proposal->get('id')));
        $mysql_query = mysql_query($query);
        while($thisVote = mysql_fetch_assoc($mysql_query)) {
            $this->allVotes[] = new Vote();
        }

    }

    public function createAllVotes() {

        $paysList = new PaysList();
        $allowedPays = $paysList->getActive();

        /** @var Pays $pays */
        foreach($allowedPays as $pays) {
            $query = sprintf('
              INSERT INTO ocgc_votes(ID_proposal, ID_pays, reponse_choisie, created)
              VALUES(%s, %s, NULL, NOW())',
                GetSQLValueString($this->proposal->get('id')),
                GetSQLValueString($pays->get('ch_pay_id'))
            );
            mysql_query($query);
        }

        // Mettre à jour la liste des votes
        $this->getAllVotes();

    }

    public function getTotalVotes() {

        return count($this->allVotes);

    }

    /**
     * Donne le nombre de votes d'abstention (valeur <code>NULL</code> pour le champ 'reponse_choisie').
     * @return int Nombre d'abstentionnistes.
     */
    public function getAbstentionVotes() {

        $query = mysql_query(sprintf('SELECT COUNT(id) AS nbr_votes
                      FROM ocgc_votes
                      WHERE ID_proposal = %s AND reponse_choisie IS NULL',
            GetSQLValueString($this->proposal->get('id'))));
        $count = mysql_fetch_assoc($query)['nbr_votes'];

        return (int)$count;

    }

    private function getVotesByResponse($get_null = true, $orderby_field = 'id') {

        $listReponses = array_keys($this->proposal->getResponses());

        $results = array();
        $query = sprintf('SELECT id, reponse_choisie, COUNT(id) AS nbr_votes
                    FROM ocgc_votes
                    WHERE ID_proposal = %s %s
                    GROUP BY reponse_choisie ',
                    $this->proposal->get('id'),
                   ($get_null ? '' : ' AND reponse_choisie IS NOT NULL '));
        $mysql_query = mysql_query($query);

        while($row = mysql_fetch_assoc($mysql_query)) {
            $results[] = $row;
        }

        $existingReponses = array_column($results, 'reponse_choisie');

        foreach($listReponses as $thisReponse) {
            if(!in_array($thisReponse, $existingReponses)) {
                $results[] = array(
                    'id' => null,
                    'reponse_choisie' => $thisReponse,
                    'nbr_votes' => 0
                );
            }
        }

        // Tri du tableau
        usort($results, function($a, $b) use($orderby_field) {
            $return = 0;
            switch($orderby_field) {
                case 'id':
                case 'reponse_choisie':
                    $return = $a[$orderby_field] < $b[$orderby_field] ? -1 : 1;
                    break;

                case 'count':
                    $return = $a['nbr_votes'] > $b['nbr_votes'] ? -1 : 1;
                    break;
            }
            return $return;
        });

        return $results;

    }

    public function getResultsByResponses($orderby_field = 'id') {

        $results = $this->getVotesByResponse(false, $orderby_field);

        $sum = 0;
        foreach($results as $result) {
            $sum += (int)$result['nbr_votes'];
        }

        foreach($results as &$result) {
            $result['pct'] = $sum === 0 ? 0 : round($result['nbr_votes'] / $sum, 3);
        }

        return $results;

    }

    /**
     * Donne les résultats par pays.
     * @return Vote[] Array d'objets.
     */
    public function getResultsPerCountry() {

        $results = array();
        $query = sprintf('SELECT ocgc_votes.*
                  FROM ocgc_votes
                  JOIN pays ON ID_pays = ch_pay_id
                  WHERE ID_proposal = %s
                  ORDER BY FIELD(ch_pay_continent,
                    "RFGC", "Aurinea", "Oceania", "Volcania", "Aldesyl", "Philicie"), ch_pay_nom ASC',
                GetSQLValueString($this->proposal->get('id')));
        $mysql_query = mysql_query($query);
        while($row = mysql_fetch_assoc($mysql_query)) {
            $results[] = new Vote($row);
        }

        return $results;

    }

    /**
     * @param User $user Les votes que l'utilisateur peut modifier.
     * @return Vote[] Array d'objets.
     */
    public function getUserVotes(User $user) {

        $userVotes = array();

        $query = sprintf('SELECT ocgc_votes.* FROM ocgc_votes
                            JOIN users_pays USING(ID_pays)
                            WHERE ID_proposal = %s AND ID_user = %s
                              AND permissions >= %s
                            ORDER BY ocgc_votes.id',
            GetSQLValueString($this->proposal->get('id')),
            GetSQLValueString($user->get('ch_use_id')),
            Pays::$permissions['dirigeant']);
        $mysql_query = mysql_query($query);
        while($row = mysql_fetch_assoc($mysql_query)) {
            $userVotes[$row['id']] = new Vote($row);
        }

        return $userVotes;

    }

    public function generateDiagramData() {

        $results = $this->getResultsPerCountry();

        $diagram = array(
            'd3DataSource' => array(),
            'css' => array()
        );
        foreach($results as $vote) {
            $this_row_id = "diagram-pays-" . $vote->get('id');
            $diagram['d3DataSource'][] = array(
                'id' => $this_row_id,
                'voteId' => $vote->get('id'),
                'legend' => $vote->getPaysAuthor()->get('ch_pay_continent'),
                'name' => $vote->getPaysAuthor()->get('ch_pay_nom'),
                'seats' => 1
            );
            $diagram['css'][] = array(
                "svg .seat.$this_row_id" => $this->getColorFromVote($vote)
            );
        }

        return $diagram;

    }

    public function generateChartResults() {

        $return = array(
            'labels'  => array(),
            'data'    => array(),
            'bgColor' => array()
        );

        $results = $this->getVotesByResponse(false, 'reponse_choisie');

        // Dans le cas d'un vote de type "pour/contre", on fait en sorte à ce que
        // le vote blanc apparaisse au milieu du diagramme.
        if($this->proposal->get('type_reponse') === 'dual' && isset($results[1])) {
            $tmp = $results[1];
            $results[1] = $results[0];
            $results[0] = $tmp;
        }

        foreach($results as $result) {
            $return['labels'][]  = $this->proposal->get('reponse_' . $result['reponse_choisie']);
            $return['data'][]    = $result['nbr_votes'];
            $return['bgColor'][] = $this->getColorFromVote(new Vote($result['id']));
        }

        return $return;

    }

    public function generateTooltipData() {

        $return = array();

        $votes = $this->getResultsPerCountry();

        foreach($votes as $vote) {

            $reponseIntitule = $vote->get('reponse_choisie') !== null ?
                $this->getProposal()->get('reponse_' . $vote->get('reponse_choisie')) : 'Abstention';

            // Définir le nom du pays
            if($vote->getPaysAuthor()->get('ch_pay_continent') === 'RFGC') {
                $pays_nom = "République Fédérale de Gécée - " .
                            $vote->getPaysAuthor()->get('ch_pay_nom');
            } else {
                $pays_nom = $vote->getPaysAuthor()->get('ch_pay_nom');
            }

            $return[$vote->get('id')] = array(
                'paysNom' => $pays_nom,
                'paysDrapeau' => $vote->getPaysAuthor()->get('ch_pay_lien_imgdrapeau'),
                'reponseColor' => $this->getColorFromVote($vote),
                'reponseIntitule' => $reponseIntitule
            );
        }

        return $return;

    }

    public function getColorFromVote(Vote $vote) {

        $colorsDual = array('#0D911F', '#910F0F'); /* pour / contre */
        $colorsMultiple = array('#918024', '#595C91', '#28915C', '#913C67', '#915836'); // Une pour chq réponse
        $colorBlanc = '#AEB6C3';
        $colorAbstention = '#83808A';

        $return = $colorAbstention;

        if(!is_null($vote->get('reponse_choisie'))) {
            if((int)$vote->get('reponse_choisie') === 0) {
                $return = $colorBlanc;
            } else {
                $arrayKey = (int)$vote->get('reponse_choisie') - 1;
                if($this->proposal->get('type_reponse') === 'dual') {
                    $return = $colorsDual[$arrayKey];
                } else {
                    $return = $colorsMultiple[$arrayKey];
                }
            }
        }

        return $return;

    }

    public function getProposal() {

        return $this->proposal;

    }

}