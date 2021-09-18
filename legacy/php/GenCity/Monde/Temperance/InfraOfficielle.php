<?php

namespace GenCity\Monde\Temperance;
use Squirrel\BaseModel;

class InfraOfficielle extends BaseModel {

    private $infraGroup = null;

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

    public function getGroup() {

        if($this->infraGroup === null) {
            $sql = sprintf("SELECT ig.* FROM infrastructures_groupes ig
              JOIN infrastructures_officielles_groupes iog ON  iog.ID_groupes = ig.id
              WHERE ID_infra_officielle = %s",
                GetSQLValueString($this->get('ch_inf_off_id')));
            $query = mysql_query($sql) or die(mysql_error());
            $result = mysql_fetch_assoc($query);
            if($result)
                $this->infraGroup = new InfraGroup($result);
        }
        return $this->infraGroup;

    }

    public function hasGroup() {

        if($this->getGroup() !== null) {
            return true;
        }
        return false;

    }

}