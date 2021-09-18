<?php

namespace GenCity\Monde\Notification;
use Squirrel\BaseModel;


class Notification extends BaseModel {

    public function __construct($data = null) {

        $this->model = new NotificationModel($data);

    }

    protected function create() {

        $query = 'INSERT INTO notifications_legacy
          (recipient_id, type_notif, element, created)
          VALUES(%s, %s, %s, NOW())';

        $query = sprintf($query,
            GetSQLValueString($this->get('recipient_id')),
            GetSQLValueString($this->get('type_notif')),
            GetSQLValueString($this->get('element'))
        );

        mysql_query($query) or die(mysql_error());

        // On réinitialise le modèle
        $this->model = new NotificationModel(mysql_insert_id());

    }

    public function emit($recipients) {

        foreach($recipients as $recipient) {
            $this->set('recipient_id', $recipient);
            $this->create();
        }

    }

}