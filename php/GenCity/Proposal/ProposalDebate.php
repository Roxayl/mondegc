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

        if(strlen($this->proposal->get('link_debate')) > 200) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "Le lien du débat est trop long."
            );
        }
        if(strlen($this->proposal->get('link_wiki')) > 200) {
            $return[] = array(
                'targetedField' => null,
                'errorMessage' => "Le lien du wiki est trop long."
            );
        }

        return $return;

    }

}