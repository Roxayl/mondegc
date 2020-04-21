<?php

namespace GenCity\Monde\User;
use GenCity\Monde\User;


class UserList {

    private $list = array();

    public function __construct() { }

    private function setListFromQuery($query, $getIdOnly = false) {

        $this->list = array();

        $mysql_query = mysql_query($query);

        while($user = mysql_fetch_assoc($mysql_query)) {
            $this->list[] = $getIdOnly ? $user['ch_use_id'] : new User($user);
        }

        return $this->list;

    }

    public function getAll() {

        $query = 'SELECT ch_use_id FROM users';
        return $this->setListFromQuery($query, true);

    }

    public function getActive() {

        $query = 'SELECT ch_use_id FROM users
                  WHERE ch_use_last_log > DATE_SUB(NOW(), INTERVAL 4 MONTH)';
        return $this->setListFromQuery($query, true);

    }

}