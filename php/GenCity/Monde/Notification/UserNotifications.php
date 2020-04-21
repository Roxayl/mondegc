<?php

namespace GenCity\Monde\Notification;


class UserNotifications {

    private $user;

    public function __construct($user) {

        $this->user = $user;

    }

    /**
     * @param int $limit
     * @param int $offset
     * @param null $filter
     * @return Notification[]
     */
    public function getNotifications($limit = 10, $offset = 0, $filter = null) {

        $where_sql = '';
        if($filter === 'unread') {
            $where_sql = ' AND unread = 1 ';
        }

        $sql = sprintf('SELECT * FROM notifications
            WHERE recipient_id = %s ' . $where_sql . '
            LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset,
            GetSQLValueString($this->user->get('ch_use_id'), 'int')
        );
        $query = mysql_query($sql) or die(mysql_error());

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new Notification($row);
        }

        return $return;

    }

    public function getUserId() {
        return $this->user_id;
    }

}