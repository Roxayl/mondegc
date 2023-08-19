<?php

namespace GenCity\Proposal;

use Roxayl\MondeGC\Jobs\Discord;
use Roxayl\MondeGC\Models\OcgcProposal;
use Roxayl\MondeGC\Models\Pays;

class ProposalRoutine
{
    private ProposalList $proposalList;

    /**
     * @param ProposalList $proposalList
     */
    public function __construct(ProposalList $proposalList)
    {
        $this->proposalList = $proposalList;
    }

    /**
     * Permet de limiter l'exécution d'une routine, en fonction de la génération d'un nombre aléatoire.
     * Sur les environnements de développement, cette méthode est inopérante et renvoie toujours <code>false</code>.
     *
     * @param int $probability Probabilté d'exécution (e.g. mettre "4" pour 1 fois sur 4).
     * @return bool Si cette méthode renvoie <code>true</code>, la routine ne devrait pas être exécutée.
     *              Dans le cas contraire, il faudra l'exécuter.
     */
    private function shouldThrottle(int $probability = 3): bool
    {
        // Pour des raisons de performance, exécuter une fois sur quatre en moyenne.
        return (app()->environment() === 'production' && rand(1, $probability) !== 1);
    }

    /**
     * Exécute toutes les tâches de routine définies dans les méthodes ci-dessous.
     */
    public function runRoutine(): void
    {
        try {
            $this->checkValidPending();
            $this->updateVotingCountries();
            $this->sendPendingNotifications();
            $this->sendFinishedNotifications();
        } catch(\Exception $ex) {
            if(app()->environment() !== 'production') {
                throw $ex;
            }
        }
    }

    /**
     * Met à jour toutes les propositions en attente de validation par l'OCGC et crées il y a plus
     * d'une semaine et les définit comme validés par l'OCGC (principe d'accord sans réponse).
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
     * Ajoute de nouveaux pays votants à une proposition au fur et à mesure de leur réactivation,
     * pendant la phase précédant le vote.
     */
    public function updateVotingCountries(): void
    {
        $pendingDebate = $this->proposalList->getUnfinished();

        foreach($pendingDebate as $proposal) {
            $eloquentProposal = OcgcProposal::find($proposal->get('id'));
            $activeCountries = Pays::query()->active()->get();
            $eloquentProposal->addVoters($activeCountries->pluck('ch_pay_id'));
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
