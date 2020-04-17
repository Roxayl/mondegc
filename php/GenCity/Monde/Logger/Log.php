<?php

namespace GenCity\Monde\Logger;
use Squirrel\BaseModel;


class Log extends BaseModel {

    public function __construct($data = null) {

        $this->model = new LogModel($data);

    }

    static function getAll() {

        $sql = 'SELECT * FROM ' . LogModel::$tableName . ' ORDER BY created DESC';
        $query = mysql_query($sql);

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new Log($row['id']);
        }
        return $return;

    }

}