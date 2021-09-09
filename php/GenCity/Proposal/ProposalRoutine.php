<?php

namespace GenCity\Proposal;

use App\Jobs\Discord;
use App\Models\OcgcProposal;

class ProposalRoutine
{
    private ProposalList $proposalList;

    public function __construct()
    {
        $this->proposalList = new ProposalList;

        $this->runRoutine();
    }

    /**
     * Permet de limiter l'exécution d'une routine, en fonction de la génération d'un nombre aléatoire.
     * Sur les environnements de développement, cette méthode est inopérante et renvoie toujours <code>false</code>.
     * @param int $probability Probabilté d'exécution (e.g. mettre "4" pour 1 fois sur 4).
     * @return bool Si cette méthode renvoie <code>true</code>, la routine ne devrait pas être exécutée.
     *              Dans le cas contraire, il faudra l'exécuter.
     */
    private function shouldThrottle(int $probability = 4): bool
    {
        // Pour des raisons de performance, exécuter une fois sur quatre en moyenne.
        return (app()->environment() === 'production' && rand(1, $probability) !== 1);
    }

    /**
     * Exécute toutes les tâches de routine définies dans les méthodes ci-dessous.
     */
    public function runRoutine(): void
    {
        $this->checkValidPending();
        $this->sendPendingNotifications();
        $this->sendFinishedNotifications();
    }

    /**
     * Cette fonction met à jour toutes les propositions en attente de validation par l'OCGC
     * et créés il y a plus d'une semaine et les définit comme validés par l'OCGC (principe
     * d'accord sans réponse).
     */
    private function checkValidPending(): void
    {
        $validationPeriodExpired = $this->proposalList->getValidationPeriodExpired();
        /** @var Proposal $thisProposal */
        foreach($validationPeriodExpired as $thisProposal) {
            $proposalValidate = new ProposalValidate($thisProposal);
            $proposalValidate->accept();
        }
    }

    /**
     * Envoie les notifications sur Discord lorsqu'une proposition est en cours de vote.
     */
    private function sendPendingNotifications(): void
    {
        if($this->shouldThrottle()) {
            return;
        }

        $pendingVotes = $this->proposalList->getPendingVotes();

        foreach($pendingVotes as $proposal) {
            $eloquentProposal = OcgcProposal::find($proposal->get('id'));
            Discord\NotifyVotingProposal::dispatch($eloquentProposal);
        }
    }

    /**
     * Envoie les notifications sur Discord lorsqu'une proposition se termine.
     */
    private function sendFinishedNotifications(): void
    {
        if($this->shouldThrottle()) {
            return;
        }

        $finishedProposals = $this->proposalList->getFinished(6);

        foreach($finishedProposals as $proposal) {
            /** @var OcgcProposal $eloquentProposal */
            $eloquentProposal = OcgcProposal::find($proposal->get('id'));

            // - Vérifier que la proposition existe
            // - Eviter d'envoyer des notifications pour des propositions beaucoup trop anciennes.
            if(! is_null($eloquentProposal) && $eloquentProposal->debate_end->diffInDays() <= 2) {
                Discord\NotifyFinishedProposal::dispatch($eloquentProposal);
            }
        }
    }
}
