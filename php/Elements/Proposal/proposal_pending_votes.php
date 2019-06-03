<?php

use GenCity\Monde\Pays;
use GenCity\Proposal\Vote;

$formProposal = $data['formProposal'];
$userVotes = $data['userVotes'];

/** @var Vote $thisVote */
foreach($userVotes as $thisVote):

    $thisPays = new Pays($thisVote->get('ID_pays'));
    ?>

    <form method="POST" action="ocgc_proposal.php?id=<?= $formProposal->get('id') ?>">
        <input type="hidden" name="voteCast[ID_proposal]" value="<?= $formProposal->get('id') ?>">

        <input type="hidden" name="voteCast[id]" value="<?= $thisVote->get('id') ?>">

        <h4><img class="img-menu-drapeau" src="<?= $thisPays->get('ch_pay_lien_imgdrapeau') ?>">
            <?= $thisPays->get('ch_pay_nom') ?></h4>

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