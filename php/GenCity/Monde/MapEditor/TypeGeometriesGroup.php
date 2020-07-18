<?php

namespace GenCity\Monde\MapEditor;
use Squirrel\BaseModel;


class TypeGeometriesGroup extends BaseModel {

    public function __construct($data = null) {

        $this->model = new TypeGeometriesModel($data);

    }

    static function getAll() {

        $sql = 'SELECT * FROM type_geometries_group ORDER BY created_at';
        $query = mysql_query($sql);

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new TypeGeometriesGroup($row['id']);
        }
        return $return;

    }

}