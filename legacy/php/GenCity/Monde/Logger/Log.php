<?php

namespace GenCity\Monde\Logger;
use GenCity\Monde\User;
use Squirrel\BaseModel;


class Log extends BaseModel {

    public function __construct($data = null) {

        $this->model = new LogModel($data);

    }

    static function getAll($limit = 8, $offset = 0) {

        $sql = 'SELECT * FROM ' . LogModel::$tableName . ' ORDER BY created DESC
            LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        $query = mysql_query($sql);

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new Log($row['id']);
        }
        return $return;

    }

    static function getTotal() {

        $query = 'SELECT COUNT(*) AS nbrrows FROM ' . LogModel::$tableName;
        $mysql_query = mysql_query($query) or die(mysql_error());
        return (int)mysql_fetch_assoc($mysql_query)['nbrrows'];

    }

    public function create() {

        $query = 'INSERT INTO log
          (target, target_id, type_action, user_id, data_changes, created) 
          VALUES(%s, %s, %s, %s, %s, NOW())';

        $query = sprintf($query,
            GetSQLValueString($this->get('target')),
            GetSQLValueString($this->get('target_id')),
            GetSQLValueString($this->get('type_action')),
            GetSQLValueString($this->get('user_id')),
            GetSQLValueString($this->get('data_changes'))
        );

        mysql_query($query) or die(mysql_error());

    }

    static function createItem($target, $target_id, $type_action, $user_id = null, $data_changes = null) {

        /** @var User $_SESSION['userObject'] */
        if($user_id === null && isset($_SESSION['userObject'])) {
            $user_id = $_SESSION['userObject']->get('ch_use_id');
        }

        $modelData = array(
            'target' => $target,
            'target_id' => $target_id,
            'type_action' => $type_action,
            'user_id' => $user_id,
            'data_changes' => json_encode($data_changes)
        );
        
        $log = new Log($modelData);
        $log->create();

        return $log;

    }

}