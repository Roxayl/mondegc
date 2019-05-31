<?php

namespace GenCity\Proposal;

class ProposalRoutine {

    public function __construct() {

        $this->runRoutine();

    }

    public function runRoutine() {

        $this->checkValidPending();

    }

    /**
     * Cette fonction met à jour toutes les propositions en attente de validation par l'OCGC
     * et créés il y a plus d'une semaine et les définit comme validés par l'OCGC (principe
     * d'accord sans réponse).
     */
    private function checkValidPending() {

        $proposalList = new ProposalList();
        $validationPeriodExpired = $proposalList->getValidationPeriodExpired();
        /** @var Proposal $thisProposal */
        foreach($validationPeriodExpired as $thisProposal) {
            $proposalValidate = new ProposalValidate($thisProposal);
            $proposalValidate->accept();
        }

    }

}