<?php

namespace GenCity\Proposal;

use Roxayl\MondeGC\Models\OcgcProposal;
use Roxayl\MondeGC\Jobs\Discord;

class ProposalValidate {

    private $proposal = null;

    public function __construct(Proposal $proposal) {

        $this->proposal = $proposal;

    }

    public function checkPermission() {

        if(isset($_SESSION['userObject'])
             && $_SESSION['userObject']->getUserPermission('OCGC')) {
            return true;
        }
        return false;

    }

    public function validate() {

        $return = array();

        if(!$this->checkPermission()) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "Vous n'avez pas la permission pour exécuter cette action."
            );
        }

        return $return;

    }

    private function runQuery($is_valid_value) {

        if(count($this->validate()) > 0) {
            throw new \UnexpectedValueException("Validation échouée.");
        }

        $query = sprintf('
            UPDATE ocgc_proposals SET is_valid = %s WHERE id = %s',
            escape_sql($is_valid_value, 'int'),
            escape_sql($this->proposal->get('id'), 'int'));
        mysql_query($query);

    }

    public function accept() {

        $this->runQuery(Proposal::allValidationStatus('debatePending'));

        /** @var OcgcProposal $eloquentProposal */
        $eloquentProposal = OcgcProposal::query()->findOrFail($this->proposal->get('id'));
        Discord\NotifyCreatedProposal::dispatch($eloquentProposal);

        // Cette proposition est acceptée par l'OCGC après sa date de début de vote ;
        // on décale la phase de vote à la semaine suivante.
        if(strtotime($this->proposal->get('debate_start')) < time()) {
            $nextDebates = Proposal::getNextDebates(true);
            $debate_start = date(Proposal::$date_formatting, strtotime($nextDebates[0]['debate_start']));
            $debate_end = date(Proposal::$date_formatting, strtotime($nextDebates[0]['debate_end']));
            $this->proposal->set('debate_start', $debate_start);
            $this->proposal->set('debate_end', $debate_end);
            $this->proposal->update();
        }

    }

    public function reject() {

        $this->runQuery(Proposal::allValidationStatus('notValid'));

    }

    public function update() {

        (int)$this->proposal->get('is_valid') === 2 ? $this->accept() : $this->reject();

    }

}