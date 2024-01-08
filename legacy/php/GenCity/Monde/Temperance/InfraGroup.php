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
            escape_sql($this->get('nom_groupe')),
            escape_sql($this->get('url_image')),
            escape_sql($this->get('order'))
        );

        mysql_query($query);

        // On réinitialise le modèle
        $this->model = new InfraGroupModel(mysql_insert_id());

    }

    public function update(): void {

        $structure = $this->model->getStructure();

        $query = 'UPDATE infrastructures_groupes SET ';

        foreach($structure as $field => $default) {
            $query .= ' `' . $field . '` = ' . escape_sql($this->get($field));
            end($structure);
            if($field !== key($structure)) {
                $query .= ', ';
            }
        }

        $query .= ' WHERE id = ' . escape_sql($this->get('id'));
        mysql_query($query);

    }

    public function validate() {

        $return = array();

        return $return;

    }

    public function delete() {

        $infraList = Infrastructure::getListFromGroup($this);
        $infraOfficielle = InfraOfficielle::getListFromGroup($this);

        if(count($infraList) > 0 || count($infraOfficielle) > 0) {
            return false;

        } else {

            $query = sprintf("DELETE FROM infrastructures_groupes WHERE id = %s",
                escape_sql($this->get('id')));
            mysql_query($query);

            $query = sprintf("DELETE FROM infrastructures_officielles_groupes WHERE ID_groupes = %s",
                escape_sql($this->get('id')));
            mysql_query($query);

            $this->model = null;
            return true;

        }

    }

}