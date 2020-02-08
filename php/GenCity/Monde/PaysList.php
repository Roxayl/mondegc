<?php

namespace GenCity\Monde;

class PaysList {

    private $list = array();

    public function __construct() {

    }

    private function setListFromQuery($query) {

        $this->list = array();

        $mysql_query = mysql_query($query);

        while($proposal = mysql_fetch_assoc($mysql_query)) {
            $this->list[] = new Pays($proposal['ch_pay_id']);
        }

        return $this->list;

    }

    public function getAll() {

        $query = 'SELECT ch_pay_id FROM pays';
        return $this->setListFromQuery($query);

    }

    public function getActive() {

        $query = '
          SELECT ID_pays AS ch_pay_id, MAX(ch_use_last_log) FROM users_pays
          JOIN users ON ch_use_id = ID_user
          JOIN pays ON ID_pays = ch_pay_id
          WHERE ch_pay_publication != 2
          GROUP BY ch_pay_id
          HAVING MAX(ch_use_last_log) > DATE_SUB(NOW(), INTERVAL 4 MONTH)';
        return $this->setListFromQuery($query);

    }

}