<?php

namespace GenCity\Proposal;
use DateTime;
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
                'targetedField' => 'type_reponse',
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
                        'targetedField' => "reponse_$i",
                        'errorMessage' => "Votre réponse $i est trop courte ou trop longue (+ 255 caractères)"
                    );
                }
            }
        }

        // Vérifier la date des débats

        $setDebateEnd = DateTime::createFromFormat(self::$date_formatting, $this->get('debate_start'));
        $setDebateEnd->modify('+2 days');
        $this->set('debate_end', $setDebateEnd->format(self::$date_formatting));

        if(!$this->isValidDebateDate()) {
            $return[] = array(
                'targetedField' => 'debate_start',
                'errorMessage' => "La date des débats n'est pas valide. Elle doit se situer durant une session plénière."
            );
        }

        return $return;

    }

    static function getNextDebates($getDebateEnd = false) {

        $debatePeriods = array();

        for($i = 0; $i < 3; $i++) {
            $start_week_string = '';
            $end_week_string = '';

            $start_bonus_week = $i;
            $end_bonus_week = $i;

            if(date('D') === 'Fri') {
                $end_bonus_week++;
            }

            if($start_bonus_week > 0) {
                $start_week_string = "+$start_bonus_week week" . ($start_bonus_week > 1 ? 's' : '');
            }
            if($end_bonus_week > 0) {
                $end_week_string = "+$end_bonus_week week" . ($end_bonus_week > 1 ? 's' : '');
            }

            $timeNextDebateStart = strtotime("next friday $start_week_string");
            $timeNextDebateEnd = strtotime("next sunday $end_week_string");
            $dateNextDebateStart = date(self::$date_formatting, $timeNextDebateStart);
            $dateNextDebateEnd = date(self::$date_formatting, $timeNextDebateEnd);
            if($getDebateEnd) {
                $debatePeriods[] = array(
                    'debate_start' => $dateNextDebateStart,
                    'debate_end' => $dateNextDebateEnd
                );
            } else {
                $debatePeriods[$dateNextDebateStart] = '';
            }
        }

        return $debatePeriods;

    }

    public function isValidDebateDate() {

        $start_is_friday = date('D', strtotime($this->get('debate_start'))) === "Fri";
        $end_is_saturday = date('D', strtotime($this->get('debate_end'))) === "Sun";
        return ($start_is_friday && $end_is_saturday);

    }

}