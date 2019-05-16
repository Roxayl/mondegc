<?php

namespace Squirrel;


class BaseModel {

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

}