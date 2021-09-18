<?php

namespace GenCity\Proposal;


class ProposalDebate {

    private $proposal = null;

    public function __construct(Proposal $proposal) {

        $this->proposal = $proposal;

    }

    public function updateLink() {

        $this->proposal->update();

    }

    public function validate() {

        $return = array();

        if(!isset($_SESSION['userObject']) && $_SESSION['userObject']->minStatus('Dirigeant')) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "Vous devez être connecté et être dirigeant d'un pays pour pouvoir modifier"
                                . "ce champ."
            );
        }
        if(strlen($this->proposal->get('link_debate')) > 200) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "Le lien 1 est trop long."
            );
        }
        if(strlen($this->proposal->get('link_debate_name')) > 200) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "L'intitulé du lien 1 est trop long."
            );
        }
        if(strlen($this->proposal->get('link_wiki')) > 200) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "Le lien 2 est trop long."
            );
        }
        if(strlen($this->proposal->get('link_wiki_name')) > 200) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "L'intitulé du lien 2 est trop long."
            );
        }

        return $return;

    }

    public function set($array) {

        foreach($array as $field => $value) {
            $this->proposal->set($field, $value);
        }

    }

}