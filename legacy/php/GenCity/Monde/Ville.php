<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;

class Ville extends BaseModel {

    public function __construct($data = null) {

        $this->model = new VilleModel($data);

    }

    static function getListFromPays(Pays $pays) {

        $query = mysql_query(sprintf(
            'SELECT * FROM villes WHERE ch_vil_paysID = %s',
            escape_sql($pays->get('ch_pay_id'))
        ));
        $result = array();
        while($row = mysql_fetch_assoc($query)) {
            $result[] = new Ville($row);
        }
        return $result;

    }

}