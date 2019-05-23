<?php

namespace GenCity\Proposal;
use GenCity\Monde\Pays;
use GenCity\Monde\PaysList;
use GenCity\Monde\User;

class Vote {

    private $proposal = null;
    private $allVotes = array();
    private $voter = array();

    public function __construct(Proposal $proposal) {

        $this->proposal = $proposal;

        // Obtenir tous les votes
        $this->getAllVotes();

        // Définir un votant
        $this->setVoter();

    }

    private function getAllVotes() {

        // Vider l'array avant d'ajouter les votes
        $this->allVotes = array();

        $query = sprintf('SELECT * FROM ocgc_votes WHERE ID_proposal = %s',
            GetSQLValueString($this->proposal->get('id')));
        $mysql_query = mysql_query($query);
        while($thisVote = mysql_fetch_assoc($mysql_query)) {
            $this->allVotes[] = $thisVote;
        }

    }

    public function createAllVotes() {

        $paysList = new PaysList();
        $allowedPays = $paysList->getActive();

        /** @var Pays $pays */
        foreach($allowedPays as $pays) {
            $query = sprintf('
              INSERT INTO ocgc_votes(ID_proposal, ID_pays, reponse_choisie, has_voted, created)
              VALUES(%s, %s, NULL, 0, NOW())',
                GetSQLValueString($this->proposal->get('id')),
                GetSQLValueString($pays->get('ch_pay_id'))
            );
            mysql_query($query);
        }

        // Mettre à jour la liste des votes
        $this->getAllVotes();

    }

    private function setVoter(User $user = null) {

        if(is_null($user)) {
            if(isset($_SESSION['userObject']) && $_SESSION['userObject'] instanceof User)
                $this->setVoter($_SESSION['userObject']);
        } else {
            $this->voter = $user;
        }

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



    }

    public function voteFor() {

    }

}