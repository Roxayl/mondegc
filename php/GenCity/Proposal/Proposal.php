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

    static $typeDetail = array(
        'IRL' => "Sondage",
        'RP' => "Résolution"
    );
    static $maxResponses = 5;

    private $vote = null;

    public function __construct($data = null) {

        $this->model = new ProposalModel($data);

    }

    public function create() {

        // Obtenir la res_id max
        // TODO! Plutôt que de définir une variable res_id et res_year, on peut plutôt
        // essayer de définir la valeur de l'identifiant en comptant le nombre de propositions
        // publiées au cours de l'année, ayant une date de création inférieure à celle recherchée.
        $query = 'SELECT (MAX(res_id) + 1) AS max_res_id FROM ocgc_proposals WHERE YEAR(created) = YEAR(CURDATE())';
        $mysql_query = mysql_query($query);
        $current_res_id = mysql_fetch_assoc($mysql_query)['max_res_id'];
        if(empty($current_res_id))
            $current_res_id = 1;

        // Définir variables non définies au préalable dans le formulaire.
        $this->set('is_valid', 1);
        $this->set('motive', null);
        $this->set('res_id', $current_res_id);

        // Requêtes
        $query ='INSERT INTO ocgc_proposals(
                         ID_pays, question, type, type_reponse,
                         reponse_1, reponse_2, reponse_3, reponse_4, reponse_5, 
                         is_valid, motive, debate_start, debate_end,
                         res_year, res_id, created, updated)
                 VALUES(
                         %s, %s, %s, %s,
                         %s, %s, %s, %s, %s,
                         %s, %s, %s, %s,
                         YEAR(CURDATE()), %s, NOW(), NOW())';

        $query = sprintf($query,
             GetSQLValueString($this->get('ID_pays')),
             GetSQLValueString($this->get('question')),
             GetSQLValueString($this->get('type')),
             GetSQLValueString($this->get('type_reponse')),
             GetSQLValueString($this->get('reponse_1')),
             GetSQLValueString($this->get('reponse_2')),
             GetSQLValueString($this->get('reponse_3')),
             GetSQLValueString($this->get('reponse_4')),
             GetSQLValueString($this->get('reponse_5')),
             GetSQLValueString($this->get('is_valid'), 'int'),
             GetSQLValueString($this->get('motive')),
             GetSQLValueString($this->get('debate_start')),
             GetSQLValueString($this->get('debate_end')),
             GetSQLValueString($this->get('res_id'))
        );
        mysql_query($query);

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
            for($i = 1; $i <= self::$maxResponses; $i++) {
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

            if(date('D') === 'Fri' || date('D') === 'Sat') {
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
        return $start_is_friday && $end_is_saturday;

    }

    public function isWithinDebatePeriod() {

        return strtotime($this->get('debate_start')) < time() &&
                                                       time() < strtotime($this->get('debate_end'));

    }

    public function getProposalId() {

        $year = substr($this->get('res_year'), 2);
        $id = (string)$this->get('res_id');
        while(strlen($id) < 3) {
            $id = (string)'0' . $id;
        }
        return "$year-$id";

    }

    public function getVote() {

        if(is_null($this->vote)) {
            $this->vote = new Vote($this);
        }
        return $this->vote;

    }

}