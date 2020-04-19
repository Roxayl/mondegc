<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;

class Pays extends BaseModel {

    public static $permissions = array(
        'codirigeant' => 5,
        'dirigeant'   => 10
    );

    public function __construct($data = null) {

        $this->model = new PaysModel($data);

    }

    /**
     * Obtient la liste des dirigeants et co-dirigeants d'un pays.
     * @param int $minPermission Niveau de permission égal ou supérieur à cette valeur.
     * @param bool $setObject Détermine si on renvoie un array d'objets {@see \GenCity\Monde\User}.
     * @return array|User[] Renvoie un array contenant la liste des dirigeants.
     */
    public function getLeaders($minPermission = 0, $setObject = false) {

        $where_query = $minPermission > 0 ? ' AND permissions >= ' . GetSQLValueString($minPermission, 'int') : '';

        $query = mysql_query(sprintf('SELECT users_pays.id AS users_pays_ID, ID_user, permissions, ch_use_login, ch_use_lien_imgpersonnage FROM users JOIN users_pays ON ch_use_id = ID_user AND ID_pays = %s ' . $where_query,
            GetSQLValueString($this->model->ch_pay_id, 'int')));
        $result = array();
        while($row = mysql_fetch_assoc($query)) {
            if($setObject) {
                $result[] = new User($row['ID_user']);
            } else {
                $result[] = $row;
            }
        }
        return $result;

    }

    public function getCharacters() {

        $query = mysql_query(sprintf(
            "SELECT id, entity, entity_id, nom_personnage, predicat, prenom_personnage, biographie,
            titre_personnage, lien_img FROM personnage
            WHERE entity = 'pays' AND entity_id = %s",
            GetSQLValueString($this->model->ch_pay_id, 'int')));
        $result = array();
        while($row = mysql_fetch_assoc($query)) {
            $result[] = $row;
        }
        return $result;

    }

    public function addLeader(User $user, $permission) {

        mysql_query(sprintf(
            'INSERT INTO users_pays(ID_pays, ID_user, permissions) ' .
                        ' VALUES(%s, %s, %s)',
            GetSQLValueString($this->ch_pay_id, 'int'),
            GetSQLValueString($user->ch_use_id, 'int'),
            GetSQLValueString($permission, 'int')
        ));

    }

    public function removeLeader(User $user) {

        mysql_query(sprintf(
            'DELETE FROM users_pays WHERE ID_pays = %s AND ID_user = %s',
            GetSQLValueString($this->ch_pay_id, 'int'),
            GetSQLValueString($user->ch_use_id, 'int')
        ));

    }

    /**
     * Renvoie le niveau de permissions d'un membre sur un pays.
     * @param User $user Utilisateur.
     * @return int Renvoie 0 si pas de permission, 5 si co-dirigeant, 10 si dirigeant.
     */
    public function getUserPermission(User $user = null) {

        if($user === null)
            $user = $_SESSION['userObject'];

        $query = mysql_query(sprintf('SELECT permissions FROM users_pays WHERE ID_pays = %s AND ID_user = %s',
            GetSQLValueString($this->model->ch_pay_id, 'int'),
            GetSQLValueString($user->ch_use_id, 'int')));
        $result = mysql_fetch_assoc($query);
        if(empty($result)) {
            return 0;
        }
        return (int)$result['permissions'];

    }

    public function isActive() {

        $mysql_query = mysql_query(sprintf(
        'SELECT ID_pays AS ch_pay_id, MAX(ch_use_last_log) FROM users_pays
              JOIN users ON ch_use_id = ID_user
              WHERE ID_pays = %s
              GROUP BY ch_pay_id
              HAVING MAX(ch_use_last_log) > DATE_SUB(NOW(), INTERVAL 4 MONTH)',
            GetSQLValueString($this->get('ch_pay_id'))
            ));
        $results = mysql_fetch_assoc($mysql_query);
        return !empty($results);

    }

    static function getPermissionName($permission) {

        switch($permission) {
            case 5:
                return 'Co-dirigeant';
            case 10:
                return 'Dirigeant';
            default:
                throw new \UnexpectedValueException("Erreur : type de permission non existant");
        }

    }

}