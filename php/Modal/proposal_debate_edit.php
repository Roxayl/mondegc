<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');

// renvoyer les données POST à soi-même
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if(!isset($_GET['ID_proposal'])) {
    getErrorMessage('error', "Cette proposition n'existe pas.", false);
    exit;
}

$formProposal = new \GenCity\Proposal\Proposal($_GET['ID_proposal']);

if(isset($_POST['proposal_debate_edit'])) {
    $proposalDebate = new \GenCity\Proposal\ProposalDebate($formProposal);
    $proposalDebate->set($_POST['proposal_debate_edit']);
    $formValidate = $proposalDebate->validate();
    if(count($formValidate) > 0) {
        getErrorMessage('error', $formValidate);
    } else {
        $formProposal->update();
        getErrorMessage('success', "Les liens ont été modifiés avec succès !");
        header(DEF_URI_PATH . "back/ocgc_proposal.php?id={$formProposal->get('id')}");
        exit();
    }
}

?>

<form action="<?php echo $editFormAction; ?>" name="pays_leader_edit" method="POST" class="form-horizontal" id="proposal_debate_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Gérer les liens des débats de cette proposition</h3>
  </div>

  <div class="modal-body">

      <input name="proposal_debate_edit[ID_proposal]" type="hidden" value="<?= $formProposal->get('id') ?>">

      <div class="control-group">
      <div class="control-label">Lien 1</div>
      <div class="controls">
          <label>
              URL :
              <input type="text" name="proposal_debate_edit[link_debate]"
                     value="<?= $formProposal->get('link_debate') ?>">
          </label>
          <label>
              Intitulé :
              <input type="text" name="proposal_debate_edit[link_debate_name]"
                     value="<?= $formProposal->get('link_debate_name') ?>">
          </label>
      </div>
      </div>

      <div class="control-group">
      <div class="control-label">Lien 2</div>
      <div class="controls">
          <label>
              URL :
              <input type="text" name="proposal_debate_edit[link_wiki]"
                     value="<?= $formProposal->get('link_wiki') ?>">
          </label>
          <label>
              Intitulé :
              <input type="text" name="proposal_debate_edit[link_wiki_name]"
                     value="<?= $formProposal->get('link_wiki_name') ?>">
          </label>
      </div>
      </div>

  </div> <!-- end modal body -->

  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="proposal_debate_edit">
</form>
