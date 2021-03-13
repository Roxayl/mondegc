<?php

//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=20))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ' . legacyPage('Haut-Conseil'));
exit();
	}

//requete instituts
$institut_id = 5;

$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

//requete liste temperances
$type_classement = 'ch_inf_off_nom ASC';
if (isset($_GET['type_classement_inf'])) {
  $type_classement = $_GET['type_classement_inf'];
} 

$maxRows_liste_temperance = 2;
$pageNum_liste_temperance = 0;
if (isset($_GET['pageNum_liste_temperance'])) {
  $pageNum_liste_temperance = $_GET['pageNum_liste_temperance'];
}
$startRow_liste_temperance = $pageNum_liste_temperance * $maxRows_liste_temperance;


$query_liste_temperance = sprintf("SELECT ch_temp_id as id, ch_pay_nom as nom, ch_temp_element as element, ch_temp_element_id as element_id, ch_temp_date as date, ch_temp_mis_jour as mis_jour, ch_temp_statut as statut, ch_temp_note as note, ch_temp_tendance as tendance FROM temperance LEFT JOIN pays ON ch_temp_element_id = ch_pay_id WHERE ch_temp_element='pays'
UNION
SELECT ch_temp_id as id, ch_vil_nom as nom, ch_temp_element as element, ch_temp_element_id as element_id, ch_temp_date as date, ch_temp_mis_jour as mis_jour, ch_temp_statut as statut, ch_temp_note as note, ch_temp_tendance as tendance FROM temperance LEFT JOIN villes ON ch_temp_element_id = ch_vil_ID WHERE ch_temp_element='ville'
ORDER BY date asc");
$query_limit_liste_temperance = sprintf("%s LIMIT %d, %d", $query_liste_temperance, $startRow_liste_temperance, $maxRows_liste_temperance);
$liste_temperance = mysql_query($query_limit_liste_temperance, $maconnexion) or die(mysql_error());
$row_liste_temperance = mysql_fetch_assoc($liste_temperance);

if (isset($_GET['totalRows_liste_temperance'])) {
  $totalRows_liste_temperance = $_GET['totalRows_liste_temperance'];
} else {
  $all_liste_temperance = mysql_query($query_liste_temperance);
  $totalRows_liste_temperance = mysql_num_rows($all_liste_temperance);
}
$totalPages_liste_temperance = ceil($totalRows_liste_temperance/$maxRows_liste_temperance)-1;

$queryString_liste_temperance = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_liste_temperance") == false && 
        stristr($param, "totalRows_liste_temperance") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_liste_temperance = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_liste_temperance = sprintf("&totalRows_liste_temperance=%d%s", $totalRows_liste_temperance, $queryString_liste_temperance);

//requete liste infrastructures officielles

$type_classement = 'ch_inf_off_nom ASC';
if (isset($_GET['type_classement_inf'])) {
  $type_classement = $_GET['type_classement_inf'];
} 


$maxRows_liste_infra_officielles = 10;
$pageNum_liste_infra_officielles = 0;
if (isset($_GET['pageNum_liste_infra_officielles'])) {
  $pageNum_liste_infra_officielles = $_GET['pageNum_liste_infra_officielles'];
}
$startRow_liste_infra_officielles = $pageNum_liste_infra_officielles * $maxRows_liste_infra_officielles;


$query_liste_infra_officielles = sprintf("SELECT * FROM infrastructures_officielles ORDER BY $type_classement");
$query_limit_liste_infra_officielles = sprintf("%s LIMIT %d, %d", $query_liste_infra_officielles, $startRow_liste_infra_officielles, $maxRows_liste_infra_officielles);
$liste_infra_officielles = mysql_query($query_limit_liste_infra_officielles, $maconnexion) or die(mysql_error());
$row_liste_infra_officielles = mysql_fetch_assoc($liste_infra_officielles);

if (isset($_GET['totalRows_liste_infra_officielles'])) {
  $totalRows_liste_infra_officielles = $_GET['totalRows_liste_infra_officielles'];
} else {
  $all_liste_infra_officielles = mysql_query($query_liste_infra_officielles);
  $totalRows_liste_infra_officielles = mysql_num_rows($all_liste_infra_officielles);
}
$totalPages_liste_infra_officielles = ceil($totalRows_liste_infra_officielles/$maxRows_liste_infra_officielles)-1;

$queryString_liste_infra_officielles = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_liste_infra_officielles") == false && 
        stristr($param, "totalRows_liste_infra_officielles") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_liste_infra_officielles = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_liste_infra_officielles = sprintf("&totalRows_liste_infra_officielles=%d%s", $totalRows_liste_infra_officielles, $queryString_liste_infra_officielles);

// Groupes d'infra
/** @var \GenCity\Monde\Temperance\InfraGroup[] $infraGroupList */
$infraGroupList = \GenCity\Monde\Temperance\InfraGroup::getAll();


$_SESSION['last_work'] = "institut_economie.php";

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Gérer le <?= __s($row_institut['ch_ins_nom']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../assets/css/colorpicker.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('');
}
</style>
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
    </script>
<!-- Color Picker  -->
<script src="../assets/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<!-- MODAL -->
<script src="../assets/js/bootstrap-modalmanager.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script>
		$(function(){
			window.prettyPrint && prettyPrint()
			$('#cp3').colorpicker({
format: 'hex'});
$('#cp4').colorpicker({
format: 'hex'});
		});
	</script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div class="container" id="overview"> 
  
  <!-- Page CONTENT
    ================================================== -->
  <section class="corps-page">
    <?php include(DEF_ROOTPATH . 'php/menu-haut-conseil.php'); ?>

    <!-- formulaire de modification instituts
     ================================================== -->
    <form class="pull-right-cta cta-title" action="<?= DEF_URI_PATH ?>back/insitut_modifier.php" method="post">
      <input name="institut_id" type="hidden" value="<?= e($row_institut['ch_ins_ID']) ?>">
      <button class="btn btn-primary btn-cta" type="submit" title="modifier les informations sur l'institut"><i class="icon-edit icon-white"></i> Modifier la description</button>
    </form>
    <!-- Titre page
        ================================================== -->
    <div id="titre_institut" class="titre-bleu anchor">
      <h1>Gérer le <?= e($row_institut['ch_ins_nom']) ?></h1>
    </div>
    <div class="clearfix"></div>

    <?php renderElement('errormsgs'); ?>

    <!-- Liste des temperances
     ================================================== -->
    <div class="row-fluid">
      <div class="span12">
        <div class="titre-gris anchor" id="liste-temperance">
          <h3>Liste des tempérances</h3>
        </div>
        <ul class="listes">
          <?php do { ?>
            <li class="row-fluid liste-temp"> 
              <!-- Boutons supprimer -->
              <div class="span1"> <a href="../php/temperance-confirmer-supprimer-modal.php?ch_temp_id=<?= e($row_liste_temperance['id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="supprimer cette temperance"><i class="icon-remove"></i></a> </div>
              <!-- affichage du statut -->
              <?php if ($row_liste_temperance['statut'] == 1) {?>
              <div class="span2 liste-statut-temp" style="background-color: rgba(255,102,0,1);">
                <p>Phase de lancement</p>
              </div>
              <?php   } elseif ($row_liste_temperance['statut'] == 2) {?>
              <div class="span2 liste-statut-temp" style="background-color: rgba(0,204,51,1);">
                <p>Ouverture notations</p>
              </div>
              <?php } elseif ($row_liste_temperance['statut'] == 3) {?>
              <div class="span2 liste-statut-temp" style="background-color: rgba(0,0,255,1);">
                <p>Publiée</p>
              </div>
              <?php  } else {?>
              <div class="span2 liste-statut-temp" style="background-color: rgba(204,0,0,1);">
                <p>Statut inconnu</p>
              </div>
              <?php } ?>
              <!-- Nom element tempere -->
              <div class="span3">
                <h5><?= e($row_liste_temperance['element']) ?>&nbsp;: <?= e($row_liste_temperance['nom']) ?></h5>
              </div>
              <!-- contenu categorie -->
              <div class="span4"><em>Lancée il y a
                <?php 
		$now= date("Y-m-d G:i:s");
	  $d1 = new DateTime($row_liste_temperance['date']);
	  $d2 = new DateTime($now);
	  $diff = get_timespan_string_hour($d1, $d2);
	  echo $diff;?>
                </em>
                <?php if (($row_liste_temperance['statut'] == 2) OR ($row_liste_temperance['statut'] == 3))  { // visible si en phase 2
				$idtemperance = $row_liste_temperance ['id'];
				// requete nb de juges votants

$query_nb_juges = sprintf("SELECT COUNT(ch_not_temp_juge) as nbjuges FROM notation_temperance WHERE ch_not_temp_temperance_id =%s", GetSQLValueString( $idtemperance, "int"));
$nb_juges = mysql_query($query_nb_juges, $maconnexion) or die(mysql_error());
$row_nb_juges = mysql_fetch_assoc($nb_juges);
$totalRows_nb_juges = mysql_num_rows($nb_juges);
				 ?>
                <p>&nbsp;</p>
                <strong>
                <p>Nombre de juges ayant voté : <?= e($row_nb_juges['nbjuges']) ?></p>
                </strong>
                <?php } ?>
              </div>
              <div class="span2"> 
                <!-- Boutons modifier -->
                <?php if ($row_liste_temperance['statut'] == 1) { // visible si en phase 1 ?>
                <a class="btn btn-primary" href="../php/temperance-modifier-phase2-modal.php?ch_temp_id=<?= e($row_liste_temperance['id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="passer &agrave; la phase d'ouverture des votes">Ouvrir les votes</a>
                <?php } elseif ($row_liste_temperance['statut'] == 2) {  // visible si en phase 2 ?>
                <a class="btn btn-primary" href="../php/temperance-modifier-phase3-modal.php?ch_temp_id=<?= e($row_liste_temperance['id']) ?>&element=<?= e($row_liste_temperance['element']) ?>&element_id=<?= e($row_liste_temperance['element_id']) ?>&nb_juges=<?= e($row_nb_juges['nbjuges']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="publier la note et le rapport de synth&egrave;se des juges">Clore les votes</a>
                <?php } elseif ($row_liste_temperance['statut'] == 3) {  // visible si en phase 3
				if ($row_liste_temperance['element'] == "pays") { ?>
                <a class="btn btn-temperance" href="../php/temperance-rapport-pays.php?ch_temp_id=<?= e($row_liste_temperance['id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="voir le rapport publi&eacute; sur le site">Note&nbsp;: <?php echo get_note_finale($row_liste_temperance['note']); ?>
                <?php	if ($row_liste_temperance['tendance'] == "sup") { ?>
                <i class="icon-arrow-up icon-white"></i>
                <?php } elseif ($row_liste_temperance['tendance'] == "inf") { ?>
                <i class="icon-arrow-down icon-white"></i>
                <?php } else { ?>
                <i class=" icon-arrow-right icon-white"></i>
                <?php } ?>
                </a>
                <?php }
                if ($row_liste_temperance['element'] == "ville") { ?>
                <a class="btn btn-temperance" href="../php/temperance-rapport-ville.php?ch_temp_id=<?= e($row_liste_temperance['id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="voir le rapport publi&eacute; sur le site">Note&nbsp;: <?php echo get_note_finale($row_liste_temperance['note']); ?>
                <?php	if ($row_liste_temperance['tendance'] == "sup") { ?>
                <i class="icon-arrow-up icon-white"></i>
                <?php } elseif ($row_liste_temperance['tendance'] == "inf") { ?>
                <i class="icon-arrow-down icon-white"></i>
                <?php } else { ?>
                <i class=" icon-arrow-right icon-white"></i>
                <?php } ?>
                </a>
                <?php }} else {}  // invisible autres cas ?>
              </div>
            </li>
            <?php } while ($row_liste_temperance = mysql_fetch_assoc($liste_temperance)); ?>
        </ul>
        <!-- Pagination de la liste -->
        <p>&nbsp;</p>
        <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_liste_temperance + 1) ?> &agrave; <?php echo min($startRow_liste_temperance + $maxRows_liste_temperance, $totalRows_liste_temperance) ?> sur <?php echo $totalRows_liste_temperance ?>
          <?php if ($pageNum_liste_temperance > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_temperance=%d%s#liste-categories", $currentPage, max(0, $pageNum_liste_temperance - 1), $queryString_liste_temperance); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
          <?php if ($pageNum_liste_temperance < $totalPages_liste_temperance) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_temperance=%d%s#liste-categories", $currentPage, min($totalPages_liste_temperance, $pageNum_liste_temperance + 1), $queryString_liste_temperance); ?>"> <i class="icon-forward"></i></a>
            <?php } // Show if not last page ?>
          </small> </p>
        <!-- Modal et script -->
        <div class="modal container fade" id="Modal-Monument" data-width="760"></div>
        <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script> 
        <!-- Ajouter une temperance
        ================================================== --> 
        <!-- Button to trigger modal --> 
        <a class="btn btn-primary btn-margin-left" href="../php/temperance-pays-ajouter.php" data-toggle="modal" data-target="#Modal-Monument" title="Ajouter une nouvelle temp&eacute;rance pour un pays">Temp&eacute;rer un pays</a> <a class="btn btn-primary btn-margin-left" href="../php/temperance-ville-ajouter.php" data-toggle="modal" data-target="#Modal-Monument" title="Ajouter une nouvelle temp&eacute;rance pour une ville">Temp&eacute;rer une ville</a> 
        <!-- Modal --> 
      </div>
      <p>&nbsp;</p>
      <!-- Modal et script -->
      <div class="modal container fade" id="#Modal-Monument" data-width="760"></div>
      <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script> 
      <!-- liste communique de l'institut
     ================================================== -->
      <div class="row-fluid">
        <div class="span12">
          <div class="titre-gris" id="mes-communiques" class="anchor">
          <h3>Communiqués</h3>
        </div>
        <div class="alert alert-tips">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          Les communiqués postés &agrave; partir de cette page seront considérés comme des annonces officielles &eacute;manant de cette institution. Ils seront publiés sur la page de l'institut et dans la partie événement du site. Utilisez les communiqu&eacute;s pour animer le site</div>
        <?php 
