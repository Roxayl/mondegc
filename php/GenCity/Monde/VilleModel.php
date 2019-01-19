<?php

namespace GenCity\Monde;
use Squirrel\ModelStructureInterface;


class VilleModel implements ModelStructureInterface {

    static $tableName = 'villes';
    private $info = array();

    public function __construct($data = null) {

        if(is_numeric($data)) {
            $this->info = $this->populate($data);
        } else {
            $this->info = $data;
        }

    }

    private function populate($id) {

        $query = mysql_query(sprintf('SELECT * FROM villes WHERE ch_vil_id = %s',
            GetSQLValueString($id, 'int')));
        $result = mysql_fetch_assoc($query);
        return $result;

    }



    public function __get($prop) {

        if(!isset($this->info[$prop]))
            throw new \Exception("La propriété $prop n'est pas définie pour la classe " . __CLASS__);
        return $this->info[$prop];

    }

    public function __set($prop, $value) {

        $this->info[$prop] = $value;

    }

}