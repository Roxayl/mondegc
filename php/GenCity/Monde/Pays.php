<?php

namespace GenCity\Monde;
use mysql_xdevapi\Exception;
use Squirrel\BaseModel;

class Pays extends BaseModel {

    public function __construct($data = null) {

        $this->model = new PaysModel($data);

    }

    public function getLeaders() {

        $query = mysql_query(sprintf('SELECT permissions, ch_use_login, ch_use_lien_imgpersonnage FROM users JOIN users_pays ON ch_use_id = ID_user AND ID_pays = %s', GetSQLValueString($this->model->ch_pay_id, 'int')));
        $result = array();
        while($row = mysql_fetch_assoc($query)){
             $result[] = $row;
        }
        return $result;

    }

    static function getPermissionName($permission) {

        switch($permission) {
            case 5:
                return 'Maire';
            case 10:
                return 'Dirigeant';
            default:
                throw new \UnexpectedValueException("Erreur : type de permission non existant");
        }

    }

}