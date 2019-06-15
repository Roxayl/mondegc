<?php

namespace GenCity\Proposal;


class ProposalList {

    private $list = array();

    public function __construct() {

    }

    private function setListFromQuery($query) {

        $this->list = array();

        $mysql_query = mysql_query($query);

        while($proposal = mysql_fetch_assoc($mysql_query)) {
            $this->list[] = new Proposal($proposal['id']);
        }

        return $this->list;

    }

    public function getAll() {

        $query = 'SELECT id FROM ocgc_proposals ORDER BY
          CASE WHEN NOW() BETWEEN debate_start AND debate_end THEN 1
               WHEN debate_start > NOW() AND is_valid = 2 THEN 2
               WHEN is_valid = 1 THEN 3
               ELSE 4 END, debate_start DESC, created DESC';
        return $this->setListFromQuery($query);

    }

    public function getPendingVotes() {

        $query = 'SELECT id FROM ocgc_proposals WHERE NOW() BETWEEN debate_start AND debate_end
                  AND is_valid = 2';
        return $this->setListFromQuery($query);

    }

    public function getPendingDebate() {

        $query = 'SELECT id FROM ocgc_proposals WHERE debate_start > NOW() AND is_valid = 2';
        return $this->setListFromQuery($query);

    }

    public function getPendingValidation() {

        $query = 'SELECT id FROM ocgc_proposals WHERE is_valid = 1';
        return $this->setListFromQuery($query);

    }

    public function getValidationPeriodExpired() {

        $query = 'SELECT id FROM ocgc_proposals
                  WHERE NOW() > DATE_ADD(created, INTERVAL 1 WEEK)
                  AND is_valid = 1';
        return $this->setListFromQuery($query);

    }

    public function getFinished() {

        $query = 'SELECT id FROM ocgc_proposals WHERE debate_end < NOW() AND is_valid = 2';
        return $this->setListFromQuery($query);

    }

}