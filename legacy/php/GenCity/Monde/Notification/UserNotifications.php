<?php

namespace GenCity\Monde\Notification;


class UserNotifications {

    private $user;

    public function __construct($user) {

        $this->user = $user;

    }

    /**
     * @return Notification[]
     */
    public function getNotifications() {

        $sql = sprintf('SELECT * FROM notifications_legacy
            WHERE recipient_id = %s
              AND (    created > DATE_SUB(NOW(), INTERVAL 3 DAY)
                    OR (unread = 1 AND created > DATE_SUB(NOW(), INTERVAL 1 MONTH))
              )
            ORDER BY id DESC',
            GetSQLValueString($this->user->get('ch_use_id'), 'int')
        );
        $query = mysql_query($sql) or die(mysql_error());

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new Notification($row);
        }

        return $return;

    }

    public function markAsRead() {

        $sql = sprintf('UPDATE notifications_legacy SET unread = 0
            WHERE recipient_id = %s',
            GetSQLValueString($this->user->get('ch_use_id')));
        $query = mysql_query($sql) or die(mysql_error());

    }

    public function getUser() {
        return $this->user;
    }

    public function getUnreadCount() {

        $sql = sprintf('SELECT COUNT(*) AS notifCount FROM notifications_legacy
            WHERE recipient_id = %s AND unread = 1
            ORDER BY id DESC',
            GetSQLValueString($this->user->get('ch_use_id'), 'int')
        );
        $query = mysql_query($sql) or die(mysql_error());

        return (int)mysql_fetch_assoc($query)['notifCount'];

    }

}