<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;

class Ville extends BaseModel {

    public function __construct($data = null) {

        $this->model = new VilleModel($data);

    }

}