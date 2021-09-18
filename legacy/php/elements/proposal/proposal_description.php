<?php

/** @var \GenCity\Proposal\Proposal $formProposal */
$formProposal = $data['formProposal'];
$decisionData = $data['decisionData'];

$str = '';

if($formProposal->getStatus(false) >=
    \GenCity\Proposal\Proposal::allValidationStatus('pendingValidation')):

    switch($formProposal->get('type_reponse')) {

        case 'dual':
            $str = "Cette proposition nécessite " . ($formProposal->get('threshold') * 100) . "% " .
                   "de votes favorables afin d'être acceptée.";
            break;

        case 'multiple':

            if($formProposal->getStatus(false) ===
               \GenCity\Proposal\Proposal::allValidationStatus('voteFinished')) {
                if(count($data['decisionData']) > 1) {
                    $str = "Un nouveau tour est organisé entre les modalités arrivées en tête.";
                } else {
                    $str = "La modalité est acceptée à plus de " . ($formProposal->get('threshold') * 100) .
                           "% des votes.";
                }
            }

            else {
                $str = "Une motion est acceptée lorsqu'elle recueille 50% des votes exprimés. Dans le cas " .
                       "contraire, un nouveau tour est organisé entre les modalités arrivées en tête.";
            }

            break;

    }

endif;

echo $str;