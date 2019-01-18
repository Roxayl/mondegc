<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;

class Pays extends BaseModel {

    public static $permissions = array(
        'maire' => 5,
        'dirigeant' => 10
    );

    public function __construct($data = null) {

        $this->model = new PaysModel($data);

    }

    /**
     * Obtient la liste des dirigeants et maires d'un pays.
     * @return array Renvoie un array contenant la liste des dirigeants.
     */
    public function getLeaders() {

        $query = mysql_query(sprintf('SELECT users_pays.id AS users_pays_ID, permissions, ch_use_login, ch_use_lien_imgpersonnage FROM users JOIN users_pays ON ch_use_id = ID_user AND ID_pays = %s', GetSQLValueString($this->model->ch_pay_id, 'int')));
        $result = array();
        while($row = mysql_fetch_assoc($query)){
             $result[] = $row;
        }
        return $result;

    }

    /**
     * Renvoie le niveau de permissions d'un membre sur un pays.
     * @param User $user Utilisateur.
     * @return int Renvoie 0 si pas de permission, 5 si maire, 10 si dirigeant.
     */
    public function getUserPermission(User $user) {

        $query = mysql_query(sprintf('SELECT permissions FROM users_pays WHERE ID_pays = %s AND ID_user = %s',
            GetSQLValueString($this->model->ch_pay_id, 'int'),
            GetSQLValueString($user->ch_use_id, 'int')));
        $result = mysql_fetch_assoc($query);
        if(empty($result)) {
            return 0;
        }
        return (int)$result['permissions'];

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