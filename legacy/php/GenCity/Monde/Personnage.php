<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;


class Personnage extends BaseModel {

    public function __construct($data) {

        $this->model = new PersonnageModel($data);

    }

    static function constructFromEntity(BaseModel $entity) {

        $data = self::getDataFromEntity($entity);

        $query = sprintf('SELECT id FROM personnage WHERE entity = %s AND entity_id = %s',
            GetSQLValueString($data['entity']), GetSQLValueString($data['entity_id']));
        $result = mysql_query($query);
        $result = mysql_fetch_assoc($result);
        if(!$result) {
            return null;
        } else {
            $personnage_id = $result['id'];
            return new Personnage($personnage_id);
        }

    }

    static function getDataFromEntity($entity) {

        $data = array();

        /** @var Pays $entity */
        if(is_a($entity, 'GenCity\Monde\Pays')) {
            $data['entity']    = 'pays';
            $data['entity_id'] = $entity->get('ch_pay_id');
        } else {
            throw new \UnexpectedValueException("Type de classe invalide.");
        }

        return $data;

    }

}