<?php

namespace Squirrel;


abstract class ModelStructure implements ModelStructureInterface {

    static $tableName = null;
    static $primary_key = null;
    public $info = array();

    private static $structure = [];

    final public function __construct($data) {

        if(is_numeric($data)) {
            $this->info = $this->populate($data);
        } elseif(is_null($data)) {
            $this->info = $this->getStructure();
        } else {
            $this->info = $data;
        }

    }

    protected function populate($id) {

        $sql = sprintf('SELECT * FROM ' . $this::$tableName .
            ' WHERE ' . $this::$primary_key .' = %s',
            GetSQLValueString($id, 'int'));
        $query = mysql_query($sql);
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

    public function getInfo() {

        return $this->info;

    }

    public function __get($prop) {

        return $this->info[$prop];

    }

    public function __set($prop, $value) {

        $this->info[$prop] = $value;

    }

}