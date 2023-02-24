<?php

namespace GenCity\Proposal;
use Squirrel\ModelStructureInterface;


class VoteModel implements ModelStructureInterface {

    static $tableName = 'ocgc_votes';
    private $info = array();

    private static $structure = [];

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

        if(! array_key_exists(self::$tableName, self::$structure)) {
            $query = mysql_query('DESCRIBE ' . self::$tableName);
            $finalTable = [];
            while($row = mysql_fetch_assoc($query)) {
                $finalTable[$row['Field']] = $row['Default'];
            }
            self::$structure[self::$tableName] = $finalTable;
        }

        return self::$structure[self::$tableName];

    }

    public function __get($prop) {

        return $this->info[$prop];

    }

    public function __set($prop, $value) {

        $this->info[$prop] = $value;

    }

}