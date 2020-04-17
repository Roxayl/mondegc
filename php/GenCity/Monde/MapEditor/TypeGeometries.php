<?php

namespace GenCity\Monde\MapEditor;
use Squirrel\BaseModel;


class TypeGeometries extends BaseModel {

    public function __construct($data = null) {

        $this->model = new TypeGeometriesModel($data);

    }

    static function getAll() {

        $sql = 'SELECT * FROM type_geometries ORDER BY created';
        $query = mysql_query($sql);

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $this_group = $row['group_id'];
            $return[$this_group][] = new TypeGeometries($row['id']);
        }
        return $return;

    }

}