<?php

namespace GenCity\Monde;
use Squirrel\ModelStructureInterface;


class PersonnageModel implements ModelStructureInterface {

    static $tableName = 'personnage';
    private $info = array();

    public function __construct($data = null) {

        if(is_numeric($data)) {
            $this->info = $this->populate($data);
        } elseif(is_null($data)) {
            $this->info = $this->getStructure();
        } else {
            $this->info = $data;
        }

    }

    private function populate($id) {

        $query = mysql_query(sprintf('SELECT * FROM '. self::$tableName . ' WHERE id = %s',
            GetSQLValueString($id, 'int')));
        $result = mysql_fetch_assoc($query);
        return $result;

    }

    public function getStructure() {

        $query = mysql_query('DESCRIBE ' . self::$tableName);
        $finalTable = array();
        while($row = mysql_fetch_assoc($query)) {
            $finalTable[$row['Field']] = $row['Default'];
        }
        return $finalTable;

    }

    public function __get($prop) {

        return $this->info[$prop];

    }

    public function __set($prop, $value) {

        $this->info[$prop] = $value;

    }

}