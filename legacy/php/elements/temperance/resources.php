<?php

$path = '';
if(isset($data['path']))
    $path = $data['path'];
$resources = $data['resources'];

?>

<div class="boite-bg" style="padding-top:1em; padding-bottom: 5em">
<ul class="token">
  <li class="span2 token-budget" style="width: 10%;"><span title="Budget"><img src="<?= url("assets/img/ressources/budget.png") ?>" alt="icone Budget"></span><br>
    <p style="text-align: center; margin-top: -0.5em;">Budget <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['budget'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-industrie" style="width: 10%;"><span title="Industrie"><img src="<?= url("assets/img/ressources/industrie.png") ?>" alt="icone Industrie"></span><br>
    <p style="text-align: center; margin-top: -0.5em;">Industrie <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['industrie'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-commerce" style="width: 10%;"><span title="Commerce"><img src="<?= url("assets/img/ressources/bureau.png") ?>" alt="icone Commerce"></span><br>
    <p style="text-align: center; margin-top: -0.5em;">Commerce <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['commerce'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-agriculture" style="width: 10%;"><span title="Agriculture"><img src="<?= url("assets/img/ressources/agriculture.png") ?>" alt="icone Agriculture"></span><br>
    <p style="text-align: center; margin-top: -0.5em;">Agriculture <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['agriculture'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-tourisme" style="width: 10%;"><span title="Tourisme"><img src="<?= url("assets/img/ressources/tourisme.png") ?>" alt="icone Tourisme"></span><br>
    <p style="text-align: center; margin-top: -0.5em;">Tourisme <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['tourisme'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-recherche" style="width: 10%;"><span title="Recherche"><img src="<?= url("assets/img/ressources/recherche.png") ?>" alt="icone Recherche"></span><br>
    <p style="text-align: center; margin-top: -0.5em;">Recherche <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['recherche'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-environnement" style="width: 10%;"><span title="Environnement"><img src="<?= url("assets/img/ressources/environnement.png") ?>" alt="icone Environnement"></span><br>
    <p style="text-align: center; margin-top: -0.5em;">Environnement <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['environnement'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-education" style="width: 10%;"><span title="Education"><img src="<?= url("assets/img/ressources/education.png") ?>" alt="icone Education"></span><br>
    <p style="text-align: center; margin-top: -0.5em;">Éducation <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['education'], 0, ',', ' '); ?></span>
    </p>
  </li>
</ul>
</div>