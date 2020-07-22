<?php

$path = '';
if(isset($data['path']))
    $path = $data['path'];
$resources = $data['resources'];

?>


<ul class="token">
  <li class="span2 token-budget"><span title="Budget"><img src="<?= url("/assets/img/ressources/budget.png") ?>" alt="icone Budget"></span>
    <p>Budget <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['budget'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-industrie"><span title="Industrie"><img src="<?= url("/assets/img/ressources/industrie.png") ?>" alt="icone Industrie"></span>
    <p>Industrie <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['industrie'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-commerce"><span title="Commerce"><img src="<?= url("/assets/img/ressources/bureau.png") ?>" alt="icone Commerce"></span>
    <p>Commerce <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['commerce'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-agriculture"><span title="Agriculture"><img src="<?= url("/assets/img/ressources/agriculture.png") ?>" alt="icone Agriculture"></span>
    <p>Agriculture <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['agriculture'], 0, ',', ' '); ?></span>
    </p>
  </li>
</ul>
<div class="clearfix"></div>
<ul class="token">
  <li class="span2 token-tourisme"><span title="Tourisme"><img src="<?= url("/assets/img/ressources/tourisme.png") ?>" alt="icone Tourisme"></span>
    <p>Tourisme <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['tourisme'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-recherche"><span title="Recherche"><img src="<?= url("/assets/img/ressources/recherche.png") ?>" alt="icone Recherche"></span>
    <p>Recherche <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['recherche'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-environnement"><span title="Environnement"><img src="<?= url("/assets/img/ressources/environnement.png") ?>" alt="icone Environnement"></span>
    <p>Environn. <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['environnement'], 0, ',', ' '); ?></span>
    </p>
  </li>
  <li class="span2 token-education"><span title="Education"><img src="<?= url("/assets/img/ressources/education.png") ?>" alt="icone Education"></span>
    <p>Education <br>
        <span class="infra-nbr-ressource"><?php echo number_format($resources['education'], 0, ',', ' '); ?></span>
    </p>
  </li>
</ul>
