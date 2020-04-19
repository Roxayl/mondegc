<?php

namespace GenCity\Monde\Institut;
use Squirrel\BaseModel;


class Institut extends BaseModel {

    public function __construct($data = null) {

        $this->model = new InstitutModel($data);

    }

}