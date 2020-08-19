<?php

/** @var \GenCity\Proposal\Proposal[] $proposalList */
$proposalList = $data['proposalList'];

?>

<?php foreach($proposalList as $proposal):

    $info = ''; $btn_text = '';

    if($proposal->getStatus(false) ===
       \GenCity\Proposal\Proposal::allValidationStatus('votePending')) {
        $info = "En vote jusqu'au " . dateFormat($proposal->get('debate_end'), true);
        $btn_text = 'Voter';
    } elseif($proposal->getStatus(false) ===
       \GenCity\Proposal\Proposal::allValidationStatus('debatePending')) {
        $info = "Soumis au vote le " . dateFormat($proposal->get('debate_start'), true);
        $btn_text = "Débattre";
    }
    elseif($proposal->getStatus(false) ===
       \GenCity\Proposal\Proposal::allValidationStatus('pendingValidation')) {
        $info = "À modérer dans les 7 jours suivant la création de la proposition";
        $btn_text = 'Modérer';
    }

    ?>

    <div class="proposal-active-container well well-dark">
        <div class="row-fluid">

          <div class="span7">
            <small><?= $proposal->getProposalId() ?> -
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
            <small><em>Proposé le <?= __s(dateFormat($proposal->get('created'))) ?><br>
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