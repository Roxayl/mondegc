<?php

namespace GenCity\Proposal;
use GenCity\Monde\User;


class ProposalList {

    private $list = array();

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

    public function getUnfinished() {

        $query = 'SELECT id FROM ocgc_proposals WHERE is_valid = 2 AND debate_end > NOW()';
        return $this->setListFromQuery($query);

    }

    public function getFinished($limit = 8, $offset = 0) {

        $query = 'SELECT id FROM ocgc_proposals
                  WHERE debate_end < NOW() AND is_valid = 2
                  ORDER BY debate_end DESC, created DESC
                  LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        return $this->setListFromQuery($query);

    }

    public function getFinishedTotal() {

        $query = 'SELECT COUNT(*) AS nbrrows FROM ocgc_proposals
                  WHERE debate_end < NOW() AND is_valid = 2 ';
        $mysql_query = mysql_query($query);
        return (int)mysql_fetch_assoc($mysql_query)['nbrrows'];

    }

    public function userProposalPendingVotes(User $user) {

        $query = sprintf('SELECT DISTINCT ocgc_proposals.id FROM ocgc_proposals
          INNER JOIN ocgc_votes ON ID_proposal = ocgc_proposals.id
          WHERE NOW() BETWEEN debate_start AND debate_end
            AND ocgc_votes.ID_pays IN(
                           SELECT ID_pays FROM users_pays WHERE ID_user = %s)
            AND is_valid = 2
            AND reponse_choisie IS NULL',
            escape_sql($user->get('ch_use_id')));

        return $this->setListFromQuery($query);

    }

}