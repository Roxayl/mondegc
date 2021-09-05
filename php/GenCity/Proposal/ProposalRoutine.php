<?php

namespace GenCity\Proposal;

use App\Jobs\Discord\NotifyVotingProposal;
use App\Models\OcgcProposal;

class ProposalRoutine {

    public function __construct() {

        $this->runRoutine();

    }

    public function runRoutine() {

        $this->checkValidPending();
        $this->sendNotifications();

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

    /**
     * Envoie les notifications sur Discord lorsqu'une proposition est en cours de vote.
     */
    private function sendNotifications()
    {
        // Exécuter une fois sur quatre en moyenne.
        $rand = rand(1, 4);
        if($rand !== 1) return;

        $pendingVotes = (new ProposalList)->getPendingVotes();
        foreach($pendingVotes as $proposal) {
            $eloquentProposal = OcgcProposal::find($proposal->get('id'));
            NotifyVotingProposal::dispatch($eloquentProposal);
        }
    }

}
