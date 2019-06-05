<?php


/** @var \GenCity\Proposal\Proposal $formProposal */
$formProposal = $data['formProposal'];

$voteList = $formProposal->getVote();

$results = $voteList->getResultsByResponses();

$totalVotes = $voteList->getTotalVotes();
$absVotes   = $voteList->getAbstentionVotes();

?>

<p><i>
    <?= $totalVotes - $absVotes ?> votes exprimés • Participation :
    <?= number_format( (1 - $absVotes / $totalVotes) * 100,
                        1, ',', ' ') ?>%
</i></p>

<!-- Réponses -->
<ul class="proposal-responses">
<?php foreach($results as $key => $result): ?>
    <?php $voteArray = array(
        'reponse_choisie' => $result['reponse_choisie']
    );
    $thisColor = $voteList->getColorFromVote(
            new \GenCity\Proposal\Vote($voteArray)); ?>
    <li class="btn-block" style="color: <?= $thisColor ?>; background-color: unset; border-color: unset;"
        data-default-color="<?= $thisColor ?>">
        <label>
            <span style="font-size: 24px;">
                <?= $formProposal->get("reponse_{$result['reponse_choisie']}") ?>
            </span>
            <span class="pull-right" style="text-transform: none;">
                <span style="font-size: 24px;"><?= number_format( $result['pct'] * 100,
                        1, ',', ' ') ?>%</span><br />
                <?= $result['nbr_votes'] ?> vote<?= $result['nbr_votes'] > 1 ? 's' : '' ?>
            </span>
        </label>
    </li>
<?php endforeach; ?>
</ul>