<?php

namespace GenCity\Monde\Temperance;
use Squirrel\BaseModel;

class Infrastructure extends BaseModel {

    public function __construct($data = null) {

        $this->model = new InfrastructureModel($data);

    }

    static function getListFromGroup(InfraGroup $infraGroup) {

        $return = array();

        $sql = sprintf('SELECT ch_inf_id FROM infrastructures inf
          JOIN infrastructures_officielles_groupes iog ON iog.ID_infra_officielle = inf.ch_inf_off_id
          WHERE ID_groupes = %s',
            GetSQLValueString($infraGroup->get('id')));

        $query = mysql_query($sql);
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new Infrastructure($row['ch_inf_id']);
        }

        return $return;

    }

    public function update() {

        $structure = $this->model->getStructure();

        $query = 'UPDATE infrastructures SET ';

        foreach($structure as $field => $default) {
            $query .= ' `' . $field . '` = ' . GetSQLValueString($this->get($field));
            end($structure);
            if($field !== key($structure)) {
                $query .= ', ';
            }
        }

        $query .= ' WHERE ch_inf_id = ' . GetSQLValueString($this->get('ch_inf_id'));
        mysql_query($query);

    }

}