<?php

namespace GenCity\Monde;
use Squirrel\ModelStructureInterface;


class PageModel implements ModelStructureInterface {

    static $tableName = 'legacy_pages';
    private $info = array();

    public function __construct($data = null) {

        if(is_scalar($data)) {
            $this->info = $this->populate($data);
        } else {
            $this->info = $data;
        }

    }

    private function populate($id) {

        $query = mysql_query(sprintf('SELECT * FROM legacy_pages WHERE this_id = %s',
            GetSQLValueString($id, 'text')));
        $result = mysql_fetch_assoc($query);
        return $result;

    }

    public function getInfo() {

        return $this->info;

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