<?php

namespace GenCity\Proposal;
use GenCity\Proposal\VoteList;


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

            if($results[1]['pct'] > 0.5) {
                $acceptedResponses[] = $results[1]['reponse_choisie'];
                // pour ; acceptée
            } else {
                $acceptedResponses[] = $results[2]['reponse_choisie'];
                // contre ; refusée
            }

        } else {

            $results = $this->voteList->getResultsByResponses('count');
            if($results[0]['pct'] > 0.5) {
                $acceptedResponses[] = $results[0]['reponse_choisie'];
                // Réponse #1 en votes
            } else {
                $acceptedResponses[] = $results[0]['reponse_choisie'];
                // Réponse #1 en votes
                if((int)$results[1]['reponse_choisie'] !== 0) {
                    $acceptedResponses[] = $results[1]['reponse_choisie'];
                    // Réponse #2 en votes
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