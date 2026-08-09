<?php

use GenCity\Monde\Pays;
use GenCity\Proposal\Vote;


/** @var \GenCity\Proposal\Proposal $formProposal */
$formProposal = $data['formProposal'];
/** @var Vote[] $userVotes */
$userVotes = $data['userVotes'];

foreach($userVotes as $thisVote):

    $thisPays = new Pays($thisVote->get('ID_pays'));
    ?>

    <form method="POST" action="ocgc_proposal.php?id=<?= e($formProposal->get('id')) ?>">
        <input type="hidden" name="voteCast[ID_proposal]" value="<?= e($formProposal->get('id')) ?>">

        <input type="hidden" name="voteCast[id]" value="<?= e($thisVote->get('id')) ?>">

        <h4><img class="img-menu-drapeau" src="<?= e($thisPays->get('ch_pay_lien_imgdrapeau')) ?>">
            <?= e($thisPays->get('ch_pay_nom')) ?></h4>

        <!-- Réponses -->
        <ul class="proposal-responses">
        <?php foreach($formProposal->getResponses() as $key => $thisResponse): ?>
            <?php $voteArray = array(
                'reponse_choisie' => $key
            );
            $thisColor = $formProposal->getVote()->getColorFromVote(
                    new \GenCity\Proposal\Vote($voteArray)); ?>
            <li style="color: <?= $thisColor ?>; border-color: <?= $thisColor ?>;"
                data-default-color="<?= $thisColor ?>">
                <label><input type="checkbox" value="<?= $key ?>" name="voteCast[reponse_choisie]"
                      <?= ($key === (int)$thisVote->get('reponse_choisie')
                           && $thisVote->get('reponse_choisie') !== null ? 'checked selected' : '') ?>>
                    <?= $thisResponse ?></label>
            </li>
        <?php endforeach; ?>
        </ul>
    </form>

<?php endforeach; ?>