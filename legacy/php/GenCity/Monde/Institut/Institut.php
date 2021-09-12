<?php

namespace GenCity\Monde\Institut;
use Squirrel\BaseModel;


class Institut extends BaseModel {

    static $instituts = array(
        "ocgc" => 1,
        "geographie" => 2,
        "culture" => 3,
        "histoire" => 4,
        "economie" => 5,
        "politique" => 6
    );

    public function __construct($data = null) {

        $this->model = new InstitutModel($data);

    }

    /**
     * @return Institut[] Liste de tous les organes de l'OCGC.
     */
    static function getAll() {

        $sql = 'SELECT * FROM ' . InstitutModel::$tableName . ' ORDER BY ch_ins_ID ASC';
        $query = mysql_query($sql);

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new Institut($row);
        }
        return $return;

    }

}