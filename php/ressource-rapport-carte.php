<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

$colname_Pays = "-1";
if (isset($_GET['ch_pay_id'])) {
  $colname_Pays = $_GET['ch_pay_id'];
}

//recuperation ID element
$ch_temp_id = "-1";
if (isset($_GET['ch_temp_id'])) {
  $ch_temp_id = $_GET['ch_temp_id'];
}

//recherche des mesures des zones de la carte

$query_geometries = sprintf("SELECT SUM(ch_geo_mesure) as mesure, ch_geo_type FROM geometries WHERE ch_geo_pay_id = %s AND ch_geo_type != 'maritime' AND ch_geo_type != 'region' GROUP BY ch_geo_type ORDER BY ch_geo_geometries", GetSQLValueString($colname_Pays, "int"));
$geometries = mysql_query($query_geometries, $maconnexion) or die(mysql_error());
$row_geometries = mysql_fetch_assoc($geometries);
?>

<!-- Modal Header-->
<!-- Boutons cach�s -->

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
  <h3 id="myModalLabel">Balance des ressources issues de la carte</h3>
</div>
<div class="modal-body corps-page">
  <?php do { 
		$surface = $row_geometries['mesure'];
		$typeZone = $row_geometries['ch_geo_type'];
		ressourcesGeometrie($surface, $typeZone, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population, $emploi);
		$tot_budget = $tot_budget + $budget;
		$tot_industrie = $tot_industrie + $industrie;
		$tot_commerce = $tot_commerce + $commerce;
		$tot_agriculture = $tot_agriculture + $agriculture; 
		$tot_tourisme = $tot_tourisme + $tourisme;
		$tot_recherche = $tot_recherche + $recherche; 
		$tot_environnement = $tot_environnement + $environnement;
		$tot_education = $tot_education + $education;
		$tot_population = $tot_population + $population;
		$tot_emploi = $tot_emploi + $emploi;
		?>
  <div class="row-fluid">
    <div class="titre-gris">
      <h3 style="margin-left:20px;"><?php echo $label; ?>&nbsp;: <?php echo number_format($surface, 0, ',', ' '); ?> km<?php if ($typeZone == 'megapole' OR $typeZone == 'urbaine' OR $typeZone == 'periurbaine' OR $typeZone == 'industrielle' OR $typeZone == 'maraichere' OR $typeZone == 'cerealiere' OR $typeZone == 'elevage' OR $typeZone == 'prairies' OR $typeZone == 'forestiere' OR $typeZone == 'protegee' OR $typeZone == 'forestiere' OR $typeZone == 'marecageuse' OR $typeZone == 'lagunaire') {; ?><sup>2</sup><?php } ?></h3>
    </div>
    <?php if ($typeZone == 'megapole' OR $typeZone == 'urbaine' OR $typeZone == 'periurbaine' OR $typeZone == 'industrielle' OR $typeZone == 'maraichere' OR $typeZone == 'cerealiere' OR $typeZone == 'elevage' OR $typeZone == 'prairies' OR $typeZone == 'forestiere' OR $typeZone == 'protegee' OR $typeZone == 'forestiere' OR $typeZone == 'marecageuse' OR $typeZone == 'lagunaire') {; ?><p class="pull-center">Population&nbsp;: <?php $chiffre_francais = number_format($population, 0, ',', ' '); echo $chiffre_francais; ?> habitants</p>
<?php } ?>
    <div class="span2">&nbsp;</div>
    <ul class="token">
      <li class="span1"><a title="Budget"><img src="http://www.generation-city.com/monde/assets/img/ressources/budget.png" alt="icone Budget"></a>
        <p>
          <?php $chiffre_francais = number_format($budget, 0, ',', ' '); echo $chiffre_francais; ?>
        </p>
      </li>
      <li class="span1"><a title="Industrie"><img src="http://www.generation-city.com/monde/assets/img/ressources/industrie.png" alt="icone Industrie"></a>
        <p>
          <?php $chiffre_francais = number_format($industrie, 0, ',', ' '); echo $chiffre_francais; ?>
        </p>
      </li>
      <li class="span1"><a title="Commerce"><img src="http://www.generation-city.com/monde/assets/img/ressources/bureau.png" alt="icone Commerce"></a>
        <p>
          <?php $chiffre_francais = number_format($commerce, 0, ',', ' '); echo $chiffre_francais; ?>
        </p>
      </li>
      <li class="span1"><a title="Agriculture"><img src="http://www.generation-city.com/monde/assets/img/ressources/agriculture.png" alt="icone Agriculture"></a>
        <p>
          <?php $chiffre_francais = number_format($agriculture, 0, ',', ' '); echo $chiffre_francais; ?>
        </p>
      </li>
      <li class="span1"><a title="Tourisme"><img src="http://www.generation-city.com/monde/assets/img/ressources/tourisme.png" alt="icone Tourisme"></a>
        <p>
          <?php $chiffre_francais = number_format($tourisme, 0, ',', ' '); echo $chiffre_francais; ?>
        </p>
      </li>
      <li class="span1"><a title="Recherche"><img src="http://www.generation-city.com/monde/assets/img/ressources/recherche.png" alt="icone Recherche"></a>
        <p>
          <?php $chiffre_francais = number_format($recherche, 0, ',', ' '); echo $chiffre_francais; ?>
        </p>
      </li>
      <li class="span1"><a title="Environnement"><img src="http://www.generation-city.com/monde/assets/img/ressources/environnement.png" alt="icone Environnement"></a>
        <p>
          <?php $chiffre_francais = number_format($environnement, 0, ',', ' '); echo $chiffre_francais; ?>
        </p>
      </li>
      <li class="span1"><a title="Education"><img src="http://www.generation-city.com/monde/assets/img/ressources/education.png" alt="icone Education"></a>
        <p>
          <?php $chiffre_francais = number_format($education, 0, ',', ' '); echo $chiffre_francais; ?>
        </p>
      </li>
    </ul>
  </div>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <?php } while ($row_geometries = mysql_fetch_assoc($geometries)); ?>
  <div class="row-fluid">
    <div class="titre-gris">
    <h3>Balance totale des ressources issues de la carte</h3>
    </div>
    <div class="span2">&nbsp;</div>
    <ul class="token">
      <li class="span1"><a title="Budget"><img src="http://www.generation-city.com/monde/assets/img/ressources/budget.png" alt="icone Budget"></a>
        <p> <strong>
          <?php $chiffre_francais = number_format($tot_budget, 0, ',', ' '); echo $chiffre_francais; ?>
          </strong> </p>
      </li>
      <li class="span1"><a title="Industrie"><img src="http://www.generation-city.com/monde/assets/img/ressources/industrie.png" alt="icone Industrie"></a>
        <p> <strong>
          <?php $chiffre_francais = number_format($tot_industrie, 0, ',', ' '); echo $chiffre_francais; ?>
          </strong> </p>
      </li>
      <li class="span1"><a title="Commerce"><img src="http://www.generation-city.com/monde/assets/img/ressources/bureau.png" alt="icone Commerce"></a>
        <p> <strong>
          <?php $chiffre_francais = number_format($tot_commerce, 0, ',', ' '); echo $chiffre_francais; ?>
          </strong> </p>
      </li>
      <li class="span1"><a title="Agriculture"><img src="http://www.generation-city.com/monde/assets/img/ressources/agriculture.png" alt="icone Agriculture"></a>
        <p> <strong>
          <?php $chiffre_francais = number_format($tot_agriculture, 0, ',', ' '); echo $chiffre_francais; ?>
          </strong> </p>
      </li>
      <li class="span1"><a title="Tourisme"><img src="http://www.generation-city.com/monde/assets/img/ressources/tourisme.png" alt="icone Tourisme"></a>
        <p> <strong>
          <?php $chiffre_francais = number_format($tot_tourisme, 0, ',', ' '); echo $chiffre_francais; ?>
          </strong> </p>
      </li>
      <li class="span1"><a title="Recherche"><img src="http://www.generation-city.com/monde/assets/img/ressources/recherche.png" alt="icone Recherche"></a>
        <p> <strong>
          <?php $chiffre_francais = number_format($tot_recherche, 0, ',', ' '); echo $chiffre_francais; ?>
          </strong> </p>
      </li>
      <li class="span1"><a title="Environnement"><img src="http://www.generation-city.com/monde/assets/img/ressources/environnement.png" alt="icone Environnement"></a>
        <p> <strong>
          <?php $chiffre_francais = number_format($tot_environnement, 0, ',', ' '); echo $chiffre_francais; ?>
          </strong> </p>
      </li>
      <li class="span1"><a title="Education"><img src="http://www.generation-city.com/monde/assets/img/ressources/education.png" alt="icone Education"></a>
        <p> <strong>
          <?php $chiffre_francais = number_format($tot_education, 0, ',', ' '); echo $chiffre_francais; ?>
          </strong> </p>
      </li>
    </ul>
  </div>
</div>
<div class="modal-footer">
  <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
</div>
<?php
mysql_free_result($geometries);
?>
