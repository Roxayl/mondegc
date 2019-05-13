<?php

namespace GenCity\Proposal;


class ProposalList {

    private $list = array();

    public function __construct() {

    }

    private function setListFromQuery($query) {

        $mysql_query = mysql_query($query);

        while($proposal = mysql_fetch_assoc($mysql_query)) {
            $this->list[] = new Proposal($proposal['id']);
        }

        return $this->list;

    }

    public function getPendingVotes() {

        $query = 'SELECT id FROM ocgc_proposals WHERE NOW() BETWEEN debate_start AND debate_end';
        return $this->setListFromQuery($query);

    }

    public function getPendingDebate() {

        $query = 'SELECT id FROM ocgc_proposals WHERE debate_start < NOW() AND is_valid = 2';
        return $this->setListFromQuery($query);

    }

    public function getPendingValidation() {

        $query = 'SELECT id FROM ocgc_proposals WHERE debate_start < NOW() AND is_valid = 1';
        return $this->setListFromQuery($query);

    }

    public function getFinished() {

        $query = 'SELECT id FROM ocgc_proposals WHERE debate_end > NOW()';
        return $this->setListFromQuery($query);

    }

}