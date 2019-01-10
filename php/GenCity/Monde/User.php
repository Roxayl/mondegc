<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;

class User extends BaseModel {

    public function __construct($data = null) {

        $this->model = new UserModel($data);

    }

    public function getCountries() {

        $query = mysql_query(sprintf('SELECT ch_pay_id, ch_pay_continent, ch_pay_nom, ch_pay_lien_imgdrapeau FROM pays JOIN users_pays ON ch_pay_id = ID_pays AND ID_user = %s', GetSQLValueString($this->model->ch_use_id, 'int')));
        $result = array();
        while($row = mysql_fetch_assoc($query)){
             $result[] = $row;
        }
        return $result;

    }

}