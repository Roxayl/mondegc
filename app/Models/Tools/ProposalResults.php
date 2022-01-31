<?php

namespace App\Models\Tools;

use App\Models\OcgcProposal;
use App\Models\OcgcVote;
use CondorcetPHP\Condorcet\Election;

class ProposalResults
{
    private Election $election;

    private OcgcProposal $proposal;

    private string $algorithm;

    public function __construct(Election $election, OcgcProposal $proposal)
    {
        $this->election  = $election;
        $this->proposal  = $proposal;
        $this->algorithm = $this->proposal->getRankingAlgorithm();
    }

    public function runElection(): self
    {
        /** @var string $response */
        foreach($this->proposal->responses() as $id => $response) {
            $this->election->addCandidate();
        }

        /** @var OcgcVote $vote */
        foreach($this->proposal->votes as $vote) {
            $this->election->addVote($vote->getResponseLabel());
        }

        return $this;
    }

    public function generateResults()
    {
        //
    }
}
