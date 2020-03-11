<?php

namespace GenCity\Monde\Temperance;
use Squirrel\BaseModel;

class InfraGroup extends BaseModel {

    public function __construct($data = null) {

        $this->model = new InfraGroupModel($data);

    }

    static function getAll() {

        $sql = 'SELECT * FROM infrastructures_groupes ORDER BY `order`, created';
        $query = mysql_query($sql);

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new InfraGroup($row['id']);
        }
        return $return;

    }

    public function create() {

        $query = 'INSERT INTO infrastructures_groupes
          (nom_groupe, url_image, `order`, created) 
          VALUES(%s, %s, %s, NOW())';

        $query = sprintf($query,
            GetSQLValueString($this->get('nom_groupe')),
            GetSQLValueString($this->get('url_image')),
            GetSQLValueString($this->get('order'))
        );

        mysql_query($query) or getErrorMessage('error', "Impossible !");

        // On réinitialise le modèle
        $this->model = new InfraGroupModel(mysql_insert_id());

    }

    public function update() {

        $structure = $this->model->getStructure();

        $query = 'UPDATE infrastructures_groupes SET ';

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

    public function validate() {

        $return = array();

        return $return;

    }

    public function delete() {

        $infraList = Infrastructure::getListFromGroup($this);
        $infraOfficielle = InfraOfficielle::getListFromGroup($this);

        if(count($infraList) > 0 || count($infraOfficielle) > 0) {
            getErrorMessage("error", "Vous ne pouvez pas supprimer ce groupe
                d'infrastructure car il existe des <strong>infrastructures</strong> ou des
                <strong>infrastructures officielles</strong> appartenant à ce groupe d'infrastructures.
                Assurez-vous qu'aucune <strong>infrastructure</strong> ou <strong>infrastructure
                officielle</strong> n'est affiliée à ce groupe avant de continuer.");
        } else {

            $query = sprintf("DELETE FROM infrastructures_groupes WHERE id = %s",
                GetSQLValueString($this->get('id')));
            mysql_query($query);

            $query = sprintf("DELETE FROM infrastructures_officielles_groupes WHERE ID_groupes = %s",
                GetSQLValueString($this->get('id')));
            mysql_query($query);

            $this->model = null;

        }

    }

}