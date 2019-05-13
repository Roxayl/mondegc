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

    static function getUserIdFromLogin($login) {

        $query = mysql_query(sprintf(
            'SELECT ch_use_id FROM users WHERE ch_use_login = %s',
                GetSQLValueString($login, 'text')
            ));
        $result = mysql_fetch_assoc($query);
        if(!isset($result)) {
            return null;
        }
        return $result['ch_use_id'];

    }

    public function getCountries($minPermission = null, $asObject = false) {

        $where_clause = '';
        if(!is_null($minPermission)) {
            $where_clause = ' AND permissions >= ' . $minPermission;
        }

        $query = mysql_query(sprintf('SELECT ch_pay_id, ch_pay_continent, ch_pay_nom, ch_pay_lien_imgdrapeau FROM pays JOIN users_pays ON ch_pay_id = ID_pays AND ID_user = %s' . $where_clause, GetSQLValueString($this->model->ch_use_id, 'int')));
        $result = array();
        while($row = mysql_fetch_assoc($query)){
            if($asObject) {
                $result[] = new Pays($row['ch_pay_id']);
            } else {
                $result[] = $row;
            }
        }
        return $result;

    }

    public function minStatus($status, $eq = '>=') {

        if(!is_numeric($status))
            $status = self::getUserPermission($status);

        if($eq === '>=')
            return $this->model->ch_use_statut >= $status;

        else
            return $this->model->ch_use_statut < $status;

    }

}