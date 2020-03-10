<?php

namespace GenCity\Monde\Temperance;
use Squirrel\BaseModel;

class InfraOfficielle extends BaseModel {

    public function __construct($data = null) {

        $this->model = new InfraOfficielleModel($data);

    }

    static function getListFromGroup(InfraGroup $infraGroup) {

        $return = array();

        $sql = sprintf('SELECT ch_inf_off_id FROM infrastructures_officielles iof
          JOIN infrastructures_officielles_groupes iog ON iog.ID_infra_officielle = iof.ch_inf_off_id
          WHERE ID_groupes = %s',
            GetSQLValueString($infraGroup->get('id')));

        $query = mysql_query($sql);
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new InfraOfficielle($row['ch_inf_off_id']);
        }

        return $return;

    }

}