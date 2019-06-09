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

    /** * @var array Détermine le type de sondage, IRL ou RP. */
    static $typeDetail = array(
        'IRL' => "Sondage",
        'RP' => "Résolution"
    );

    static $maxResponses = 5;

    private $vote = null;
    private $pays = null;

    public function __construct($data = null) {

        $this->model = new ProposalModel($data);

    }

    /**
     * Créé une nouvelle proposition dans la base de données.
     */
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
                         threshold, is_valid, motive, debate_start, debate_end,
                         res_year, res_id, created, updated)
                 VALUES(
                         %s, %s, %s, %s,
                         %s, %s, %s, %s, %s,
                         %s, %s, %s, %s, %s,
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
             GetSQLValueString($this->get('threshold')),
             GetSQLValueString($this->get('is_valid'), 'int'),
             GetSQLValueString($this->get('motive')),
             GetSQLValueString($this->get('debate_start')),
             GetSQLValueString($this->get('debate_end')),
             GetSQLValueString($this->get('res_id'))
        );
        mysql_query($query) or getErrorMessage('error', "Impossible !");

        // On réinitialise le modèle
        $this->model = new ProposalModel(mysql_insert_id());

        // Créer les votes
        $this->getVote()->createAllVotes();

    }

    /**
     * Met à jour la proposition dans la base de données.
     */
    public function update() {

        $structure = $this->model->getStructure();

        $query = 'UPDATE ocgc_proposals SET ';

        foreach($structure as $field => $default) {
            $query .= ' ' . $field . ' = ' . GetSQLValueString($this->get($field));
            end($structure);
            if($field !== key($structure)) {
                $query .= ', ';
            }
        }

        $query .= ' WHERE id = ' . GetSQLValueString($this->get('id'));
        mysql_query($query);

    }

    /**
     * Valide le formulaire d'une proposition.
     * @return array Un tableau vide en cas d'absence d'erreur, la liste des erreurs sinon.
     */
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

        // Vérifier le seuil de validation

        if(!in_array($this->get('threshold'), array(0.50, 0.66))) {
            $return[] = array(
                'targetedField' => 'threshold',
                'errorMessage' => "Le seuil de validation de la proposition n'est pas valide."
            );
        }

        return $return;

    }

    /**
     * Permet d'obtenir les dates des trois prochaines sessions plénières.
     * @param bool $getDebateEnd Afficher ou non dans l'array retourné la date de fin de débat.
     * @return array La date des trois prochaines sessions plénières valides.
     */
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

    /**
     * Détermine si les dates <code>debate_start</code> et <code>debate_end</code> sont des dates
     * de session plénière valides.
     * @return bool
     */
    public function isValidDebateDate() {

        $start_is_friday = date('D', strtotime($this->get('debate_start'))) === "Fri";
        $end_is_saturday = date('D', strtotime($this->get('debate_end'))) === "Sun";
        return $start_is_friday && $end_is_saturday;

    }

    /**
     * Définit si la proposition est en cours de vote.
     * @return bool
     */
    public function isWithinDebatePeriod() {

        return strtotime($this->get('debate_start')) < time() &&
                                                       time() < strtotime($this->get('debate_end'));

    }

    /**
     * Donne un tableau de la liste des réponses.
     * @return array La liste des réponses, avec leur intitulé.
     */
    public function getResponses() {

        $return = array(0 => 'Blanc');
        for($i = 1; $i <= Proposal::$maxResponses; $i++) {
            if(empty($this->get("reponse_$i"))) break;
            $return[$i] = $this->get("reponse_$i");
        }
        return $return;

    }

    /**
     * Donne l'identifiant de la proposition, comprenant l'année sur deux chiffres et
     * l'ordre de création au cours de l'année (e.g. "19-054").
     * @return string L'identifiant de la proposition.
     */
    public function getProposalId() {

        $year = substr($this->get('res_year'), 2);
        $id = (string)$this->get('res_id');
        while(strlen($id) < 3) {
            $id = (string)'0' . $id;
        }
        return "$year-$id";

    }

    /**
     * Initialiser l'objet {@see VoteList} lié à la proposition.
     * @return VoteList|null
     */
    public function getVote() {

        if(is_null($this->vote)) {
            $this->vote = new VoteList($this);
        }
        return $this->vote;

    }

    /**
     * Initialise le pays à l'origine de la proposition.
     * @return Pays|null
     */
    public function getPaysAuthor() {

        if(is_null($this->pays)) {
            $this->pays = new Pays($this->get('ID_pays'));
        }
        return $this->pays;

    }

    /**
     * Renvoie le statut de la proposition. Il existe 5 phases d'une proposition.
     * @param bool $get_text Définit si on souhaite l'identifiant du statut (de 0 à 4) ou le
     * texte à afficher.
     * @return int|string Renvoie le texte si <code>$get_text</code> vaut <code>true</code>,
     * l'identifiant sinon.
     * @throws \Exception
     */
    public function getStatus($get_text = true) {

        $statusText = array(
            0 => "Non validée par l'OCGC",
            1 => "En attente de validation par l'OCGC",
            2 => "En phase de débat",
            3 => "Vote en cours",
            4 => "Vote terminé"
        );

        $validity = (int)$this->get('is_valid');
        $return = 'Inconnu !';

        $now = new DateTime();
        $proposalCreate = new DateTime($this->get('created'));
        $nextWeek = $proposalCreate->add(new \DateInterval('P1W'));
        $validationPeriodPassed = $now > $nextWeek ? true : false;

        if($validity === 1 && !$validationPeriodPassed) {
            $return = self::allValidationStatus('pendingValidation');
        }
        elseif($validity === 0) {
            $return = self::allValidationStatus('notValid');
        }
        elseif($validity === 2 || ($validationPeriodPassed && $validity === 1)) {
            $return = self::allValidationStatus('debatePending');
            if($this->isWithinDebatePeriod()) {
                $return = self::allValidationStatus('votePending');
            } elseif(time() > strtotime($this->get('debate_end'))) {
                $return = self::allValidationStatus('voteFinished');
            }
        }

        return ($get_text ? $statusText[$return] : $return);

    }

    static function allValidationStatus($label) {

        $status = array(
            'notValid' => 0,
            'pendingValidation' => 1,
            'debatePending' => 2,
            'votePending' => 3,
            'voteFinished' => 4
        );

        if(!is_numeric($label)) {
            return $status[$label];
        } else {
            return array_flip($status)[$label];
        }

    }

}