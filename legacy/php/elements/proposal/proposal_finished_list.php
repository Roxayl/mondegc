<?php

/** @var \GenCity\Proposal\Proposal[] $proposalList */
$proposalList = $data['proposalList'];

?>

<?php foreach($proposalList as $proposal):

    $info = '';
    $btn_text = '';

    $voteList = $proposal->getVote();
    $decisionMaker = new \GenCity\Proposal\ProposalDecisionMaker($voteList);
    $decisionFormat = $decisionMaker->outputFormat();

    $bg_color = 'inherit';
    $text_color = 'inherit';

    if(count($decisionFormat) > 1) {
        $info .= '<em>Second tour :</em><br>';
    }
    foreach($decisionFormat as $thisDecision) {
        if($thisDecision['color'] === '#fafafa')
            $thisDecision['color'] = '#0a0a0a';
        $info .= '<h4 style="font-style: normal; color: ' . $thisDecision['color'] . ';">'
              . __s($thisDecision['intitule']) . '</h4><br>';
        $bg_color = $thisDecision['color'];
        $text_color = $thisDecision['color'] !== '#fafafa' ? '#fafafa' : '#0a0a0a';
    }
    ?>

    <div class="proposal-active-container well well-light">
        <div class="row-fluid">

          <div class="span7">
            <small style="background-color: <?= $bg_color ?>; padding: 2px 3px; color: <?= $text_color ?>">
                <?= $proposal->getProposalId() ?> -
                <?= \GenCity\Proposal\Proposal::$typeDetail[$proposal->get('type')] ?>
                (<?= __s($proposal->get('type')) ?>)</small><br>
            <a href="back/ocgc_proposal.php?id=<?= $proposal->get('id') ?>"><h4>
                <?= __s($proposal->get('question')) ?>
            </h4></a>
            <small><em> par
                <a href="page-pays.php?ch_pay_id=<?= $proposal->getPaysAuthor()->get('ch_pay_id') ?>">
                    <img src="<?= __s($proposal->getPaysAuthor()->get('ch_pay_lien_imgdrapeau')) ?>"
                            class="img-menu-drapeau">
                    <?= __s($proposal->getPaysAuthor()->get('ch_pay_nom')) ?>
                </a>
            </em></small>
          </div>

          <div class="span4">
            <small><em>Proposée le <?= __s(dateFormat($proposal->get('created'))) ?> et votée le
                       <?= __s(dateFormat($proposal->get('debate_end'))) ?><br>
                    <?= $info ?></em></small>
          </div>

          <div class="span1">
            <div class="cta-title pull-right-cta">
                <?php if(!empty($btn_text)): ?>
                <a href="back/ocgc_proposal.php?id=<?= $proposal->get('id') ?>"
                   class="btn btn-primary btn-cta"><?= $btn_text ?></a>
                <?php endif; ?>
            </div>
          </div>

        </div>
    </div>

<?php endforeach; ?>