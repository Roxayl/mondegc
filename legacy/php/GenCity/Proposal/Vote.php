<?php

namespace GenCity\Proposal;
use GenCity\Monde\Pays;
use Squirrel\BaseModel;

class Vote extends BaseModel {

    private $pays = null;

    public function __construct($data = null) {

        $this->model = new VoteModel($data);

    }

    public function validate(VoteList $voteList, Proposal $proposal) {

        $return = array();

        // Vérifier que le pays peut voter.
        $paysVotes = $voteList->getUserVotes($_SESSION['userObject']);
        if(count($paysVotes) == 0) {
            $return[] = array(
                'targetedField' =>'ID_pays',
                'errorMessage' => "Vous n'êtes pas autorisé à voter à ce scrutin."
            );
        }

        if($proposal->getStatus(false) !== Proposal::allValidationStatus('votePending')) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => ($proposal->isValidDebateDate()) ?
                    "Cette proposition n'est pas à l'ordre du jour." :
                    "L'Assemblée générale ne siège pas pour le moment, vous ne pouvez pas voter."
            );
        }

        $countResponses = $proposal->getResponses();
        if($this->get('reponse_choisie') < 0 && $this->get('reponse_choisie') > $countResponses) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "Votre vote ne correspond pas à une réponse."
            );
        }

        /*$currentVote = new Vote($this->get('id'));
        if(!is_null($currentVote->get('reponse_choisie'))) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "Vous avez déjà voté."
            );
        }*/

        return $return;

    }

    public function castVote() {

        $query = sprintf(
            'UPDATE ocgc_votes SET reponse_choisie = %s WHERE id = %s',
                escape_sql($this->get('reponse_choisie')),
                escape_sql($this->get('id'))
        );
        mysql_query($query);

    }

    public function getPaysAuthor() {

        if($this->pays === null)
            $this->pays = new Pays($this->get('ID_pays'));
        return $this->pays;

    }

}