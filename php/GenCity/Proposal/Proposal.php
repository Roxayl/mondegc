<?php

namespace GenCity\Proposal;
use GenCity\Monde\Pays;
use Squirrel\BaseModel;

class Proposal extends BaseModel {

    static $debate_day_start = 'friday';
    static $debate_day_end   = 'saturday';
    static $date_formatting  = 'Y-m-d H:i:s';

    public function __construct($data = null) {

        $this->model = new ProposalModel($data);

    }

    public function create() {

        $query ='INSERT INTO sq_salons_salles(ID_pays, question, is_valid,
                             reponse_1, reponse_2, reponse_3, reponse_4, reponse_5, debate_start, debate_end)
                 VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())';

    }

    public function validate() {

        $return = array();

        $pays = new Pays($this->get('ID_pays'));
        if(!isset($pays)) {
            $return[] = array(
                'targetedField' =>'ID_pays',
                'errorMessage' => "Ce pays n'existe pas."
            );
        }

        if(mb_strlen($this->get('question')) < 2 || mb_strlen($this->get('question')) > 255) {
            $return[] = array(
                'targetedField' => 'question',
                'errorMessage' => "Votre proposition est trop courte ou trop longue (+ 255 caractères)"
            );
        }

    }

}