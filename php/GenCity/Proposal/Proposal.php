<?php

namespace GenCity\Proposal;
use GenCity\Monde\Pays;
use GenCity\Monde\User;
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

        /* VERIFICATIONS DE BASE */

        // Vérifier que le pays existe.
        $pays = new Pays($this->get('ID_pays'));
        if(!isset($pays)) {
            $return[] = array(
                'targetedField' =>'ID_pays',
                'errorMessage' => "Ce pays n'existe pas."
            );
        }

        // Vérifier les permissions de dirigeant de l'utilisateur.
        $thisUser = new User($_SESSION['user_ID']);
        $paysUserPermission = $pays->getUserPermission($thisUser);
        if($paysUserPermission < Pays::$permissions['dirigeant']) {
            $return[] = array(
                'targetedField' => 'ID_pays',
                'errorMessage' => "Vous n'êtes pas le dirigeant de ce pays."
            );
        }

        /* VERIFICATION DES CHAMPS */

        // Vérifier type de proposition
        if(!in_array($this->get('type'), array("RP", "IRL"))) {
            $return[] = array(
                'targetedField' => 'type',
                'errorMessage' => "Le type de proposition (sondage ou résolution) n'existe pas."
            );
        }

        // Vérifier la question
        if(mb_strlen($this->get('question')) < 2 || mb_strlen($this->get('question')) > 255) {
            $return[] = array(
                'targetedField' => 'question',
                'errorMessage' => "Votre proposition est trop courte ou trop longue (+ 255 caractères)"
            );
        }

        if(!in_array($this->get('type_reponse'), array('dual', 'multiple'))) {
            $return[] = array(
                'targetedField' => 'type',
                'errorMessage' => "Le type de réponse (POUR/CONTRE ou personnalisé) spécifié est incorrect."
            );
        }

        // type_reponse = dual
        if($this->get('type_reponse') === 'dual') {
            $this->set('reponse_1', 'POUR');
            $this->set('reponse_2', 'CONTRE');
            $this->set('reponse_3', '');
            $this->set('reponse_4', '');
            $this->set('reponse_5', '');
        }

        // type_reponse = multiple
        else {
            $empty_remainder = false;
            for($i = 1; $i <= 5; $i++) {
                if($i > 2 && empty($this->get("reponse_$i")) && !$empty_remainder) {
                    $empty_remainder = true;
                }

                if($empty_remainder) {
                    $this->set("reponse_$i", '');
                    continue;
                }

                if(mb_strlen($this->get("reponse_$i")) < 2 || mb_strlen($this->get("reponse_$i")) > 255) {
                    $return[] = array(
                        'targetedField' => 'question',
                        'errorMessage' => "Votre réponse $i est trop courte ou trop longue (+ 255 caractères)"
                    );
                }
            }
        }

        return $return;

    }

}