$com_cat = "institut";
$userID = $_SESSION['user_ID'];
$com_element_id = 5;
include(DEF_ROOTPATH . 'php/communiques-back.php'); ?>
      </div>
    </div>


    <!-- Groupe d'infrastructures
     ================================================== -->
    <div class="row-fluid">
      <div class="span12">
        <div class="titre-gris anchor" id="groupe-infra">
          <h3>Groupe d'infrastructures</h3>
        </div>

      <a class="btn btn-primary btn-margin-left" href="../php/infra_group_modal.php"
         data-toggle="modal" data-target="#Modal-Monument" title="Créer un groupe d'infra"
        ><i class="icon-file icon-white"></i> Créer un groupe d'infrastructures</a>

      <ul class="listes">
      <?php foreach($infraGroupList as $row):

          /** @var \GenCity\Monde\Temperance\InfraOfficielle[] $thisInfra */
          $thisInfraOff = \GenCity\Monde\Temperance\InfraOfficielle::getListFromGroup($row); ?>

          <li>
              <h4><?= __s($row->get('nom_groupe')) ?></h4>
              <div class="btn-group" style="display: inline-block;">
                  <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                    Voir les infrastructures
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                  <?php foreach($thisInfraOff as $rowInfraOff): ?>
                    <li><img src="<?= __s($rowInfraOff->get('ch_inf_off_icone')) ?>"
                             style="height: 26px;" alt="(icone)"
                        ><?= __s($rowInfraOff->get('ch_inf_off_nom')) ?></li>
                  <?php endforeach; ?>
                  <?php if(empty($thisInfraOff)): ?>
                      <li><i class="icon-exclamation-sign"></i>
                          Ce groupe d'infrastructures ne regroupe aucune infrastructure officielle.</li>
                  <?php endif; ?>
                  </ul>
              </div>
              <a class="btn btn-primary" href="../php/infra_group_modal.php?group_id=<?= $row->get('id') ?>"
                 data-toggle="modal" data-target="#Modal-Monument" title="Modifier ce groupe d'infra"
                ><i class="icon-edit icon-white"></i> Modifier</a>
              <a class="btn btn-danger" href="../php/infra_group_modal_suppr.php?group_id=<?= $row->get('id') ?>"
                 data-toggle="modal" data-target="#Modal-Monument" title="Supprimer ce groupe d'infra"
                ><i class="icon-trash icon-white"></i> Supprimer</a>
          </li>

      <?php endforeach; ?>
      </ul>

    </div>


    <!-- Liste des infrastructures officielles
     ================================================== -->
    <div class="row-fluid">
      <div class="span12">
        <div class="titre-gris anchor" id="liste-infrastructures-officielles">
          <h3>Liste des infrastructures officielles</h3>
        </div>
        <!-- Liste pour choix de classement -->
        <div id="select-categorie">
          <form action="<?= DEF_URI_PATH ?>back/institut_economie.php#liste-infrastructures-officielles" method="GET">
            <select name="type_classement_inf" id="type_classement_inf" onchange="this.form.submit()" class="span3">
              <option value="ch_inf_off_nom ASC" <?php if ($type_classement == 'ch_inf_off_nom ASC') {?>selected<?php } ?>>Classer par ordre alphab&eacute;tique</option>
              <option value="ch_inf_off_date DESC" <?php if ($type_classement == 'ch_inf_off_date DESC') {?>selected<?php } ?>>Classer par date de cr&eacute;ation</option>
              <option value="ch_inf_off_budget DESC" <?php if ($type_classement == 'ch_inf_off_budget DESC') {?>selected<?php } ?>>Classer par budget</option>
              <option value="ch_inf_off_Industrie DESC" <?php if ($type_classement == 'ch_inf_off_Industrie DESC') {?>selected<?php } ?>>Classer par Industrie</option>
              <option value="ch_inf_off_Commerce DESC" <?php if ($type_classement == 'ch_inf_off_Commerce DESC') {?>selected<?php } ?>>Classer par Commerce</option>
              <option value="ch_inf_off_Agriculture DESC" <?php if ($type_classement == 'ch_inf_off_Agriculture DESC') {?>selected<?php } ?>>Classer par Agriculture</option>
              <option value="ch_inf_off_Tourisme DESC" <?php if ($type_classement == 'ch_inf_off_Tourisme DESC') {?>selected<?php } ?>>Classer par Tourisme</option>
              <option value="ch_inf_off_Recherche DESC" <?php if ($type_classement == 'ch_inf_off_Recherche DESC') {?>selected<?php } ?>>Classer par Recherche</option>
              <option value="ch_inf_off_Environnement DESC" <?php if ($type_classement == 'ch_inf_off_Environnement DESC') {?>selected<?php } ?>>Classer par Environnement</option>
              <option value="ch_inf_off_Education DESC" <?php if ($type_classement == 'ch_inf_off_Education DESC') {?>selected<?php } ?>>Classer par Education</option>
            </select>
          </form>
        </div>
        <ul class="listes">
          <?php do {

            $thisInfraOff = new \GenCity\Monde\Temperance\InfraOfficielle($row_liste_infra_officielles['ch_inf_off_id']); ?>
            <li class="row-fluid"> 
              <!-- ICONE infrastructures -->
              <div class="span2"><img src="<?= e($row_liste_infra_officielles['ch_inf_off_icone']) ?>" alt="icone <?= e($row_liste_infra_officielles['ch_inf_off_nom']) ?>"></div>
              <!-- contenu categorie -->
              <div class="span10 info-listes"> 
                <!-- Boutons modifier / supprimer --> 
                <a class="pull-right" href="../php/infrastructure-officielle-supprimmer-modal.php?infrastructure_off=<?= e($row_liste_infra_officielles['ch_inf_off_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="supprimer cette infrastructure"><i class="icon-remove"></i></a> <a class="pull-right" href="../php/infrastructure-officielle-modifier-modal.php?infrastructure_off=<?= e($row_liste_infra_officielles['ch_inf_off_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="modifier cette infrastructure"><i class="icon-pencil"></i></a> 
                <!-- Desc categorie -->
                <h4><?= e($row_liste_infra_officielles['ch_inf_off_nom']) ?></h4>

                <?php if($thisInfraOff->hasGroup()): ?>
                <p><strong><?= $thisInfraOff->getGroup()->get('nom_groupe') ?></strong></p>
                <?php else: ?>
                <p><i style="color: red;">Pas de groupe d'infrastructure. Cette infrastructure n'apparaît pas dans la page d'ajout d'infrastructure.</i></p>
                <?php endif; ?>
                <p><?= htmlPurify($row_liste_infra_officielles['ch_inf_off_desc']) ?></p>

                <div class="row-fluid">
                  <div class="span3 icone-ressources"> <img src="../assets/img/ressources/budget.png" alt="icone Budget">
                    <p>Budget&nbsp;: <strong><?= e($row_liste_infra_officielles['ch_inf_off_budget']) ?></strong></p>
                    <img src="../assets/img/ressources/industrie.png" alt="icone Industrie">
                    <p>Industrie&nbsp;: <strong><?= e($row_liste_infra_officielles['ch_inf_off_Industrie']) ?></strong></p>
                  </div>
                  <div class="span3 icone-ressources"> <img src="../assets/img/ressources/bureau.png" alt="icone Commerce">
                    <p>Commerce&nbsp;: <strong><?= e($row_liste_infra_officielles['ch_inf_off_Commerce']) ?></strong></p>
                    <img src="../assets/img/ressources/agriculture.png" alt="icone Agriculture">
                    <p>Agriculture&nbsp;: <strong><?= e($row_liste_infra_officielles['ch_inf_off_Agriculture']) ?></strong></p>
                  </div>
                  <div class="span3 icone-ressources"> <img src="../assets/img/ressources/tourisme.png" alt="icone Tourisme">
                    <p>Tourisme&nbsp;: <strong><?= e($row_liste_infra_officielles['ch_inf_off_Tourisme']) ?></strong></p>
                    <img src="../assets/img/ressources/recherche.png" alt="icone Recherche">
                    <p>Recherche&nbsp;: <strong><?= e($row_liste_infra_officielles['ch_inf_off_Recherche']) ?></strong></p>
                  </div>
                  <div class="span3 icone-ressources"> <img src="../assets/img/ressources/environnement.png" alt="icone Evironnement">
                    <p>Environnement&nbsp;: <strong><?= e($row_liste_infra_officielles['ch_inf_off_Environnement']) ?></strong></p>
                    <img src="../assets/img/ressources/education.png" alt="icone Education">
                    <p>Education&nbsp;: <strong><?= e($row_liste_infra_officielles['ch_inf_off_Education']) ?></strong></p>
                  </div>
                </div>
              </div>
            </li>
            <?php } while ($row_liste_infra_officielles = mysql_fetch_assoc($liste_infra_officielles)); ?>
        </ul>
        <!-- Pagination de la liste -->
        <p>&nbsp;</p>
        <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_liste_infra_officielles + 1) ?> &agrave; <?php echo min($startRow_liste_infra_officielles + $maxRows_liste_infra_officielles, $totalRows_liste_infra_officielles) ?> sur <?php echo $totalRows_liste_infra_officielles ?>
          <?php if ($pageNum_liste_infra_officielles > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_infra_officielles=%d%s#liste-categories", $currentPage, max(0, $pageNum_liste_infra_officielles - 1), $queryString_liste_infra_officielles); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
          <?php if ($pageNum_liste_infra_officielles < $totalPages_liste_infra_officielles) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_liste_infra_officielles=%d%s#liste-categories", $currentPage, min($totalPages_liste_infra_officielles, $pageNum_liste_infra_officielles + 1), $queryString_liste_infra_officielles); ?>"> <i class="icon-forward"></i></a>
            <?php } // Show if not last page ?>
          </small> </p>
        <!-- Modal et script -->
        <div class="modal container fade" id="Modal-Monument" data-width="760"></div>
        <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script> 
        <!-- Ajouter une categorie
        ================================================== --> 
        <!-- Button to trigger modal --> 
        <a class="btn btn-primary btn-margin-left" href="../php/infrastructure-officielle-ajouter-modal.php" data-toggle="modal" data-target="#Modal-Monument" title="Ajouter une infrastructure officielle">Ajouter une infrastructure</a> 
        <!-- Modal --> 
        
      </div>
      <p>&nbsp;</p>
      <!-- Modal et script -->
      <div class="modal container fade" id="#Modal-Monument" data-width="760"></div>
      <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script>
      <p>&nbsp;</p>
    </div>
  </section>
</div>
<!-- END CONTENT
    ================================================== --> 

<!-- Footer
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>
</body>
</html>
