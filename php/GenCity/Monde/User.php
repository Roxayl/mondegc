<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;

class User extends BaseModel {

    static function getUserPermission($label) {

        $permissions = array(
            5 => "Maire",
            10 => "Dirigeant",
            15 => "Juge tempérant",
            20 => "OCGC",
            30 => "Administrateur"
        );
        if(is_numeric($label)) {
            return $permissions[$label];
        } else {
            return array_flip($permissions)[$label];
        }

    }

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

    public function minStatus($status) {

        if(!is_numeric($status))
            $status = self::getUserPermission($status);

        return $this->model->ch_use_statut >= $status;

    }

}