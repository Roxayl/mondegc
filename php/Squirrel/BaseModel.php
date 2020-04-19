<?php

namespace Squirrel;


class BaseModel {

    /**
     * @var ModelStructure
     */
    public $model;

    public function __get($prop) {

        return $this->get($prop);

    }

    public function get($prop) {
        if(is_array($this->model)) {
            return $this->model[$prop];
        } else {
            return $this->model->$prop;
        }
    }

    public function set($prop, $value) {
        if(is_array($this->model)) {
            $this->model[$prop] = $value;
        } else {
            $this->model->$prop = $value;
        }
    }

    public function update() {

        $structure = $this->model->getStructure();

        $query = 'UPDATE ' . ($this->model)::tableName . ' SET ';

        foreach($structure as $field => $default) {
            $query .= ' `' . $field . '` = ' . GetSQLValueString($this->get($field));
            end($structure);
            if($field !== key($structure)) {
                $query .= ', ';
            }
        }

        $query .= ' WHERE id = ' . GetSQLValueString($this->get('id'));
        mysql_query($query) or die(mysql_error());

    }

}