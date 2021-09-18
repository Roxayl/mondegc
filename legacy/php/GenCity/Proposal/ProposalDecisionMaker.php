<?php

namespace GenCity\Proposal;


class ProposalDecisionMaker {

    private $voteList;
    private $proposal;

    public function __construct(VoteList $voteList) {

        $this->voteList = $voteList;
        $this->proposal = $voteList->getProposal();

    }

    public function decisionRule() {

        $acceptedResponses = array();

        if($this->proposal->get('type_reponse') === 'dual') {

            $results = $this->voteList->getResultsByResponses('reponse_choisie');

            if($results[1]['pct'] > (float)$this->proposal->get('threshold') + 0.001) {
                // pour ; acceptée
                $acceptedResponses[] = $results[1]['reponse_choisie'];
            } else {
                // contre ; refusée
                $acceptedResponses[] = $results[2]['reponse_choisie'];
            }

        } else {

            $results = $this->voteList->getResultsByResponses('count');
            if($results[0]['pct'] > (float)$this->proposal->get('threshold') + 0.001) {
                // Réponse #1 en votes
                $acceptedResponses[] = $results[0]['reponse_choisie'];
            } else {

                // Réponse #1 en votes
                $acceptedResponses[] = $results[0]['reponse_choisie'];

                $previousResponseNumberVotes = null;
                // On essaye de voir s'il y a une deuxième réponse qui peut être amené à passer au second tour.
                for($i = 1; $i < Proposal::$maxResponses; $i++) {
                    // On ignore les votes blancs/nuls, qui ne doivent pas passer au second tour.
                    if(!in_array((int)$results[$i]['reponse_choisie'], array(0, 1), true)) {
                        // Si nombre de votes égal au nombre de votes précédent, on l'ajoute au second tour.
                        if( is_null($previousResponseNumberVotes) ||
                            $previousResponseNumberVotes === (int)$results[$i]['nbr_votes'] )
                        {
                            $acceptedResponses[] = $results[$i]['reponse_choisie'];
                            $previousResponseNumberVotes = (int)$results[$i]['nbr_votes'];
                        } else {
                            break;
                        }
                    }
                }

            }

        }

        if(count($acceptedResponses) == 0) {
            throw new \LogicException('Aucune réponse choisie !');
        }
        return $acceptedResponses;

    }

    public function outputFormat() {

        $acceptedResponses = $this->decisionRule();

        $return = array();

        if($this->proposal->get('type_reponse') === 'dual') {

            foreach($acceptedResponses as $reponse) {
                $return[$reponse] = array(
                    'reponse_choisie' => $reponse,
                    'intitule' => ((int)$reponse === 1 ? 'Acceptée' : 'Rejetée'),
                    'color' => ((int)$reponse === 1 ? '#0D911F' : '#910F0F')
                );
                // TODO! Trouver un moyen pour obtenir facilement les couleurs
            }

        } else {

            foreach($acceptedResponses as $reponse) {
                $return[$reponse] = array(
                    'reponse_choisie' => $reponse,
                    'intitule' => $this->proposal->get('reponse_' . $reponse),
                    'color' => '#fafafa'
                );
            }

        }

        return $return;

    }

}