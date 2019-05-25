<?php

namespace GenCity\Proposal;
use GenCity\Monde\Pays;
use GenCity\Monde\PaysList;
use GenCity\Monde\User;

/**
 * Class VoteList
 * Classe liée aux votes d'une proposition.
 * @package GenCity\Proposal
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

    private function getVotesByResponse() {

        $result = array();
        $query = sprintf('SELECT reponse_choisie, COUNT(id) AS nbr_votes
                    FROM ocgc_votes
                    WHERE ID_proposal = %s
                    GROUP BY reponse_choisie', $this->proposal->get('id'));
        $mysql_query = mysql_query($query);
        while($row = mysql_fetch_assoc($mysql_query)) {
            $result[] = $row;
        }
        return $result;

    }

    public function getResultsByResponses() {

        $listVotes = $this->getVotesByResponse();

        $results = array();

        for($i = 1; $i < Proposal::$maxResponses + 1; $i++) {
            if(is_null($this->proposal->get("reponse_$i"))) break;
            for($j = 0; $j < Proposal::$maxResponses; $j++) {
                if(!isset($listVotes[$j]['reponse_choisie'])) break;
                if($i === (int)$listVotes[$j]['reponse_choisie']) {
                    $this_i = $j;
                    break;
                }
            }
            if(!isset($this_i)) {
                $results[] = array(
                    'reponse'   => $this->proposal->get("reponse_$i"),
                    'id_reponse'=> $i,
                    'nbr_votes' => 0
                );
                continue;
            }
            $results[] = array(
                'reponse'   => $this->proposal->get("reponse_$i"),
                'id_reponse'=> (int)$listVotes[$this_i]['id_reponse'], // est égal à $i
                'nbr_votes' => (int)$listVotes[$this_i]['nbr_votes']
            );
            unset($this_i);
        }

        return $results;

    }

    public function getResultsPerCountry() {

        $results = array();
        $query = sprintf('SELECT id, ID_pays, reponse_choisie,
                    ch_pay_nom, ch_pay_continent
                  FROM ocgc_votes
                  JOIN pays ON ID_pays = ch_pay_id
                  WHERE ID_proposal = %s',
                GetSQLValueString($this->proposal->get('id')));
        $mysql_query = mysql_query($query);
        while($row = mysql_fetch_assoc($mysql_query)) {
            $results[] = $row;
        }

        return $results;

    }

    /**
     * @param User $user Les votes que l'utilisateur peut modifier.
     * @return Vote[] Array d'objets.
     */
    public function getUserVotes(User $user) {

        $userVotes = array();

        $query = sprintf('SELECT ocgc_votes.id AS id_votes FROM ocgc_votes
                            JOIN users_pays USING(ID_pays)
                            WHERE ID_proposal = %s AND ID_user = %s
                              AND permissions >= %s
                            ORDER BY ocgc_votes.id',
            GetSQLValueString($this->proposal->get('id')),
            GetSQLValueString($user->get('ch_use_id')),
            Pays::$permissions['dirigeant']);
        $mysql_query = mysql_query($query);
        while($row = mysql_fetch_assoc($mysql_query)) {
            $userVotes[$row['id_votes']] = new Vote($row['id_votes']);
        }

        return $userVotes;

    }

    public function generateDiagramData() {

        $results = $this->getResultsPerCountry();

        $diagram = array(
            'd3DataSource' => array(),
            'css' => array()
        );
        foreach($results as $row) {
            $this_row_id = "diagram-pays-{$row['id']}";
            $diagram['d3DataSource'][] = array(
                'id' => $this_row_id,
                'legend' => $row['ch_pay_continent'],
                'name' => $row['ch_pay_nom'],
                'seats' => 1
            );
            $diagram['css'][] = array(
                "svg .seat.$this_row_id" => $this->getColorFromVote(new Vote($row['id']))
            );
        }

        return $diagram;

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