<?php

use Roxayl\MondeGC\Models\Infrastructure;
use Carbon\Carbon;

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

$ch_inf_id = -1;
if(isset ($_GET['ch_inf_id'])) {
    $ch_inf_id = $_GET['ch_inf_id'];
}

$eloquentInfrastructure = Infrastructure::with('infrastructureOfficielle')
    ->findOrFail($_GET['ch_inf_id']);

?> 

<!-- Modal Header-->
<div class="modal-header">
    <div class="pull-left">
        <img style="width:100px; margin-right: 10px; margin-top:-30px;"
             src="<?= e($eloquentInfrastructure->infrastructureOfficielle->ch_inf_off_icone) ?>"
             alt="Icone infrastructure">
    </div>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">
        <?= e($eloquentInfrastructure->nom_infra) ?>
        <small><?= e($eloquentInfrastructure->infrastructureOfficielle->ch_inf_off_nom)
            ?></small><br>
        <?php
        echo view('infrastructure.judge.components.infrastructurable-snippet', [
            'infrastructure' => $eloquentInfrastructure,
        ]);
        ?>
    </h3>
</div>

<div class="modal-body">

  <div class="row-fluid">

    <div class="span7">
        <img class="hidden-phone img-modal-ressource" id="img" src="<?= e($eloquentInfrastructure->ch_inf_lien_image) ?>" alt="image de l'infrastrucutre">
        <div class="row-fluid">
             <div class="span2 list-thumb-ressource">
             <img onClick="ChangeImage(this.src);" class="img-thumb-ressource"
                  src="<?= e($eloquentInfrastructure->ch_inf_lien_image) ?>" alt="image n°1">
             </div>
        <?php if(!empty($eloquentInfrastructure->ch_inf_lien_image2)) { ?>
             <div class="span2 list-thumb-ressource">
             <img onClick="ChangeImage(this.src);" class="img-thumb-ressource"
                  src="<?= e($eloquentInfrastructure->ch_inf_lien_image2) ?>" alt="image n°2">
             </div>
        <?php } ?>
        <?php if(!empty($eloquentInfrastructure->ch_inf_lien_image3)) { ?>
             <div class="span2 list-thumb-ressource">
             <img onClick="ChangeImage(this.src);" class="img-thumb-ressource"
                  src="<?= e($eloquentInfrastructure->ch_inf_lien_image3) ?>" alt="image n°3">
             </div>
        <?php } ?>
    </div>

    <p><?= e($eloquentInfrastructure->ch_inf_commentaire) ?></p>

    <div style="color: grey;">

        <i class="icon-calendar"></i> Publiée le <?= dateFormat($eloquentInfrastructure->ch_inf_date) ?> &#183;

        <?php if(!empty($eloquentInfrastructure->ch_inf_lien_forum)) { ?>
        <a href="<?= e($eloquentInfrastructure->ch_inf_lien_forum) ?>" target="_blank">
            <div class="external-link-icon"
                 style="background-image:url('http://www.generation-city.com/forum/new/favicon.png');"></div>
            Lien sur le forum</a>
        <?php } ?>
            <?php if(!empty($eloquentInfrastructure->lien_wiki)) { ?> &#183;
        <a href="<?= e($eloquentInfrastructure->lien_wiki) ?>" target="_blank">
            <div class="external-link-icon"
                 style="background-image:url('https://romukulot.fr/kaleera/images/h4FQp.png');"></div>
            Lien sur le Wiki GC</a>
        <?php } ?>

    </div>
    </div>

    <div class="span5">

    <h4>Ressources générées</h4>

    <small>Cette infrastructure génère actuellement :</small>
    <br>
    <div style="margin-left: -175px; width: 170%; scale: 70%;">
        <?php renderElement('temperance/resources', [
                'resources' => $eloquentInfrastructure->getGeneratedResources()->toArray()
        ]); ?>
    </div>
    <small>Rendement de l'infrastructure :
        <strong><?= $eloquentInfrastructure->efficiencyRate() ?>%</strong></small>
    <div class="clearfix"></div>
    <?php

    $diffLastInfluence = $eloquentInfrastructure->influences->max('generates_influence_at');

    if($diffLastInfluence > Carbon::now()) : ?>
        <br>
        <small>L'infrastructure devrait générer
            <?= $diffLastInfluence->diffForHumans() ?> :
        </small>
        <div style="margin-left: 8px;">
            <?php renderElement('temperance/resources_small', [
                    'resources' => $eloquentInfrastructure->getFinalResources()->toArray()
            ]); ?>
        </div>
    <?php endif; ?>

    <?php
    if($eloquentInfrastructure->infrastructurable->getType() === 'organisation') {
        $nbrMembers = $eloquentInfrastructure->infrastructurable->members->count();
        ?>
        <br>
        <small>Cette infrastructure génère pour chaque pays membre de l'organisation :</small>
        <div style="margin-left: 8px;">
            <?php renderElement('temperance/resources_small', [
                    'resources' => array_map(
                        fn($val) => ($val / $nbrMembers),
                        $eloquentInfrastructure->getGeneratedResources()->toArray())]); ?>
        </div>
        <?php
    }
    ?>

    <p>&nbsp;</p>
    <h4>Critère de jugement</h4>
    <p>
        <small style="color: grey; padding-left: 0; margin-left: 0;">
            <?= htmlPurify($eloquentInfrastructure->infrastructureOfficielle->ch_inf_off_desc) ?>
        </small>
    </p>

    <?php if ($eloquentInfrastructure->ch_inf_statut == Infrastructure::JUGEMENT_ACCEPTED): ?>
      <div style="color: #5f7b59;">
          <img src="<?= DEF_URI_PATH ?>assets/img/statutinfra_<?= e($eloquentInfrastructure->ch_inf_statut) ?>.png" alt="Statut"> Acceptée par les juges tempérants<br>
          <small style="color: inherit; padding-left: 20px;">Jugée
            <?php if ($eloquentInfrastructure->ch_inf_juge != NULL): ?>
              par <?= e($eloquentInfrastructure->judge->ch_use_login) ?>
            <?php endif; ?>
            <?php if(!empty($eloquentInfrastructure->judged_at)): ?>
              le <?= dateFormat($eloquentInfrastructure->judged_at) ?>
            <?php endif; ?>
          </small>
      </div>

    <?php elseif ($eloquentInfrastructure->ch_inf_statut == Infrastructure::JUGEMENT_REJECTED): ?>
      <div style="color: #ba5d5d;">
          <p><img src="<?= DEF_URI_PATH ?>assets/img/statutinfra_<?= e($eloquentInfrastructure->ch_inf_statut) ?>.png" alt="Statut"> Refusée par les juges tempérants. Cette infrastructure n'influence pas l'économie.<p>
          <?php if (($eloquentInfrastructure->ch_inf_commentaire_juge != NULL) OR ($eloquentInfrastructure->ch_inf_commentaire_juge != "")): ?>
              <p><strong>Raison&nbsp;:</strong> <em>"<?= htmlPurify($eloquentInfrastructure->ch_inf_commentaire_juge) ?>"</em></p>
          <?php endif; ?>
          <?php if ($eloquentInfrastructure->ch_inf_juge != NULL): ?>
              <small style="color: inherit;">Jugée par
                  <?= e($eloquentInfrastructure->judge->ch_use_login) ?>
                <?php if(!empty($eloquentInfrastructure->judged_at)): ?>
                  le <?= dateFormat($eloquentInfrastructure->judged_at) ?>
                <?php endif; ?>
              </small>
          <?php endif; ?>
      </div>

    <?php else: ?>
      <div style="color: #979797;">
          <img src="<?= DEF_URI_PATH ?>assets/img/statutinfra_<?= e($eloquentInfrastructure->ch_inf_statut) ?>.png" alt="Statut"> En attente de jugement. Son influence n'est pas encore prise en compte.
      </div>

    <?php endif; ?>

    </div>
  </div>
</div>

<div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
</div>

<script type="text/javascript">
    function ChangeImage(url) {
        document.getElementById("img").src = url;
    }
</script>
