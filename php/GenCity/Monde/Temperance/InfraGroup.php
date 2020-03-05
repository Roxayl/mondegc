<?php

namespace GenCity\Monde\Temperance;
use Squirrel\BaseModel;

class InfraGroup extends BaseModel {

    public function __construct($data = null) {

        $this->model = new InfraGroupModel($data);

    }

    static function getAll() {

        $sql = 'SELECT * FROM infrastructures_groupes';
        $query = mysql_query($sql);

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new InfraGroup($row['id']);
        }
        return $return;

    }

}